@extends('layouts.auth', [
    'headline' => 'Admin access for HostelEats.',
    'subheadline' => 'Monitor orders, branches, menu activity, and daily hostel demand.',
])

@section('content')
    <form class="auth-card admin-auth-card" data-auth-form data-redirect="{{ route('admin.dashboard') }}">
        <div class="auth-card-head">
            <p class="crumb">Admin Login</p>
            <h2>Admin Portal</h2>
            <span>Frontend-only admin login. Any valid email and password will continue.</span>
        </div>

        <label>Admin Email
            <input type="email" name="email" placeholder="admin@hosteleats.test" required>
        </label>

        <label>Admin Password
            <input type="password" name="password" placeholder="Enter admin password" required minlength="6">
        </label>

        <p class="form-error" data-auth-error></p>
        <button type="submit" class="danger-button">Login as Admin</button>

        <p class="auth-link">Student login? <a href="{{ route('login') }}">Go to User Login</a></p>
    </form>
@endsection
