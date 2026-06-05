@extends('layouts.auth', [
    'headline' => 'Create your hostel ordering account.',
    'subheadline' => 'Signup is frontend-only for now, perfect for demo and UI testing.',
])

@section('content')
    <form class="auth-card" data-signup-form>
        <div class="auth-card-head">
            <p class="crumb">User Signup</p>
            <h2>Create Account</h2>
            <span>Fill in your account details to continue.</span>
        </div>

        <label>Full Name
            <input type="text" name="name" placeholder="Juan Dela Cruz" required minlength="3">
        </label>

        <label>Email
            <input type="email" name="email" placeholder="student@email.com" required>
        </label>

        <label>Password
            <input type="password" name="password" placeholder="Minimum 6 characters" required minlength="6">
        </label>

        <label>Confirm Password
            <input type="password" name="password_confirmation" placeholder="Repeat password" required minlength="6">
        </label>

        <p class="form-error" data-auth-error></p>
        <button type="submit" class="primary-action">Signup</button>

        <p class="auth-link">Already registered? <a href="{{ route('login') }}">Back to Login</a></p>
    </form>
@endsection
