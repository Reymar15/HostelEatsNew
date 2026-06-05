@extends('layouts.auth', [
    'headline' => 'Welcome back to HostelEats.',
    'subheadline' => 'Login with the sample user account and continue to the hostel food dashboard.',
])

@section('content')
    <form class="auth-card" method="POST" action="{{ route('login.store') }}">
        @csrf
        <div class="auth-card-head">
            <p class="crumb">User Login</p>
            <h2>Login</h2>
            <span>Sample: user@gmail.com / 123456</span>
        </div>

        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" placeholder="user@gmail.com" required>
        </label>

        <label>Password
            <span class="password-field">
                <input type="password" name="password" placeholder="123456" required minlength="6">
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>

        <label class="check-row">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <span>Remember me</span>
        </label>

        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
        @error('password')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <button type="submit" class="primary-action">Login</button>

        <p class="auth-link">No account yet? <a href="{{ route('signup') }}">Create Account</a></p>
        <p class="auth-link muted-link">Admin user? <a href="{{ route('admin.login') }}">Open Admin Login</a></p>
    </form>
@endsection
