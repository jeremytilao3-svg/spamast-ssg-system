@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <style>
        table.users { width: 100%; border-collapse: collapse; background: #fff; }
        table.users th, table.users td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        table.users th { background: #f9fafb; }
        .badge-on { color: #166534; font-weight: bold; }
        .badge-off { color: #991b1b; font-weight: bold; }
        .actions form { display: inline; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; align-items: center; }
        .pager { margin-top: 15px; display: flex; gap: 15px; align-items: center; }
    </style>

    {{-- Search, role filter and the "Add User" button --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.users.index') }}" class="toolbar">
            <input type="text" name="search" placeholder="Search name or email" value="{{ request('search') }}">

            <select name="role_id">
                <option value="">All roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>

            <button type="submit">Filter</button>
            <a href="{{ route('admin.users.index') }}">Reset</a>
        </form>

        <a href="{{ route('admin.users.create') }}">+ Add User</a>
    </div>

    <table class="users">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $u)
                <tr>
                    <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role->name }}</td>
                    <td>
                        @if ($u->is_active)
                            <span class="badge-on">Active</span>
                        @else
                            <span class="badge-off">Inactive</span>
                        @endif
                    </td>
                    <td class="actions">
                        {{-- Admin accounts cannot be edited or switched off here --}}
                        @unless ($u->hasRole('Admin'))
                            <a href="{{ route('admin.users.edit', $u) }}">Edit</a>

                            <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit">{{ $u->is_active ? 'Deactivate' : 'Approve' }}</button>
                            </form>
                        @endunless

                        {{-- Delete button only if UserPolicy allows it --}}
                        @can('delete', $u)
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                  onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Simple page links (they keep the search and filter) --}}
    <div class="pager">
        @if ($users->previousPageUrl())
            <a href="{{ $users->previousPageUrl() }}">&larr; Previous</a>
        @endif

        <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>

        @if ($users->nextPageUrl())
            <a href="{{ $users->nextPageUrl() }}">Next &rarr;</a>
        @endif
    </div>
@endsection