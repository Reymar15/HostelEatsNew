@extends('layouts.app')

@section('content')
    <section class="dashboard-stat-grid">
        <article class="stat-card">
            <span>Cart</span>
            <strong>{{ collect($cartItems)->sum('qty') }}</strong>
            <p>items waiting</p>
        </article>
        <article class="stat-card">
            <span>Active Orders</span>
            <strong>{{ count($activeOrders) }}</strong>
            <p>being prepared</p>
        </article>
        <article class="stat-card">
            <span>Branches</span>
            <strong>{{ count($branches) }}</strong>
            <p>available today</p>
        </article>
    </section>

    <section class="hero compact-hero">
        <div class="hero-copy">
            <span class="eyebrow">User Dashboard</span>
            <h2>Hello, {{ $profile['name'] }}.</h2>
            <p>Browse food, check your current orders, and reorder your hostel favorites from this student dashboard.</p>
            <div class="hero-actions">
                <a href="{{ route('menu.index') }}" class="primary-action">Order Food</a>
                <a href="{{ route('orders.current') }}" class="secondary-action">View My Orders</a>
            </div>
        </div>
    </section>

    <section class="section-head">
        <div>
            <h2>Recommended for you</h2>
            <p>Quick picks from the current sample menu.</p>
        </div>
    </section>

    <section class="menu-grid">
        @foreach (array_slice($foods, 0, 4) as $food)
            @include('pages.partials.food-card', ['food' => $food])
        @endforeach
    </section>
@endsection
