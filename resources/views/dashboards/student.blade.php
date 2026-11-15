@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Placeholder numbers: real data comes in later phases --}}
    <div class="cards">
        <div class="card">
            <div>Events Attended</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>Upcoming Events</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>My Unpaid Fines</div>
            <div class="number">0</div>
        </div>
    </div>
@endsection