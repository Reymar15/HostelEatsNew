@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">Analytics</p>
            <h2>Ordering insights</h2>
            <p>Animated Chart.js dashboards for daily orders, revenue, foods, and branches.</p>
        </div>
    </section>

    @include('admin.partials.analytics')
@endsection
