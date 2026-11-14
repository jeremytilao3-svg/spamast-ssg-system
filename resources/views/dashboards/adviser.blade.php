@extends('layouts.app')

@section('title', 'Adviser Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Placeholder numbers: real data comes in later phases --}}
    <div class="cards">
        <div class="card">
            <div>Events This Month</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>Average Attendance</div>
            <div class="number">0%</div>
        </div>
        <div class="card">
            <div>Reports Available</div>
            <div class="number">0</div>
        </div>
    </div>
@endsection