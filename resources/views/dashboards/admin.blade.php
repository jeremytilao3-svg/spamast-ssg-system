@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <p>Welcome, {{ auth()->user()->first_name }}!</p>

    {{-- Real numbers come from DashboardController (cached for 10 minutes) --}}
    <div class="cards">
        {{-- One card per role: Admin, Officer, Adviser, Student --}}
        @foreach (['Admin', 'Officer', 'Adviser', 'Student'] as $role)
            <div class="card">
                <div>{{ $role }}s</div>
                <div class="number">{{ $stats['users_per_role'][$role] ?? 0 }}</div>
            </div>
        @endforeach

        <div class="card">
            <div>Pending Approvals</div>
            <div class="number">{{ $stats['pending'] }}</div>
        </div>
        <div class="card">
            <div>Institutes</div>
            <div class="number">{{ $stats['institutes'] }}</div>
        </div>
        <div class="card">
            <div>Programs</div>
            <div class="number">{{ $stats['programs'] }}</div>
        </div>
    </div>
@endsection
