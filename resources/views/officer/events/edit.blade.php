@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <h1>Edit Event</h1>

    <p><a href="{{ route('events.show', $event) }}">&laquo; Back to event</a></p>

    <form method="POST" action="{{ route('officer.events.update', $event) }}">
        @csrf
        @method('PUT')

        @include('officer.events._form')

        <button type="submit">Update Event</button>
    </form>
@endsection