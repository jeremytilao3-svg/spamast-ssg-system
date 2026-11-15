@extends('layouts.app')

@section('title', 'Manage Institutes')

@section('content')
    <style>
        table.list { width: 100%; border-collapse: collapse; background: #fff; }
        table.list th, table.list td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        table.list th { background: #f9fafb; }
        .actions form { display: inline; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; align-items: center; }
        .pager { margin-top: 15px; display: flex; gap: 15px; align-items: center; }
    </style>

    {{-- Search box and the "Add Institute" link --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.institutes.index') }}" class="toolbar">
            <input type="text" name="search" placeholder="Search code or name" value="{{ request('search') }}">
            <button type="submit">Search</button>
            <a href="{{ route('admin.institutes.index') }}">Reset</a>
        </form>

        <a href="{{ route('admin.institutes.create') }}">+ Add Institute</a>
    </div>

    <table class="list">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($institutes as $institute)
                <tr>
                    <td>{{ $institute->code }}</td>
                    <td>{{ $institute->name }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.institutes.edit', $institute->id) }}">Edit</a>

                        <form method="POST" action="{{ route('admin.institutes.destroy', $institute->id) }}"
                              onsubmit="return confirm('Delete this institute?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No institutes found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Simple page links (they keep the search) --}}
    <div class="pager">
        @if ($institutes->previousPageUrl())
            <a href="{{ $institutes->previousPageUrl() }}">&larr; Previous</a>
        @endif

        <span>Page {{ $institutes->currentPage() }} of {{ $institutes->lastPage() }}</span>

        @if ($institutes->nextPageUrl())
            <a href="{{ $institutes->nextPageUrl() }}">Next &rarr;</a>
        @endif
    </div>
@endsection