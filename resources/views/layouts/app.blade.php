<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SPAMAST SSG</title>

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f4f6; color: #1f2937; }

        /* Page = sidebar on the left + content on the right */
        .app { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 240px; background: #1e3a5f; color: #fff; display: flex; flex-direction: column; padding: 20px 0; }
        .sidebar .brand { padding: 0 20px 20px; font-size: 18px; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,.2); }
        .sidebar nav { flex: 1; padding-top: 10px; }
        .sidebar nav a { display: block; padding: 10px 20px; color: #dbe7f5; text-decoration: none; }
        .sidebar nav a:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar .profile-box { padding: 15px 20px; border-top: 1px solid rgba(255,255,255,.2); }
        .avatar { display: inline-block; width: 38px; height: 38px; line-height: 38px; text-align: center; border-radius: 50%; background: #f59e0b; color: #1e3a5f; font-weight: bold; }
        .sidebar .profile-box .name { font-weight: bold; margin-top: 8px; }
        .sidebar .profile-box .role { font-size: 13px; color: #dbe7f5; margin-bottom: 8px; }
        .sidebar .profile-box a { color: #fff; font-size: 14px; }
        .sidebar .profile-box button { margin-top: 10px; width: 100%; padding: 8px; cursor: pointer; }

        /* Content area */
        .content-area { flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; padding: 15px 25px; border-bottom: 1px solid #e5e7eb; }
        .topbar h1 { margin: 0; font-size: 22px; }
        .content { padding: 25px; }

        /* Flash messages and cards */
        .flash-success { background: #dcfce7; color: #166534; padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; }
        .flash-error { background: #fee2e2; color: #991b1b; padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; }
        .cards { display: flex; gap: 15px; flex-wrap: wrap; }
        .card { background: #fff; border-radius: 6px; padding: 20px; min-width: 180px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .card .number { font-size: 28px; font-weight: bold; }

        /* Tables */
        .content table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.1); margin-bottom: 15px; }
        .content th { background: #1e3a5f; color: #fff; text-align: left; padding: 10px 12px; font-size: 14px; border: 1px solid #1e3a5f; }
        .content td { padding: 9px 12px; font-size: 14px; border: 1px solid #e5e7eb; }
        .content tr:nth-child(even) td { background: #f9fafb; }

        /* Headings and links */
        .content h2 { color: #1e3a5f; margin: 24px 0 12px; }
        .content h3 { color: #1e3a5f; }
        .content a { color: #1d4ed8; }

        /* Buttons */
        .content button, .content input[type=submit] { background: #1e3a5f; color: #fff; border: 0; padding: 7px 14px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .content button:hover, .content input[type=submit]:hover { background: #16304f; }

        /* Form fields */
        .content input[type=text], .content input[type=email], .content input[type=password], .content input[type=number], .content input[type=date], .content input[type=time], .content input[type=datetime-local], .content select, .content textarea { padding: 7px 10px; border: 1px solid #d1d5db; border-radius: 5px; font-size: 14px; background: #fff; }
        .content input:focus, .content select:focus, .content textarea:focus { outline: none; border-color: #1e3a5f; box-shadow: 0 0 0 3px rgba(30,58,95,.15); }
    </style>
</head>
<body>
    @php
        // The logged-in user and their initials (for example "JT")
        $user = auth()->user();
        $initials = strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1));
    @endphp

    <div class="app">
        {{-- Sidebar (its own file) --}}
        @include('partials.sidebar')

        <div class="content-area">
            {{-- Top bar with the page title --}}
            <header class="topbar">
                <h1>@yield('title')</h1>
            </header>

            <main class="content">
                {{-- Flash messages: keys are "success" and "error" --}}
                @if (session('success'))
                    <div class="flash-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="flash-error">{{ session('error') }}</div>
                @endif

                {{-- Each page puts its own content here --}}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>