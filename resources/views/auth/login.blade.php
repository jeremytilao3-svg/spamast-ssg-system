<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>SPAMAST SSG QR Attendance System</h1>
    <h2>Login</h2>

    {{-- Success message (for example after registering) --}}
    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    {{-- Show validation errors, if any --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </p>
        <p>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </p>

        <button type="submit">Login</button>
    </form>

    <p>No account yet? <a href="{{ route('register') }}">Register here</a></p>
</body>
</html>