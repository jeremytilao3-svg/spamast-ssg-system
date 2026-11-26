<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SPAMAST SSG</title>
    @include('auth._style')
</head>
<body>
    <div class="auth-card">
        <p class="auth-brand">SPAMAST SSG</p>
        <p class="auth-sub">QR Attendance System</p>
        <h2>Login</h2>

        {{-- Success message (for example after registering) --}}
        @if (session('success'))
            <div class="msg-ok">{{ session('success') }}</div>
        @endif

        {{-- Show validation errors, if any --}}
        @if ($errors->any())
            <div class="msg-err">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            {{-- Remember me: value="1" is sent when the box is ticked --}}
            <label class="remember">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="auth-link">No account yet? <a href="{{ route('register') }}">Register here</a></p>
    </div>
</body>
</html>
