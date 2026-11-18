{{-- Shared fields for create and edit. $announcement only exists when editing --}}
@if ($errors->any())
    <ul style="color: #b91c1c;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<p>
    <label>Title</label><br>
    <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" maxlength="255" required>
</p>

<p>
    <label>Message</label><br>
    <textarea name="body" rows="8" cols="60" required>{{ old('body', $announcement->body ?? '') }}</textarea>
</p>

<p>
    <label>Attachment (optional: PDF, JPG, PNG or WEBP, max 5 MB)</label><br>
    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp">
    @if (! empty($announcement->attachment))
        <br><small>Current file:
            <a href="{{ asset('storage/'.$announcement->attachment) }}" target="_blank">open</a>.
            Choose a new file to replace it.</small>
    @endif
</p>
