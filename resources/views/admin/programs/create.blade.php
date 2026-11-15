@extends('layouts.app')

@section('title', 'Add Program')

@section('content')
    <p><a href="{{ route('admin.programs.index') }}">&larr; Back to programs</a></p>

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

    <form method="POST" action="{{ route('admin.programs.store') }}">
        @csrf

        <p>
            <label>Institute</label><br>
            <select name="institute_id" required>
                <option value="">Choose an institute</option>
                @foreach ($institutes as $institute)
                    <option value="{{ $institute->id }}" @selected(old('institute_id') == $institute->id)>
                        {{ $institute->code }} - {{ $institute->name }}
                    </option>
                @endforeach
            </select>
        </p>
        <p>
            <label>Code (for example BSIT)</label><br>
            <input type="text" name="code" value="{{ old('code') }}" required>
        </p>
        <p>
            <label>Name</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required size="50">
        </p>

        <button type="submit">Create Program</button>
    </form>
@endsection