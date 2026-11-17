@extends('layouts.app')

@section('title', 'Adviser Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Real numbers come from DashboardController (cached for 10 minutes) --}}
    <div class="cards">
        <div class="card">
            <div>Upcoming Events</div>
            <div class="number">{{ $stats['upcoming_events'] }}</div>
        </div>
        <div class="card">
            <div>Attendance Records</div>
            <div class="number">{{ $stats['attendance_records'] }}</div>
        </div>
        <div class="card">
            <div>Unpaid Fines</div>
            <div class="number">{{ $stats['unpaid_fines'] }}</div>
        </div>
    </div>
@endsection
