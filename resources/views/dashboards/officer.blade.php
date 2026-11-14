@extends('layouts.app')

@section('title', 'Officer Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Placeholder numbers: real data comes in later phases --}}
    <div class="cards">
        <div class="card">
            <div>Upcoming Events</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>Today's Attendance</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>Unpaid Fines</div>
            <div class="number">0</div>
        </div>
    </div>
@endsection