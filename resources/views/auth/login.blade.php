@extends('layouts.auth', [
    'headline'    => 'Welcome back to HostelEats.',
    'subheadline' => 'Login to browse branches, manage orders, and keep your cart moving.',
])

@section('content')
    <form class="auth-card" method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="auth-card-head">
            <p class="crumb">User Login</p>
            <h2>Login</h2>
            <span>Sample: user@gmail.com / 123456</span>
        </div>

        {{-- Unverified email warning --}}
        @if (session('unverified_email'))
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:1rem 1.25rem;margin-bottom:.5rem;">
                <p style="color:#b91c1c;font-size:.875rem;font-weight:600;margin:0 0 .4rem;">
                    ⚠ Email Not Verified
                </p>
                <p style="color:#b91c1c;font-size:.85rem;margin:0 0 .75rem;">
                    Your email address has not been verified. Please check your Gmail inbox and verify your account before logging in.
                </p>
                <form method="POST" action="{{ route('verification.resend') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                    <button type="submit" style="font-size:.8rem;color:#16a34a;background:none;border:none;cursor:pointer;text-decoration:underline;padding:0;">
                        Resend verification email →
                    </button>
                </form>
            </div>
        @endif

        @if (session('success'))
            <p class="form-success" style="color:#16a34a;background:#f0fdf4;padding:.75rem 1rem;border-radius:8px;font-size:.9rem;">
                ✓ {{ session('success') }}
            </p>
        @endif

        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" placeholder="student@gmail.com" required>
        </label>
        @error('email')<p class="form-error">{{ $message }}</p>@enderror

        <label>Password
            <span class="password-field">
                <input type="password" name="password" placeholder="Your password" required minlength="6">
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>
        @error('password')<p class="form-error">{{ $message }}</p>@enderror

        @if (session('error'))
            <p class="form-error">{{ session('error') }}</p>
        @endif

        <label class="check-row">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span>Remember me</span>
        </label>

        <button type="submit" class="primary-action">Login</button>

        <p class="auth-link">No account yet? <a href="{{ route('signup') }}">Create Account</a></p>
        <p class="auth-link muted-link">Admin user? <a href="{{ route('admin.login') }}">Open Admin Login</a></p>
    </form>
@endsection
