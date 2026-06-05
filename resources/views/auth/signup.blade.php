@extends('layouts.auth', [
    'headline' => 'Create your HostelEats account.',
    'subheadline' => 'Signup stores sample student details in the Laravel session only, with no database required.',
])

@section('content')
    <form class="auth-card" method="POST" action="{{ route('signup.store') }}">
        @csrf
        <div class="auth-card-head">
            <p class="crumb">User Signup</p>
            <h2>Create Account</h2>
            <span>Fill in your account details to continue.</span>
        </div>

        <label>Full Name
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required minlength="3">
        </label>

        <label>Email
            <input type="email" name="email" value="{{ old('email') }}" placeholder="student@email.com" required>
        </label>

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

        @if ($errors->any())
            <p class="form-error">{{ $errors->first() }}</p>
        @endif

        <button type="submit" class="primary-action">Signup</button>

        <p class="auth-link">Already registered? <a href="{{ route('login') }}">Back to Login</a></p>
    </form>
@endsection
