@php
    // On the create page there is no $event and no $sessions yet
    $event = $event ?? null;
    $sessions = $sessions ?? collect();

    // Value of one session time: the old input first, then the saved time (HH:MM)
    $time = function (string $period, string $field) use ($sessions) {
        $saved = isset($sessions[$period]) ? substr((string) $sessions[$period]->$field, 0, 5) : '';
        return old("sessions.$period.$field", $saved);
    };

    $dayType = old('day_type', $event?->day_type ?? 'whole_day');
    $availability = old('availability', $event?->availability ?? 'scheduled');
@endphp

@if ($errors->any())
    <div class="flash-error">
        <ul>
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

<p>
    <label>Title</label><br>
    <input type="text" name="title" value="{{ old('title', $event?->title) }}" required>
</p>

<p>
    <label>Description</label><br>
    <textarea name="description" rows="3">{{ old('description', $event?->description) }}</textarea>
</p>

<p>
    <label>Venue</label><br>
    <input type="text" name="venue" value="{{ old('venue', $event?->venue) }}" required>
</p>

<p>
    <label>Event date</label><br>
    <input type="date" name="event_date" value="{{ old('event_date', $event?->event_date?->format('Y-m-d')) }}" required>
</p>

<p>
    <label>Day type</label><br>
    <select name="day_type" id="day_type">
        <option value="whole_day" @selected($dayType === 'whole_day')>Whole day (morning + afternoon)</option>
        <option value="half_day" @selected($dayType === 'half_day')>Half day (one session)</option>
    </select>
</p>

<p>
    <label>Availability</label><br>
    <select name="availability" id="availability">
        <option value="scheduled" @selected($availability === 'scheduled')>Scheduled (times below)</option>
        <option value="manual" @selected($availability === 'manual')>Manual (Officer opens and closes)</option>
    </select>
</p>

<p>
    <label>Fine per missed scan (₱)</label><br>
    <input type="number" name="fine_per_miss" step="0.01" min="0" value="{{ old('fine_per_miss', $event?->fine_per_miss ?? 0) }}" required>
</p>

{{-- One block per session. JavaScript shows only the blocks that match the day type. --}}
@foreach (['morning' => 'Morning session', 'afternoon' => 'Afternoon session', 'half_day' => 'Half-day session'] as $period => $label)
    <fieldset class="session-block" data-period="{{ $period }}" style="margin-bottom: 15px;">
        <legend>{{ $label }}</legend>

        <p>
            Check-in: from
            <input type="time" name="sessions[{{ $period }}][check_in_start]" value="{{ $time($period, 'check_in_start') }}">
            to
            <input type="time" name="sessions[{{ $period }}][check_in_end]" value="{{ $time($period, 'check_in_end') }}">
        </p>

        <p>
            Check-out: from
            <input type="time" name="sessions[{{ $period }}][check_out_start]" value="{{ $time($period, 'check_out_start') }}">
            to
            <input type="time" name="sessions[{{ $period }}][check_out_end]" value="{{ $time($period, 'check_out_end') }}">
        </p>
    </fieldset>
@endforeach

<p id="manual-note" style="display: none;">
    Manual events: the times above are optional. The Officer opens and closes check-in and check-out on the event page.
</p>

<script>
    // Show the right session blocks for the chosen day type
    const dayType = document.getElementById('day_type');
    const availability = document.getElementById('availability');

    function updateSessions() {
        const periods = dayType.value === 'whole_day' ? ['morning', 'afternoon'] : ['half_day'];

        document.querySelectorAll('.session-block').forEach(function (block) {
            block.style.display = periods.includes(block.dataset.period) ? 'block' : 'none';
        });

        document.getElementById('manual-note').style.display =
            availability.value === 'manual' ? 'block' : 'none';
    }

    dayType.addEventListener('change', updateSessions);
    availability.addEventListener('change', updateSessions);
    updateSessions();
</script>