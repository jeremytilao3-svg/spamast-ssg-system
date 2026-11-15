@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
    <p><a href="{{ route('officer.students.index') }}">&larr; Back to students</a></p>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="flash-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('officer.students.update', $student) }}">
        @csrf
        @method('PUT')

        <p>
            <label>First Name</label><br>
            <input type="text" name="first_name" value="{{ old('first_name', $student->user->first_name) }}" required>
        </p>
        <p>
            <label>Middle Name (optional)</label><br>
            <input type="text" name="middle_name" value="{{ old('middle_name', $student->user->middle_name) }}">
        </p>
        <p>
            <label>Last Name</label><br>
            <input type="text" name="last_name" value="{{ old('last_name', $student->user->last_name) }}" required>
        </p>
        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email', $student->user->email) }}" required>
        </p>
        <p>
            <label>Student Number</label><br>
            <input type="text" name="student_no" value="{{ old('student_no', $student->student_no) }}" required>
        </p>

        {{-- Institute only narrows the program list; it is not saved --}}
        <p>
            <label>Institute</label><br>
            <select id="institute_id">
                <option value="">All institutes</option>
                @foreach ($institutes as $institute)
                    <option value="{{ $institute->id }}" @selected($student->program->institute_id == $institute->id)>
                        {{ $institute->code }}
                    </option>
                @endforeach
            </select>
        </p>
        <p>
            <label>Program</label><br>
            <select name="program_id" id="program_id" required>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" data-institute="{{ $program->institute_id }}"
                        @selected(old('program_id', $student->program_id) == $program->id)>
                        {{ $program->code }} - {{ $program->name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Sex</label><br>
            <select name="sex" required>
                <option value="Male" @selected(old('sex', $student->sex) === 'Male')>Male</option>
                <option value="Female" @selected(old('sex', $student->sex) === 'Female')>Female</option>
            </select>
        </p>
        <p>
            <label>Year Level</label><br>
            <input type="number" name="year_level" min="1" max="6" value="{{ old('year_level', $student->year_level) }}" required>
        </p>

        <button type="submit">Save Changes</button>
    </form>

    {{-- Show only the programs of the chosen institute --}}
    <script>
        const instituteSelect = document.getElementById('institute_id');
        const programSelect = document.getElementById('program_id');

        function filterPrograms() {
            const institute = instituteSelect.value;

            Array.from(programSelect.options).forEach(function (option) {
                if (option.value === '') return;
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