@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
    <style>
        table.list { width: 100%; border-collapse: collapse; background: #fff; }
        table.list th, table.list td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        table.list th { background: #f9fafb; }
        .actions form { display: inline; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; align-items: center; }
        .pager { margin-top: 15px; display: flex; gap: 15px; align-items: center; }
    </style>

    {{-- Search, filters and the "Add Student" link --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('officer.students.index') }}" class="toolbar">
            <input type="text" name="search" placeholder="Search name or student no." value="{{ request('search') }}">

            <select name="institute_id" id="institute_id">
                <option value="">All institutes</option>
                @foreach ($institutes as $institute)
                    <option value="{{ $institute->id }}" @selected(request('institute_id') == $institute->id)>
                        {{ $institute->code }}
                    </option>
                @endforeach
            </select>

            <select name="program_id" id="program_id">
                <option value="">All programs</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" data-institute="{{ $program->institute_id }}"
                        @selected(request('program_id') == $program->id)>
                        {{ $program->code }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Filter</button>
            <a href="{{ route('officer.students.index') }}">Reset</a>
        </form>

        <a href="{{ route('officer.students.create') }}">+ Add Student</a>
    </div>

    <table class="list">
        <thead>
            <tr>
                <th>Student No.</th>
                <th>Name</th>
                <th>Institute</th>
                <th>Program</th>
                <th>Sex</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <td>{{ $student->student_no }}</td>
                    <td>{{ $student->user->first_name }} {{ $student->user->last_name }}</td>
                    <td>{{ $student->program->institute->code }}</td>
                    <td>{{ $student->program->code }}</td>
                    <td>{{ $student->sex }}</td>
                    <td>{{ $student->year_level }}</td>
                    <td class="actions">
                        <a href="{{ route('officer.students.edit', $student) }}">Edit</a>

                        <form method="POST" action="{{ route('officer.students.destroy', $student) }}"
                              onsubmit="return confirm('Delete this student and their login account?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Simple page links (they keep the search and filters) --}}
    <div class="pager">
        @if ($students->previousPageUrl())
            <a href="{{ $students->previousPageUrl() }}">&larr; Previous</a>
        @endif

        <span>Page {{ $students->currentPage() }} of {{ $students->lastPage() }}</span>

        @if ($students->nextPageUrl())
            <a href="{{ $students->nextPageUrl() }}">Next &rarr;</a>
        @endif
    </div>

    {{-- Show only the programs of the chosen institute --}}
    <script>
        const instituteSelect = document.getElementById('institute_id');
        const programSelect = document.getElementById('program_id');

        function filterPrograms() {
            const institute = instituteSelect.value;

            Array.from(programSelect.options).forEach(function (option) {
                if (option.value === '') return;                 // keep "All programs"
                const show = institute === '' || option.dataset.institute === institute;
                option.hidden = !show;
                option.disabled = !show;
            });

            // If the chosen program is now hidden, clear the choice
            const chosen = programSelect.options[programSelect.selectedIndex];
            if (chosen && chosen.value !== '' && chosen.disabled) {
                programSelect.value = '';
            }
        }

        instituteSelect.addEventListener('change', filterPrograms);
        filterPrograms();
    </script>
@endsection