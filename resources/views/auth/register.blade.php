<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
</head>
<body>
    <h1>SPAMAST SSG QR Attendance System</h1>
    <h2>Student Registration</h2>

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <p>
            <label>Full Name:</label>
            <input type="text" name="name" required>
        </p>

        <p>
            <label>Student ID:</label>
            <input type="text" name="student_id" required>
        </p>

        <p>
            <label>Email:</label>
            <input type="email" name="email" required>
        </p>

        <p>
            <label>Password:</label>
            <input type="password" name="password" required>
        </p>

        <p>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
        </p>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account?
        <a href="{{ url('/login') }}">Login here</a>
    </p>
</body>
</html>
