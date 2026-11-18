@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
    <p><a href="{{ route('announcements.index') }}">&larr; Back to announcements</a></p>

    <h2>{{ $announcement->title }}</h2>
    <p>Posted by {{ $announcement->user->full_name }}
        on {{ $announcement->created_at->format('M d, Y h:i A') }}</p>

    {{-- e() makes the text safe, nl2br() keeps the line breaks --}}
    <div>{!! nl2br(e($announcement->body)) !!}</div>

    @if ($announcement->attachment)
        <p>Attachment: <a href="{{ asset('storage/'.$announcement->attachment) }}" target="_blank">Open file</a></p>
    @endif

    {{-- Only Officers see Edit and Delete --}}
    @can('manage-ssg')
        <div>
            <a href="{{ route('officer.announcements.edit', $announcement) }}">Edit</a>
            <form method="POST" action="{{ route('officer.announcements.destroy', $announcement) }}"
                  onsubmit="return confirm('Delete this announcement?');" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    @endcan
@endsection
