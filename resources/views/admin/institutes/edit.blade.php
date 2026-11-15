@extends('layouts.app')

@section('title', 'Edit Institute')

@section('content')
    <p><a href="{{ route('admin.institutes.index') }}">&larr; Back to institutes</a></p>

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

    <form method="POST" action="{{ route('admin.institutes.update', $institute->id) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Code</label><br>
            <input type="text" name="code" value="{{ old('code', $institute->code) }}" required>
        </p>
        <p>
            <label>Name</label><br>
            <input type="text" name="name" value="{{ old('name', $institute->name) }}" required size="50">
        </p>

        <button type="submit">Save Changes</button>
    </form>
@endsection