@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">Branch Management</p>
            <h2>Partner branch controls</h2>
            <p>Add branches, upload logos, and manage active or inactive state.</p>
        </div>
        <button type="button" class="primary-action" data-admin-modal="branch-modal">Add Branch</button>
    </section>

    @include('admin.partials.branch-table')
    @include('admin.partials.modals')
@endsection
