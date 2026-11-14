@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Placeholder numbers: real data comes in later phases --}}
    <div class="cards">
        <div class="card">
            <div>Total Users</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>Pending Approvals</div>
            <div class="number">0</div>
        </div>
        <div class="card">
            <div>Active Events</div>
            <div class="number">0</div>
        </div>
    </div>
@endsection