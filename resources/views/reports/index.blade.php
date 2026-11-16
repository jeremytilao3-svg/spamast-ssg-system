@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <h1>Reports</h1>

    <p>Choose an event to see its attendance report.</p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Venue</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->venue }}</td>
                    <td>{{ $event->event_date->format('M d, Y') }}</td>
                    <td><a href="{{ route('reports.show', $event) }}">View report</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No events yet.</td>
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