@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">Order Management</p>
            <h2>Track and update orders</h2>
            <p>Search by order ID, view details, sort by date, and update delivery status.</p>
        </div>
    </section>

    @include('admin.partials.order-table')
    @include('admin.partials.modals')
@endsection
