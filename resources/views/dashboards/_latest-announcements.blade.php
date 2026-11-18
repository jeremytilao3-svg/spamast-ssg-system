{{-- Three newest announcements (needs $latest from DashboardController) --}}
<h3>Latest Announcements</h3>
@forelse ($latest as $announcement)
    <p>
        <a href="{{ route('announcements.show', $announcement) }}">{{ $announcement->title }}</a><br>
        <small>{{ $announcement->created_at->format('M d, Y h:i A') }}</small>
    </p>
@empty
    <p>No announcements yet.</p>
@endforelse
<p><a href="{{ route('announcements.index') }}">View all announcements</a></p>
