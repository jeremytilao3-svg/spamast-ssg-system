@extends('layouts.app')

@section('title', 'Officer Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Numbers come from DashboardController (cached for 10 minutes) --}}
    <div class="cards">
        <div class="card">
            <div>Students</div>
            <div class="number">{{ $stats['students'] }}</div>
        </div>
        <div class="card">
            <div>Upcoming Events</div>
            <div class="number">{{ $stats['upcoming_events'] }}</div>
        </div>
        <div class="card">
            <div>Unpaid Fines</div>
            <div class="number">{{ $stats['unpaid_fines'] }}</div>
        </div>
        <div class="card">
            <div>Announcements</div>
            <div class="number">{{ $stats['announcements'] }}</div>
        </div>
    </div>
    @include('dashboards._latest-announcements')
    @include('partials.weather')
@endsection