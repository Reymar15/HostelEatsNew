@extends('layouts.app')

@section('content')
    <section class="admin-hero">
        <div>
            <p class="crumb">Admin Dashboard</p>
            <h2>HostelEats operations overview</h2>
            <p>Professional session-only control center for orders, foods, branches, users, analytics, and reports.</p>
        </div>
        <div class="hero-actions">
            <button type="button" class="secondary-action" data-admin-modal="food-modal">Add Food</button>
            <a class="primary-action" href="{{ route('admin.reports') }}">Generate Report</a>
        </div>
    </section>

    @include('admin.partials.stat-cards')

    <section class="admin-dashboard-grid">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Recent Activity</h2>
                    <p>New orders, stock alerts, and branch notices.</p>
                </div>
            </div>
            <div class="activity-list">
                @foreach ($notifications as $notice)
                    <div class="activity-item {{ $notice['tone'] }}">
                        <strong>{{ $notice['type'] }}</strong>
                        <span>{{ $notice['message'] }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="panel chart-panel">
            <div class="panel-head">
                <div>
                    <h2>Revenue Snapshot</h2>
                    <p>Animated Chart.js revenue overview.</p>
                </div>
            </div>
            <canvas data-chart="revenue" data-values='@json($analytics["revenue"])'></canvas>
        </article>
    </section>

    @include('admin.partials.order-table')
    @include('admin.partials.food-table')
    @include('admin.partials.modals')
@endsection
