@extends('layouts.app')

@section('title', 'New Announcement')

@section('content')
    <p><a href="{{ route('announcements.index') }}">&larr; Back to announcements</a></p>

    {{-- enctype is needed so the browser sends the attachment file --}}
    <form method="POST" action="{{ route('officer.announcements.store') }}" enctype="multipart/form-data">
        @csrf
        @include('officer.announcements._form')

        <button type="submit">Post Announcement</button>
    </form>
@endsection
