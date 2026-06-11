@extends('layouts.auth', [
    'headline'    => 'One more step.',
    'subheadline' => 'Verify your email to unlock your HostelEats account and start ordering.',
])

@section('content')
    <div class="auth-card">
        <div class="auth-card-head">
            <p class="crumb">Email Verification</p>
            <h2>Check Your Gmail</h2>
            <span>
                We sent a verification link to
                @if ($pendingEmail)
                    <strong>{{ $pendingEmail }}</strong>
                @else
                    your registered email address
                @endif
            </span>
        </div>

        @if (session('success'))
            <p class="form-success" style="color:#16a34a;background:#f0fdf4;padding:.75rem 1rem;border-radius:8px;font-size:.9rem;">
                ✓ {{ session('success') }}
            </p>
        @endif
        @if (session('error'))
            <p class="form-error">{{ session('error') }}</p>
        @endif

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:1.25rem 1.5rem;margin:.5rem 0 1rem;">
            <p style="font-size:.95rem;color:#166534;margin:0 0 .5rem;font-weight:600;">📧 What to do next:</p>
            <ol style="color:#166534;font-size:.875rem;padding-left:1.25rem;margin:0;line-height:1.8;">
                <li>Open your Gmail inbox</li>
                <li>Look for an email from <strong>HostelEats</strong></li>
                <li>Click the <strong>"Verify Email Address"</strong> button</li>
                <li>Come back and login</li>
            </ol>
        </div>

        <p style="font-size:.85rem;color:#6b7280;text-align:center;margin:.25rem 0 1rem;">
            Verification link expires in <strong>60 minutes</strong>.
        </p>

        {{-- Resend form --}}
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            @if ($pendingEmail)
                <input type="hidden" name="email" value="{{ $pendingEmail }}">
            @endif
            <button type="submit" class="secondary-action" style="width:100%;margin-bottom:.75rem;">
                Resend Verification Email
            </button>
        </form>

        <p class="auth-link">
            Already verified? <a href="{{ route('login') }}">Go to Login</a>
        </p>
        <p class="auth-link muted-link" style="font-size:.8rem;">
            Wrong email? <a href="{{ route('signup') }}">Register again</a>
        </p>
    </div>
@endsection
