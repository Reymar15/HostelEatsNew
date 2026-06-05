@extends('layouts.app')

@section('content')
    <section class="section-head flush">
        <div>
            <h2>Categories</h2>
            <p>Browse the menu by food type.</p>
        </div>
    </section>

    <section class="category-grid">
        @foreach ($categories as $category)
            <article class="category-card" data-searchable="{{ strtolower($category['name'].' '.$category['description']) }}">
                <span>{{ strtoupper(substr($category['name'], 0, 1)) }}</span>
                <h3>{{ $category['name'] }}</h3>
                <p>{{ $category['description'] }}</p>
                <strong>{{ $category['count'] }} items</strong>
            </article>
        @endforeach
    </section>
@endsection
