<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use App\Notifications\OtpVerificationNotification;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name'     => ['required', 'string', 'min:3', 'max:120'],
                'email'    => ['required', 'email', 'max:180', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::min(6)],
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'customer',
                'is_admin' => false,
            ]);
        } catch (QueryException $e) {
            report($e);

            return back()
                ->withInput($request->only('name', 'email'))
                ->with('error', 'Database connection unavailable. Please try again later.');
        }

        $this->generateAndSendOtp($user);

        session([
            'otp_user_id' => $user->id,
            'otp_email'   => $user->email,
            'otp_name'    => $user->name,
        ]);

        return redirect()->route('otp.verify.form')
            ->with('success', 'Account created! We sent a 6-digit code to ' . $user->email);
    }

    public static function generateAndSendOtp(User $user): void
    {
        // Invalidate all previous OTPs for this user
        OtpVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $plainOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::create([
            'user_id'    => $user->id,
            'otp_code'   => Hash::make($plainOtp),
            'expires_at' => now()->addMinutes(10),
            'is_used'    => false,
            'attempts'   => 0,
        ]);

        // Store plain OTP in session for dev display (log mailer fallback)
        session(['dev_otp' => $plainOtp]);

        try {
            $user->notify(new OtpVerificationNotification($plainOtp));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OTP email failed: ' . $e->getMessage());
        }
    }
}
