
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
</head>
<body>
    <h1>SPAMAST SSG QR Attendance System</h1>
    <h2>Student Login</h2>

    @if ($errors->any())
        <p style="color: red;">
            {{ $errors->first() }}
        </p>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account?
        <a href="{{ url('/register') }}">Register here</a>
    </p>
</body>
</html>
