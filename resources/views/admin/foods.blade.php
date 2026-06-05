@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">Food Management</p>
            <h2>Menu operations</h2>
            <p>Search, filter, edit, delete, preview images, and toggle food availability.</p>
        </div>
        <button type="button" class="primary-action" data-admin-modal="food-modal">Add Food</button>
    </section>

    @include('admin.partials.food-table')
    @include('admin.partials.modals')
@endsection
