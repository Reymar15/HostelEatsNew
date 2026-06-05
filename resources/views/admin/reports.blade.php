@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">Reports</p>
            <h2>Sales and revenue exports</h2>
            <p>Generate fake PDF-style reports for daily sales, orders, and revenue.</p>
        </div>
        <button type="button" class="primary-action" data-fake-download>Download PDF Report</button>
    </section>

    @include('admin.partials.reports')
@endsection
