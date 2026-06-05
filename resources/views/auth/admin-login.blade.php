@extends('layouts.auth', [
    'headline' => 'Admin access for HostelEats.',
    'subheadline' => 'Use Super Admin for system-wide access or a Branch Admin account for one store only.',
])

@section('content')
    <form class="auth-card admin-auth-card" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <div class="auth-card-head">
            <p class="crumb">Admin Login</p>
            <h2>Admin Portal</h2>
            <span>Super Admin: admin@gmail.com / admin123</span>
        </div>

        <label>Admin Email
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@gmail.com" required>
        </label>

        <label>Admin Password
            <span class="password-field">
                <input type="password" name="password" placeholder="admin123" required minlength="6">
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password" aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </span>
        </label>

        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
        @error('password')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <div class="branch-admin-samples">
            <strong>Branch Admin samples</strong>
            <span>jollibee.admin@hosteleats.test / branch123</span>
            <span>mcdonalds.admin@hosteleats.test / branch123</span>
            <span>kfc.admin@hosteleats.test / branch123</span>
        </div>

        <button type="submit" class="danger-button">Login as Admin</button>

        <p class="auth-link">Student login? <a href="{{ route('login') }}">Go to User Login</a></p>
    </form>
@endsection
