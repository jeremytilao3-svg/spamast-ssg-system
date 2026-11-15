@extends('layouts.app')

@section('title', 'Add Student')

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

    <form method="POST" action="{{ route('officer.students.store') }}">
        @csrf

        <p>
            <label>First Name</label><br>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required>
        </p>
        <p>
            <label>Middle Name (optional)</label><br>
            <input type="text" name="middle_name" value="{{ old('middle_name') }}">
        </p>
        <p>
            <label>Last Name</label><br>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required>
        </p>
        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </p>
        <p>
            <label>Student Number (also the first password)</label><br>
            <input type="text" name="student_no" value="{{ old('student_no') }}" required>
        </p>

        {{-- Institute only narrows the program list; it is not saved --}}
        <p>
            <label>Institute</label><br>
            <select id="institute_id">
                <option value="">All institutes</option>
                @foreach ($institutes as $institute)
                    <option value="{{ $institute->id }}">{{ $institute->code }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <label>Program</label><br>
            <select name="program_id" id="program_id" required>
                <option value="">Choose a program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" data-institute="{{ $program->institute_id }}"
                        @selected(old('program_id') == $program->id)>
                        {{ $program->code }} - {{ $program->name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Sex</label><br>
            <select name="sex" required>
                <option value="">Choose</option>
                <option value="Male" @selected(old('sex') === 'Male')>Male</option>
                <option value="Female" @selected(old('sex') === 'Female')>Female</option>
            </select>
        </p>
        <p>
            <label>Year Level</label><br>
            <input type="number" name="year_level" min="1" max="6" value="{{ old('year_level', 1) }}" required>
        </p>

        <button type="submit">Create Student</button>
    </form>

    {{-- Show only the programs of the chosen institute --}}
    <script>
        const instituteSelect = document.getElementById('institute_id');
        const programSelect = document.getElementById('program_id');

        function filterPrograms() {
            const institute = instituteSelect.value;

            Array.from(programSelect.options).forEach(function (option) {
                if (option.value === '') return;                 // keep the first empty option
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