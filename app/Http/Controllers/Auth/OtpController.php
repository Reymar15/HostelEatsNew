<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function showForm(): View|RedirectResponse
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);

        if ($user && $user->hasVerifiedEmail()) {
            session()->forget(['otp_user_id', 'otp_email', 'otp_name']);
            return redirect()->route('login')
                ->with('success', 'Email already verified. Please login.');
        }

        return view('auth.otp-verify', [
            'otpEmail' => session('otp_email'),
            'otpName'  => session('otp_name'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login')->with('error', 'Account not found.');
        }

        // Get the latest valid OTP record
        $otpRecord = OtpVerification::where('user_id', $userId)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otpRecord) {
            return back()->with('error', 'No active OTP found. Please request a new code.');
        }

        // Check max attempts (5 tries)
        if ($otpRecord->isMaxAttempts()) {
            return back()->with('error', 'Too many incorrect attempts. Please request a new code.');
        }

        // Check expiry
        if ($otpRecord->isExpired()) {
            return back()->with('error', 'Your code has expired. Please request a new code.');
        }

        // Increment attempts
        $otpRecord->increment('attempts');

        // Verify OTP
        if (! \Illuminate\Support\Facades\Hash::check($request->otp, $otpRecord->otp_code)) {
            $remaining = 5 - $otpRecord->attempts;
            return back()->with('error', "Incorrect code. {$remaining} attempt(s) remaining.");
        }

        // Valid OTP — mark as used and verify the user's email
        $otpRecord->update(['is_used' => true]);
        $user->markEmailAsVerified();

        session()->forget(['otp_user_id', 'otp_email', 'otp_name']);

        return redirect()->route('otp.success');
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = session('otp_user_id');
        $email  = session('otp_email') ?? $request->input('email');
        $user   = $userId
            ? User::find($userId)
            : ($email ? User::where('email', $email)->first() : null);

        if (! $user) {
            return back()->with('error', 'Account not found. Please register first.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('success', 'Your email is already verified. Please login.');
        }

        // Restore session if user came from login redirect
        session(['otp_user_id' => $user->id, 'otp_email' => $user->email, 'otp_name' => $user->name]);

        RegisterController::generateAndSendOtp($user);

        return back()->with('success', 'A new 6-digit code has been sent to ' . $user->email);
    }

    public function success(): View
    {
        return view('auth.otp-success');
    }
}
