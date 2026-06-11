@extends('layouts.app')

@section('content')
    <section class="section-head flush">
        <div>
            <h2>All Available Foods</h2>
            <p>Sample menu items from every branch. Buttons update your session cart instantly.</p>
        </div>
    </section>

    <section class="filter-strip">
        @foreach (['All', 'Burgers', 'Chicken', 'Pasta', 'Drinks', 'Coffee'] as $filter)
            <button type="button" data-category-filter="{{ $filter }}" class="{{ $loop->first ? 'active' : '' }}">{{ $filter }}</button>
        @endforeach
    </section>

    <div class="branch-menu-stack">
        @foreach ($branches as $branch)
            <section class="branch-menu-section">
                <div class="branch-banner">
                    <img src="{{ $branch['banner'] }}" alt="{{ $branch['name'] }} food banner" loading="lazy">
                    <div class="branch-banner-content">
                        <span class="branch-banner-logo">
                            <img src="{{ $branch['logo'] }}" alt="{{ $branch['name'] }} logo" loading="lazy">
                        </span>
                        <div>
                            <p>{{ $branch['status'] }}</p>
                            <h2>{{ $branch['name'] }}</h2>
                            <span>{{ $branch['description'] }}</span>
                            <a class="branch-banner-link" href="{{ route('branches.show', $branch['id']) }}">View Branch</a>
                        </div>
                    </div>
                </div>

                <div class="branch-food-grid-wrap">
                    <div class="branch-food-grid-header">
                        <span class="branch-food-grid-label">{{ $branch['name'] }} Menu</span>
                        <span class="branch-food-grid-count">{{ count($foodsByBranch[$branch['name']] ?? []) }} items</span>
                    </div>
                    <div class="menu-grid branch-food-grid">
                        @foreach ($foodsByBranch[$branch['name']] ?? [] as $food)
                            @include('pages.partials.food-card', ['food' => $food])
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    </div>
@endsection
