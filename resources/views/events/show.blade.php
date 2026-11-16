@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <h1>{{ $event->title }}</h1>

    <p><a href="{{ route('events.index') }}">&laquo; Back to events</a></p>

    <div class="card">
        <p><strong>Venue:</strong> {{ $event->venue }}</p>
        <p><strong>Date:</strong> {{ $event->event_date->format('F d, Y') }}</p>
        <p><strong>Day type:</strong> {{ $event->day_type === 'whole_day' ? 'Whole day (4 scans)' : 'Half day (2 scans)' }}</p>
        <p><strong>Availability:</strong> {{ ucfirst($event->availability) }}</p>
        <p><strong>Fine per miss:</strong> ₱{{ number_format($event->fine_per_miss, 2) }}</p>
        <p><strong>Created by:</strong> {{ $event->creator?->first_name }} {{ $event->creator?->last_name }}</p>
        <p><strong>Description:</strong> {{ $event->description ?: 'No description.' }}</p>
    </div>

    <h2>Sessions</h2>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>Session</th>
                <th>Check-in</th>
                <th>Check-out</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($event->sessions as $session)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $session->period)) }}</td>

                    @if ($event->availability === 'scheduled')
                        {{-- Scheduled: the clock decides --}}
                        <td>
                            {{ substr($session->check_in_start, 0, 5) }} - {{ substr($session->check_in_end, 0, 5) }}
                            ({{ $session->isCheckInOpen() ? 'Open now' : 'Closed now' }})
                        </td>
                        <td>
                            {{ substr($session->check_out_start, 0, 5) }} - {{ substr($session->check_out_end, 0, 5) }}
                            ({{ $session->isCheckOutOpen() ? 'Open now' : 'Closed now' }})
                        </td>
                    @else
                        {{-- Manual: the Officer opens and closes by hand --}}
                        @foreach (['check_in' => 'Check-in', 'check_out' => 'Check-out'] as $type => $label)
                            <td>
                                {{ $session->{$type . '_open'} ? 'Open' : 'Closed' }}

                                @can('update', $event)
                                    <form method="POST" action="{{ route('officer.sessions.toggle', $session) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <button type="submit">{{ $session->{$type . '_open'} ? 'Close' : 'Open' }}</button>
                                    </form>
                                @endcan
                            </td>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Students: scan in and out --}}
    @can('use-attendance')
        <h2>My Attendance</h2>

        @php $student = auth()->user()->student; @endphp

        @if (! $student)
            <p>Your account has no linked student record, so you cannot scan. Please contact the SSG Officer.</p>
        @else
            <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>My check-in</th>
                        <th>My check-out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($event->sessions as $session)
                        @php $mine = $session->attendances()->where('student_id', $student->id)->first(); @endphp
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $session->period)) }}</td>

                            <td>
                                @if ($mine)
                                    {{ $mine->check_in_at->format('h:i A') }}
                                @elseif ($session->isCheckInOpen())
                                    <form method="POST" action="{{ route('attendance.check-in', $session) }}">
                                        @csrf
                                        <button type="submit">Check in</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if ($mine && $mine->check_out_at)
                                    {{ $mine->check_out_at->format('h:i A') }}
                                @elseif ($mine && $session->isCheckOutOpen())
                                    <form method="POST" action="{{ route('attendance.check-out', $session) }}">
                                        @csrf
                                        <button type="submit">Check out</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endcan

    {{-- Officers and Advisers: attendance, fines and report --}}
    @can('view-reports')
        <p style="margin-top: 15px;">
            <a href="{{ route('attendance.index', $event) }}">Attendance</a> |
            <a href="{{ route('penalties.index', $event) }}">Fines</a> |
            <a href="{{ route('reports.show', $event) }}">Report</a>
        </p>
    @endcan

    {{-- Edit and Delete: the policy decides who sees them --}}
    <p style="margin-top: 15px;">
        @can('update', $event)
            <a href="{{ route('officer.events.edit', $event) }}">Edit</a>
        @endcan

        @can('delete', $event)
            <form method="POST" action="{{ route('officer.events.destroy', $event) }}" style="display: inline;"
                  onsubmit="return confirm('Delete this event?');">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        @endcan
    </p>
@endsection