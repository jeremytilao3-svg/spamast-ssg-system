<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPAMAST SSG - Main</title>
</head>
<body>
    <h1>SPAMAST SSG Event Management System</h1>

    {{-- Greet the logged-in user and show their role --}}
    <p>Welcome, {{ auth()->user()->first_name }}!</p>
    <p>Role: {{ auth()->user()->role->name }}</p>

    {{-- Logout must be a POST form with @csrf --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>