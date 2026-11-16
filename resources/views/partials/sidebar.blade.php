<aside class="sidebar">
    <div class="brand">SPAMAST SSG</div>

    <nav>
        {{-- Everyone: their own dashboard --}}
        <a href="{{ route(auth()->user()->dashboardRoute()) }}">Dashboard</a>

        {{-- Admin only --}}
        @if (auth()->user()->hasRole('Admin'))
            <a href="{{ route('admin.users.index') }}">Manage Users</a>
            <a href="{{ route('admin.institutes.index') }}">Manage Institutes</a>
            <a href="{{ route('admin.programs.index') }}">Manage Programs</a>
        @endif

        {{-- Officers (gate: manage-ssg) --}}
        @can('manage-ssg')
            <a href="{{ route('officer.students.index') }}">Manage Students</a>
            <a href="{{ route('events.index') }}">Manage Events</a>
            <a href="#">Manage Fines</a>
            <a href="#">Announcements</a>
        @endcan

        {{-- Officers and Advisers (gate: view-reports) --}}
        @can('view-reports')
            @if (auth()->user()->hasRole('Adviser'))
                <a href="{{ route('events.index') }}">Events</a>
            @endif
            <a href="{{ route('reports.index') }}">Reports</a>
        @endcan

        {{-- Students (gate: use-attendance) --}}
        @can('use-attendance')
            <a href="{{ route('events.index') }}">Events</a>
            <a href="#">My Attendance</a>
            <a href="#">My Fines</a>
        @endcan
    </nav>

    {{-- Real name, initials and role of the logged-in user --}}
    <div class="profile-box">
        <span class="avatar">{{ $initials }}</span>
        <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="role">{{ $user->role->name }}</div>

        <a href="{{ route('profile.edit') }}">My Profile</a>

        {{-- Logout must be a POST form with @csrf --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</aside>