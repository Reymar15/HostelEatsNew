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
        <a class="secondary-action" href="{{ route('branches.index') }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Branches
        </a>
        <a class="primary-action" href="{{ route('menu.index') }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1" ry="1"/></svg>
            View All Menu
        </a>
    </div>
@endsection
