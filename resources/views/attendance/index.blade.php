@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
    <h1>Attendance: {{ $event->title }}</h1>

    <p><a href="{{ route('events.show', $event) }}">&laquo; Back to event</a></p>

    {{-- Filters --}}
    <form method="GET" action="{{ route('attendance.index', $event) }}" style="margin-bottom: 15px;">
        <select name="session_id">
            <option value="">All sessions</option>
            @foreach ($event->sessions as $session)
                <option value="{{ $session->id }}" @selected(request('session_id') == $session->id)>
                    {{ Str::headline($session->period) }}
                </option>
            @endforeach
        </select>

        <select name="institute_id">
            <option value="">All institutes</option>
            @foreach ($institutes as $institute)
                <option value="{{ $institute->id }}" @selected($instituteId == $institute->id)>
                    {{ $institute->code }}
                </option>
            @endforeach
        </select>

        <select name="program_id">
            <option value="">All programs</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" @selected($programId == $program->id)>
                    {{ $program->code }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('attendance.index', [$event, 'clear' => 1]) }}">Clear Filters</a>
    </form>

    {{-- Officers can add or fix a scan --}}
    @can('manage-ssg')
        <form method="POST" action="{{ route('attendance.store', $event) }}" style="margin-bottom: 15px;">
            @csrf
            <strong>Add / fix a scan:</strong>

            <select name="student_id" required>
                <option value="">Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->student_no }} - {{ $student->user->last_name }}, {{ $student->user->first_name }}</option>
                @endforeach
            </select>

            <select name="event_session_id" required>
                <option value="">Session</option>
                @foreach ($event->sessions as $session)
                    <option value="{{ $session->id }}">{{ Str::headline($session->period) }}</option>
                @endforeach
            </select>

            In <input type="datetime-local" name="check_in_at">
            Out <input type="datetime-local" name="check_out_at">

            <button type="submit">Save</button>
        </form>
    @endcan

    @if ($errors->any())
        <div class="flash-error">
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>Student no.</th>
                <th>Name</th>
                <th>Program</th>
                <th>Institute</th>
                <th>Session</th>
                <th>Check-in</th>
                <th>Check-out</th>
                @can('manage-ssg')
                    <th>Action</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->student->student_no }}</td>
                    <td>{{ $attendance->student->user->last_name }}, {{ $attendance->student->user->first_name }}</td>
                    <td>{{ $attendance->student->program->code }}</td>
                    <td>{{ $attendance->student->program->institute->code }}</td>
                    <td>{{ Str::headline($attendance->session->period) }}</td>

                    @can('manage-ssg')
                        {{-- Officer: edit the times in the row --}}
                        <td colspan="2">
                            <form method="POST" action="{{ route('attendance.update', $attendance) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="datetime-local" name="check_in_at" value="{{ $attendance->check_in_at?->format('Y-m-d\TH:i') }}">
                                <input type="datetime-local" name="check_out_at" value="{{ $attendance->check_out_at?->format('Y-m-d\TH:i') }}">
                                <button type="submit">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('attendance.destroy', $attendance) }}" style="display: inline;"
                                  onsubmit="return confirm('Delete this attendance row?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    @else
                        {{-- Adviser: view only --}}
                        <td>{{ $attendance->check_in_at?->format('h:i A') ?? '-' }}</td>
                        <td>{{ $attendance->check_out_at?->format('h:i A') ?? '-' }}</td>
                    @endcan
                </tr>
            @empty
                <tr>
                    <td colspan="8">No attendance found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Previous / Next links --}}
    <p style="margin-top: 15px;">
        @if ($attendances->previousPageUrl())
            <a href="{{ $attendances->previousPageUrl() }}">&laquo; Previous</a>
        @endif

        Page {{ $attendances->currentPage() }} of {{ $attendances->lastPage() }}

        @if ($attendances->nextPageUrl())
            <a href="{{ $attendances->nextPageUrl() }}">Next &raquo;</a>
        @endif
    </p>
@endsection