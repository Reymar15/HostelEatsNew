@extends('layouts.app')

@section('content')
    <section class="section-head flush">
        <div>
            <h2>All Branches</h2>
            <p>Choose from the five hostel ordering partners.</p>
        </div>
    </section>

    <section class="branch-grid branch-grid-large">
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
                <div class="branch-meta">
                    <strong>{{ $branch['status'] }}</strong>
                    <span>15-35 min</span>
                </div>
            </a>
        @endforeach
    </section>

    <div class="branch-menu-stack branch-page-menu">
        @foreach ($branches as $branch)
            <section class="branch-menu-section">
                <div class="branch-banner">
                    <img src="{{ $branch['banner'] }}" alt="{{ $branch['name'] }} banner" loading="lazy">
                    <div class="branch-banner-content">
                        <span class="branch-banner-logo">
                            <img src="{{ $branch['logo'] }}" alt="{{ $branch['name'] }} logo" loading="lazy">
                        </span>
                        <div>
                            <p>{{ $branch['status'] }}</p>
                            <h2>{{ $branch['name'] }}</h2>
                            <span>{{ $branch['description'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="menu-grid branch-food-grid">
                    @foreach ($foodsByBranch[$branch['name']] ?? [] as $food)
                        @include('pages.partials.food-card', ['food' => $food])
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
@endsection
