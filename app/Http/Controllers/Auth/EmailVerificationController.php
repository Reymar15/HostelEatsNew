<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(): View|RedirectResponse
    {
        // If already verified and logged into session, go to dashboard
        $email = session('pending_verification_email');
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user && $user->hasVerifiedEmail()) {
                session()->forget(['pending_verification_email', 'pending_verification_name']);
                return redirect()->route('login')
                    ->with('success', 'Email already verified. You can now login.');
            }
        }

        return view('auth.verify-email', [
            'pendingEmail' => $email,
            'pendingName'  => session('pending_verification_name'),
        ]);
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill(); // marks email_verified_at

        session()->forget(['pending_verification_email', 'pending_verification_name']);

        return redirect()->route('verification.success');
    }

    public function resend(Request $request): RedirectResponse
    {
        $email = session('pending_verification_email') ?? $request->input('email');
        $user  = $email ? User::where('email', $email)->first() : null;

        if (! $user) {
            return back()->with('error', 'No pending account found. Please register first.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('success', 'Your email is already verified. Please login.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email resent! Please check your Gmail inbox.');
    }

    public function success(): View
    {
        return view('auth.email-verified');
    }
}
