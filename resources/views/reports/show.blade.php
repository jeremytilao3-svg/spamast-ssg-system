@extends('layouts.app')

@section('title', 'Report: ' . $event->title)

@section('content')
    {{-- Print CSS: hide the sidebar and the buttons when printing --}}
    <style>
        @media print {
            .sidebar, .no-print { display: none !important; }
        }
    </style>

    <h1>Report: {{ $event->title }}</h1>

    <p>
        {{ $event->venue }} &middot; {{ $event->event_date->format('F d, Y') }}
        &middot; {{ Str::headline($event->day_type) }}
    </p>

    <p class="no-print">
        <a href="{{ route('reports.index') }}">&laquo; Back to reports</a>
        <button type="button" onclick="window.print()">Print</button>
    </p>

    <h2>Attendance by institute and program</h2>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>Institute</th>
                <th>Program</th>
                <th>Total</th>
                <th>Male</th>
                <th>Female</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($summary as $row)
                <tr>
                    <td>{{ $row->institute_code }}</td>
                    <td>{{ $row->program_code }}</td>
                    <td>{{ $row->total }}</td>
                    <td>{{ $row->male }}</td>
                    <td>{{ $row->female }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No check-ins yet.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Grand total</th>
                <th>{{ $grand['total'] }}</th>
                <th>{{ $grand['male'] }}</th>
                <th>{{ $grand['female'] }}</th>
            </tr>
        </tfoot>
    </table>

    <h2>Students</h2>
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
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $row)
                <tr>
                    <td>{{ $row->student_no }}</td>
                    <td>{{ $row->last_name }}, {{ $row->first_name }}</td>
                    <td>{{ $row->program_code }}</td>
                    <td>{{ $row->institute_code }}</td>
                    <td>{{ Str::headline($row->period) }}</td>
                    <td>{{ $row->check_in_at?->format('h:i A') ?? '-' }}</td>
                    <td>{{ $row->check_out_at?->format('h:i A') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No students checked in.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection