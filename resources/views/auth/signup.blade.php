@extends('layouts.auth', [
    'headline'    => 'Create your HostelEats account.',
    'subheadline' => 'Register to browse branches, order food, and track your deliveries.',
])

@section('content')
    <form class="auth-card" method="POST" action="{{ route('signup.store') }}">
        @csrf
        <div class="auth-card-head">
            <p class="crumb">User Signup</p>
            <h2>Create Account</h2>
            <span>Fill in your details. A verification email will be sent to your Gmail.</span>
        </div>

        <label>Full Name
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required minlength="3">
        </label>
        @error('name')<p class="form-error">{{ $message }}</p>@enderror

        <label>Email Address
            <input type="email" name="email" value="{{ old('email') }}" placeholder="student@gmail.com" required>
        </label>
        @error('email')<p class="form-error">{{ $message }}</p>@enderror

        <label>Password
            <span class="password-field">
                <input type="password" name="password" placeholder="Minimum 6 characters" required minlength="6">
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>
        @error('password')<p class="form-error">{{ $message }}</p>@enderror

        <label>Confirm Password
            <span class="password-field">
                <input type="password" name="password_confirmation" placeholder="Repeat password" required minlength="6">
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>

        @if (session('error'))
            <p class="form-error">{{ session('error') }}</p>
        @endif

        <button type="submit" class="primary-action">Create Account</button>

        <p class="auth-link">Already registered? <a href="{{ route('login') }}">Back to Login</a></p>
    </form>
@endsection
