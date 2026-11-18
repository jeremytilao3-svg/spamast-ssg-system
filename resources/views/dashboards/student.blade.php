@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Real numbers come from DashboardController (cached 10 minutes, one key per student) --}}
    <div class="cards">
        <div class="card">
            <div>My Attendance Records</div>
            <div class="number">{{ $stats['my_attendance'] }}</div>
        </div>
        <div class="card">
            <div>Upcoming Events</div>
            <div class="number">{{ $stats['upcoming_events'] }}</div>
        </div>
    </div>
    @include('dashboards._latest-announcements')
@endsection
