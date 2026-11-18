@extends('layouts.app')

@section('title', 'Edit Announcement')

@section('content')
    <p><a href="{{ route('announcements.show', $announcement) }}">&larr; Back to announcement</a></p>

    {{-- HTML forms only send GET or POST, so @method('PUT') tells Laravel it is an update --}}
    <form method="POST" action="{{ route('officer.announcements.update', $announcement) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('officer.announcements._form')

        <button type="submit">Save Changes</button>
    </form>
@endsection
