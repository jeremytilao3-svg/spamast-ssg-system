@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
    <style>
        table.announcements { width: 100%; border-collapse: collapse; background: #fff; }
        table.announcements th, table.announcements td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        table.announcements th { background: #f9fafb; }
    </style>

    {{-- Only Officers see the "New" link (same gate as before) --}}
    @can('manage-ssg')
        <p><a href="{{ route('officer.announcements.create') }}">+ New Announcement</a></p>
    @endcan

    <table class="announcements">
        <thead>
            <tr>
                <th>Title</th>
                <th>Preview</th>
                <th>Posted by</th>
                <th>Date</th>
                <th>Attachment</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($announcements as $announcement)
                <tr>
                    <td><a href="{{ route('announcements.show', $announcement) }}">{{ $announcement->title }}</a></td>
                    <td>{{ Str::limit($announcement->body, 120) }}</td>
                    <td>{{ $announcement->user->full_name }}</td>
                    <td>{{ $announcement->created_at->format('M d, Y h:i A') }}</td>
                    <td>{{ $announcement->attachment ? 'Yes' : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No announcements yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Page links (only when there is more than one page) --}}
    @if ($announcements->hasPages())
        <p>
            @if (! $announcements->onFirstPage())
                <a href="{{ $announcements->previousPageUrl() }}">&larr; Previous</a>
            @endif
            Page {{ $announcements->currentPage() }} of {{ $announcements->lastPage() }}
            @if ($announcements->hasMorePages())
                <a href="{{ $announcements->nextPageUrl() }}">Next &rarr;</a>
            @endif
        </p>
    @endif
@endsection
