<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'HostelEats' }} - Hostel Ordering System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if (session('error') || session('success'))
        <div class="auth-toast {{ session('error') ? 'error' : 'success' }}">
            {{ session('error') ?? session('success') }}
        </div>
    @endif

    <main class="auth-shell">
        <section class="auth-visual">
            <a class="brand auth-brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">HE</span>
                <span>
                    <strong>HostelEats</strong>
                    <small>Hostel Ordering System</small>
                </span>
            </a>
            <div class="auth-copy">
                <span class="eyebrow">Fast campus meals</span>
                <h1>{{ $headline ?? 'Order food for your hostel day.' }}</h1>
                <p>{{ $subheadline ?? 'Sign in to browse branches, manage orders, and keep your cart moving.' }}</p>
            </div>
            <div class="auth-food-strip" aria-hidden="true">
                <span>Chicken</span>
                <span>Burgers</span>
                <span>Coffee</span>
            </div>
        </section>

        <section class="auth-panel">
            @yield('content')
        </section>
    </main>

    <div class="success-modal {{ session('signup_success') ? 'open' : '' }}" data-success-modal aria-hidden="{{ session('signup_success') ? 'false' : 'true' }}">
        <div class="logout-dialog" role="dialog" aria-modal="true">
            <h2>Signup successful</h2>
            <p>{{ session('success') ?? 'Your HostelEats account is ready. You can now login.' }}</p>
            <div class="dialog-actions">
                <a class="primary-action" href="{{ route('login') }}">Go to Login</a>
                <button type="button" class="secondary-button" data-success-close>Stay here</button>
            </div>
        </div>
    </div>
</body>
</html>
