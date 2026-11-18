@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <h1>Events</h1>

    {{-- Tabs: Upcoming and Past --}}
    <p>
        <a href="{{ route('events.index', ['tab' => 'upcoming']) }}"
           style="{{ $tab === 'upcoming' ? 'font-weight: bold; text-decoration: underline;' : '' }}">Upcoming</a>
        |
        <a href="{{ route('events.index', ['tab' => 'past']) }}"
           style="{{ $tab === 'past' ? 'font-weight: bold; text-decoration: underline;' : '' }}">Past</a>
    </p>

    {{-- Search by title/venue and by date (stays on the same tab) --}}
    <form method="GET" action="{{ route('events.index') }}" style="margin-bottom: 15px;">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or venue">
        <input type="date" name="date" value="{{ request('date') }}">
        <button type="submit">Search</button>
        <a href="{{ route('events.index', ['tab' => $tab]) }}">Clear</a>
    </form>

    {{-- Only Officers see the create button --}}
    @can('manage-ssg')
        <p><a href="{{ route('officer.events.create') }}">+ New Event</a></p>
    @endcan

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Venue</th>
                <th>Date</th>
                <th>Day type</th>
                <th>Availability</th>
                <th>Fine per miss</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->venue }}</td>
                    <td>{{ $event->event_date->format('M d, Y') }}</td>
                    <td>{{ Str::headline($event->day_type) }}</td>
                    <td>{{ Str::headline($event->availability) }}</td>
                    <td>₱{{ number_format($event->fine_per_miss, 2) }}</td>
                    <td><a href="{{ route('events.show', $event) }}">View</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No {{ $tab }} events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Previous / Next links --}}
    <p style="margin-top: 15px;">
        @if ($events->previousPageUrl())
            <a href="{{ $events->previousPageUrl() }}">&laquo; Previous</a>
        @endif

        Page {{ $events->currentPage() }} of {{ $events->lastPage() }}

        @if ($events->nextPageUrl())
            <a href="{{ $events->nextPageUrl() }}">Next &raquo;</a>
        @endif
    </p>
@endsection