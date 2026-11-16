@extends('layouts.app')

@section('title', 'New Event')

@section('content')
    <h1>New Event</h1>

    <p><a href="{{ route('events.index') }}">&laquo; Back to events</a></p>

    <form method="POST" action="{{ route('officer.events.store') }}">
        @csrf

        @include('officer.events._form')

        <button type="submit">Save Event</button>
    </form>
@endsection