@extends('layouts.app')

@section('title', 'Fines')

@section('content')
    <h1>Fines: {{ $event->title }}</h1>

    <p><a href="{{ route('events.show', $event) }}">&laquo; Back to event</a></p>

    {{-- Filters --}}
    <form method="GET" action="{{ route('penalties.index', $event) }}" style="margin-bottom: 15px;">
        <select name="institute_id">
            <option value="">All institutes</option>
            @foreach ($institutes as $institute)
                <option value="{{ $institute->id }}" @selected(request('institute_id') == $institute->id)>
                    {{ $institute->code }}
                </option>
            @endforeach
        </select>

        <select name="program_id">
            <option value="">All programs</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" @selected(request('program_id') == $program->id)>
                    {{ $program->code }}
                </option>
            @endforeach
        </select>

        <select name="status">
            <option value="">All statuses</option>
            @foreach (['unpaid', 'paid', 'served'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('penalties.index', $event) }}">Clear Filters</a>
    </form>

    {{-- Only Officers can generate the fines --}}
    @can('manage-ssg')
        <form method="POST" action="{{ route('penalties.generate', $event) }}" style="margin-bottom: 15px;">
            @csrf
            <button type="submit">Generate / Refresh Fines</button>
            (Fine per missed scan: ₱{{ number_format($event->fine_per_miss, 2) }})
        </form>
    @endcan

    <p><strong>Total of these fines: ₱{{ number_format($total, 2) }}</strong></p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>Student no.</th>
                <th>Name</th>
                <th>Program</th>
                <th>Institute</th>
                <th>Missed scans</th>
                <th>Amount</th>
                <th>Sanction</th>
                <th>Status</th>
                @can('manage-ssg')
                    <th>Action</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse ($penalties as $penalty)
                <tr>
                    <td>{{ $penalty->student->student_no }}</td>
                    <td>{{ $penalty->student->user->last_name }}, {{ $penalty->student->user->first_name }}</td>
                    <td>{{ $penalty->student->program->code }}</td>
                    <td>{{ $penalty->student->program->institute->code }}</td>
                    <td>{{ $penalty->missed_scans }}</td>
                    <td>₱{{ number_format($penalty->amount, 2) }}</td>

                    @can('manage-ssg')
                        {{-- Officer: edit the sanction and the status --}}
                        <td colspan="2">
                            <form method="POST" action="{{ route('penalties.update', $penalty) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="sanction" value="{{ $penalty->sanction }}" placeholder="Sanction">
                                <select name="status">
                                    @foreach (['unpaid', 'paid', 'served'] as $status)
                                        <option value="{{ $status }}" @selected($penalty->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('penalties.destroy', $penalty) }}" style="display: inline;"
                                  onsubmit="return confirm('Delete this fine?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    @else
                        {{-- Adviser: view only --}}
                        <td>{{ $penalty->sanction ?: '-' }}</td>
                        <td>{{ ucfirst($penalty->status) }}</td>
                    @endcan
                </tr>
            @empty
                <tr>
                    <td colspan="9">No fines found. An Officer can press "Generate / Refresh Fines".</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Previous / Next links --}}
    <p style="margin-top: 15px;">
        @if ($penalties->previousPageUrl())
            <a href="{{ $penalties->previousPageUrl() }}">&laquo; Previous</a>
        @endif

        Page {{ $penalties->currentPage() }} of {{ $penalties->lastPage() }}

        @if ($penalties->nextPageUrl())
            <a href="{{ $penalties->nextPageUrl() }}">Next &raquo;</a>
        @endif
    </p>
@endsection