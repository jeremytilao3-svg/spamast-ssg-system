<aside class="sidebar">
    <div class="brand">SPAMAST SSG</div>

    <nav>
        {{-- Everyone: their own dashboard --}}
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">Dashboard</a>

        {{-- Admin only --}}
        @if (auth()->user()->hasRole('Admin'))
            <a href="{{ route('admin.users.index') }}" class="{{ str_starts_with(request()->url(), route('admin.users.index')) ? 'active' : '' }}">Manage Users</a>
            <a href="{{ route('admin.institutes.index') }}" class="{{ str_starts_with(request()->url(), route('admin.institutes.index')) ? 'active' : '' }}">Manage Institutes</a>
            <a href="{{ route('admin.programs.index') }}" class="{{ str_starts_with(request()->url(), route('admin.programs.index')) ? 'active' : '' }}">Manage Programs</a>
        @endif

        {{-- Officers (gate: manage-ssg) --}}
        @can('manage-ssg')
            <a href="{{ route('officer.students.index') }}" class="{{ str_starts_with(request()->url(), route('officer.students.index')) ? 'active' : '' }}">Manage Students</a>
            <a href="{{ route('events.index') }}" class="{{ str_starts_with(request()->url(), route('events.index')) ? 'active' : '' }}">Manage Events</a>
            <a href="{{ route('events.index') }}" class="{{ str_starts_with(request()->url(), route('events.index')) ? 'active' : '' }}">Manage Fines</a>
            <a href="{{ route('announcements.index') }}" class="{{ str_starts_with(request()->url(), route('announcements.index')) ? 'active' : '' }}">Announcements</a>
        @endcan

        {{-- Officers and Advisers (gate: view-reports) --}}
        @can('view-reports')
            @if (auth()->user()->hasRole('Adviser'))
                <a href="{{ route('events.index') }}" class="{{ str_starts_with(request()->url(), route('events.index')) ? 'active' : '' }}">Events</a>
                <a href="{{ route('announcements.index') }}" class="{{ str_starts_with(request()->url(), route('announcements.index')) ? 'active' : '' }}">Announcements</a>
            @endif
            <a href="{{ route('reports.index') }}" class="{{ str_starts_with(request()->url(), route('reports.index')) ? 'active' : '' }}">Reports</a>
        @endcan

        {{-- Students (gate: use-attendance) --}}
        @can('use-attendance')
            <a href="{{ route('events.index') }}" class="{{ str_starts_with(request()->url(), route('events.index')) ? 'active' : '' }}">Events</a>
        <a href="{{ route('announcements.index') }}" class="{{ str_starts_with(request()->url(), route('announcements.index')) ? 'active' : '' }}">Announcements</a>
        @endcan
    </nav>

    {{-- Real name, initials and role of the logged-in user --}}
    <div class="profile-box">
        @if ($user->photo)
            <img class="avatar" src="{{ asset('storage/'.$user->photo) }}" alt="{{ $user->first_name }}" style="object-fit: cover;">
        @else
            <span class="avatar">{{ $initials }}</span>
        @endif
        <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="role">{{ $user->role->name }}</div>

        <a href="{{ route('profile.edit') }}" class="{{ str_starts_with(request()->url(), route('profile.edit')) ? 'active' : '' }}">My Profile</a>

        {{-- Logout must be a POST form with @csrf --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</aside>