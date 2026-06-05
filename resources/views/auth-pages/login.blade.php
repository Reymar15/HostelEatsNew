@extends('layouts.auth', [
    'headline' => 'Welcome back to HostelEats.',
    'subheadline' => 'Login as a student and jump straight to your food dashboard.',
])

@section('content')
    <form class="auth-card" data-auth-form data-redirect="{{ route('user.dashboard') }}">
        <div class="auth-card-head">
            <p class="crumb">User Login</p>
            <h2>Login</h2>
            <span>Use any valid email and password for this frontend demo.</span>
        </div>

        <label>Email
            <input type="email" name="email" placeholder="student@email.com" required>
        </label>

        <label>Password
            <input type="password" name="password" placeholder="Enter your password" required minlength="6">
        </label>

        <label class="check-row">
            <input type="checkbox" name="remember">
            <span>Remember me</span>
        </label>

        <p class="form-error" data-auth-error></p>
        <button type="submit" class="primary-action">Login</button>

        <p class="auth-link">No account yet? <a href="{{ route('signup') }}">Signup here</a></p>
        <p class="auth-link muted-link">Admin user? <a href="{{ route('admin.login') }}">Open Admin Login</a></p>
    </form>
@endsection
