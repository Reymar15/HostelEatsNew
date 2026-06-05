@extends('layouts.app')

@section('content')
    <section class="branch-menu-section branch-detail-hero {{ $selectedBranch['accent'] ?? '' }}">
        <div class="branch-banner">
            <img src="{{ $selectedBranch['banner'] }}" alt="{{ $selectedBranch['name'] }} banner" loading="lazy">
            <div class="branch-banner-content">
                <span class="branch-banner-logo">
                    <img src="{{ $selectedBranch['logo'] }}" alt="{{ $selectedBranch['name'] }} logo" loading="lazy">
                </span>
                <div>
                    <p>{{ $selectedBranch['status'] }}</p>
                    <h2>{{ $selectedBranch['name'] }}</h2>
                    <span>{{ $selectedBranch['description'] }}</span>
                    <div class="branch-quick-stats" aria-label="{{ $selectedBranch['name'] }} quick stats">
                        <strong>{{ $selectedFoods->count() }} foods</strong>
                        <strong>{{ $selectedCategories->count() }} categories</strong>
                        <strong>15-35 min</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-head">
        <div>
            <h2>{{ $selectedBranch['name'] }} Menu</h2>
            <p>{{ $selectedBranch['description'] }}. Tap a card to stay on this branch page or use cart actions directly.</p>
        </div>
        <a href="{{ route('menu.index') }}">All menu</a>
    </section>

    <section class="filter-strip">
        @foreach (collect(['All'])->merge($selectedCategories) as $filter)
            <button type="button" data-category-filter="{{ $filter }}" class="{{ $loop->first ? 'active' : '' }}">{{ $filter }}</button>
        @endforeach
    </section>

    <section class="branch-menu-section">
        <div class="branch-category-strip" aria-label="{{ $selectedBranch['name'] }} food categories">
            @foreach ($selectedCategories as $category)
                <span>{{ $category }}</span>
            @endforeach
        </div>

        <div class="menu-grid branch-food-grid">
            @forelse ($selectedFoods as $food)
                @include('pages.partials.food-card', ['food' => $food])
            @empty
                <p class="empty-cart-message">No foods yet for this branch.</p>
            @endforelse
        </div>
    </section>

    <div class="branch-detail-actions">
        <a class="secondary-action" href="{{ route('branches.index') }}">Back to Branches</a>
        <a class="primary-action" href="{{ route('menu.index') }}">View All Menu</a>
    </div>
@endsection
