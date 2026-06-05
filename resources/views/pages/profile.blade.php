@extends('layouts.app')

@section('content')
    @php
        $authName    = session('auth_name') ?? ($profile['name'] ?? (Auth::check() ? Auth::user()->name : 'Guest'));
        $nameParts   = array_filter(explode(' ', trim($authName)));
        $initials    = strtoupper(substr($nameParts[0] ?? 'G', 0, 1));
        if (count($nameParts) > 1) {
            $initials .= strtoupper(substr(end($nameParts), 0, 1));
        }
        $displayBlock = session('auth_hostel_block') ?? ($profile['hostel_block'] ?? '');
        $displayEmail = session('auth_email') ?? ($profile['email'] ?? (Auth::check() ? Auth::user()->email : ''));
        $studentId    = session('auth_student_id') ?? ($profile['student_id'] ?? '');
    @endphp

    <section class="profile-card">
        <span class="profile-avatar">{{ $initials }}</span>
        <div>
            <p class="crumb">Student Profile</p>
            <h2>{{ $authName }}</h2>
            <p>{{ $displayEmail }}</p>
        </div>
        <dl>
            <div><dt>Name</dt><dd>{{ $authName }}</dd></div>
            <div><dt>Email</dt><dd>{{ $displayEmail }}</dd></div>
            <div><dt>Student ID</dt><dd>{{ $studentId }}</dd></div>
            <div><dt>Hostel Block</dt><dd>{{ $displayBlock }}</dd></div>
        </dl>
    </section>
@endsection
