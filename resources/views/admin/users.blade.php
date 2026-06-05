@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">User Management</p>
            <h2>Student account overview</h2>
            <p>Review hostel users, account state, and quick disable actions.</p>
        </div>
    </section>

    @include('admin.partials.user-table')
@endsection
