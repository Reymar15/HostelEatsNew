@extends('layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow">For hungry hostel students</span>
            <h2>Crave it. Order it. Eat it. Repeat.</h2>
            <p>Browse favorite campus branches, build a quick cart, and track active orders from one clean dashboard.</p>
            <div class="hero-actions">
                <a href="{{ route('menu.index') }}" class="primary-action">Browse Menu</a>
                <a href="{{ route('branches.index') }}" class="secondary-action">View Branches</a>
            </div>
        </div>
        <div class="food-mosaic" aria-hidden="true">
            <span>Burger</span>
            <span>Chicken</span>
            <span>Pasta</span>
            <span>Coffee</span>
            <span>Fries</span>
            <span>Drinks</span>
        </div>
    </section>

    <section class="section-head">
        <div>
            <h2>Pick a branch</h2>
            <p>Five favorites. Always open for hostel orders.</p>
        </div>
        <a href="{{ route('branches.index') }}">View all</a>
    </section>

    <section class="branch-grid">
        @foreach ($branches as $branch)
            <a class="branch-card branch-card-link {{ $branch['accent'] }}" href="{{ route('branches.show', $branch['id']) }}" data-searchable="{{ strtolower($branch['name'].' '.$branch['description']) }}">
                <div class="branch-logo-shell">
                    <img class="branch-card-logo" src="{{ $branch['logo'] }}" alt="{{ $branch['name'] }} logo" loading="lazy">
                </div>
                <h3>{{ $branch['name'] }}</h3>
                <p>{{ $branch['description'] }}</p>
                <div class="branch-card-image">
                    <img src="{{ $branch['banner'] }}" alt="{{ $branch['name'] }} branch food" loading="lazy">
                </div>
                <strong>{{ $branch['status'] }}</strong>
            </a>
        @endforeach
    </section>

    <section class="section-head">
        <div>
            <h2>Popular Menu</h2>
            <p>Best-sellers from all partner branches.</p>
        </div>
        <a href="{{ route('menu.index') }}">View all menu</a>
    </section>

    <section class="menu-grid">
        @foreach (array_slice($foods, 0, 6) as $food)
            @include('pages.partials.food-card', ['food' => $food])
        @endforeach
    </section>
@endsection
