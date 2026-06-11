@extends('layouts.app')

@section('content')
    @php
        $authName    = session('auth_name') ?? ($profile['name'] ?? (Auth::check() ? Auth::user()->name : 'Guest'));
        $nameParts   = array_filter(explode(' ', trim($authName)));
        $initials    = strtoupper(substr($nameParts[0] ?? 'G', 0, 1));
        if (count($nameParts) > 1) {
            $initials .= strtoupper(substr(end($nameParts), 0, 1));
        }
        $displayEmail  = session('auth_email')        ?? ($profile['email']        ?? (Auth::check() ? Auth::user()->email : ''));
        $studentId     = session('auth_student_id')   ?? ($profile['student_id']   ?? '');
        $hostelBlock   = session('auth_hostel_block') ?? ($profile['hostel_block'] ?? '—');
        $roomNumber    = session('auth_room_number')  ?? ($profile['room_number']  ?? '—');
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
            <div><dt>Hostel Block</dt><dd>{{ $hostelBlock }}</dd></div>
            <div><dt>Room Number</dt><dd>{{ $roomNumber }}</dd></div>
        </dl>
    </section>
@endsection
