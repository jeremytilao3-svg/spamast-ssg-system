@extends('layouts.app')

@section('title', 'Manage Programs')

@section('content')
    <style>
        table.list { width: 100%; border-collapse: collapse; background: #fff; }
        table.list th, table.list td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        table.list th { background: #f9fafb; }
        .actions form { display: inline; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; align-items: center; }
        .pager { margin-top: 15px; display: flex; gap: 15px; align-items: center; }
    </style>

    {{-- Search box and the "Add Program" link --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.programs.index') }}" class="toolbar">
            <input type="text" name="search" placeholder="Search code, name or institute" value="{{ request('search') }}">
            <button type="submit">Search</button>
            <a href="{{ route('admin.programs.index') }}">Reset</a>
        </form>

        <a href="{{ route('admin.programs.create') }}">+ Add Program</a>
    </div>

    <table class="list">
        <thead>
            <tr>
                <th>Institute</th>
                <th>Code</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($programs as $program)
                <tr>
                    {{-- institute_code comes from the join in ProgramController --}}
                    <td>{{ $program->institute_code }}</td>
                    <td>{{ $program->code }}</td>
                    <td>{{ $program->name }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.programs.edit', $program->id) }}">Edit</a>

                        <form method="POST" action="{{ route('admin.programs.destroy', $program->id) }}"
                              onsubmit="return confirm('Delete this program?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No programs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Simple page links (they keep the search) --}}
    <div class="pager">
        @if ($programs->previousPageUrl())
            <a href="{{ $programs->previousPageUrl() }}">&larr; Previous</a>
        @endif

        <span>Page {{ $programs->currentPage() }} of {{ $programs->lastPage() }}</span>

        @if ($programs->nextPageUrl())
            <a href="{{ $programs->nextPageUrl() }}">Next &rarr;</a>
        @endif
    </div>
@endsection