<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPAMAST SSG Event Management System</title>
    <style>
        /* Simple page styling */
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1b1b18; }
        .hero { max-width: 640px; margin: 12vh auto; padding: 32px; background: #fff; border-radius: 12px; text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        .btn { display: inline-block; margin: 8px; padding: 12px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .btn-primary { background: #1e7a3c; color: #fff; }
        .btn-outline { border: 2px solid #1e7a3c; color: #1e7a3c; }
    </style>
</head>
<body>
    {{-- Landing page: the first thing a visitor sees --}}
    <div class="hero">
        <h1>SPAMAST SSG Event Management System</h1>
        <p>Manage student government events, attendance, fines, announcements and reports in one place.</p>

        {{-- route('login') builds the address from the route's name --}}
        <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
        <a class="btn btn-outline" href="{{ route('register') }}">Register</a>
    </div>
</body>
</html>