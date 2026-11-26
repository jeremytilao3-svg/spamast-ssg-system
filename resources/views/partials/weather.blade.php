@php
    $code = data_get($weather, 'weather_code');
    $label = match (true) {
        $code === null => 'Unknown',
        $code === 0 => 'Clear',
        $code <= 3 => 'Partly Cloudy',
        $code <= 48 => 'Fog',
        $code <= 67 => 'Rain',
        $code <= 82 => 'Rain Showers',
        default => 'Thunderstorm',
    };
    $icon = match (true) {
        $code === null => '&#127777;&#65039;',
        $code === 0 => '&#9728;&#65039;',
        $code <= 3 => '&#9925;',
        $code <= 48 => '&#127787;&#65039;',
        $code <= 67 => '&#127783;&#65039;',
        $code <= 82 => '&#127782;&#65039;',
        default => '&#9928;&#65039;',
    };
@endphp

<div class="weather-card">
    <div class="weather-title">Weather at campus</div>
    @if ($weather)
        <div class="weather-main">
            <span class="weather-icon">{!! $icon !!}</span>
            <span class="weather-temp">{{ data_get($weather, 'temperature_2m') }}&deg;C</span>
        </div>
        <div class="weather-label">{{ $label }}</div>
        <div class="weather-chips">
            <span>Humidity {{ data_get($weather, 'relative_humidity_2m') }}%</span>
            <span>Wind {{ data_get($weather, 'wind_speed_10m') }} km/h</span>
        </div>
    @else
        <div class="weather-label">Weather is currently unavailable</div>
    @endif
    <small>Data by <a href="https://open-meteo.com" target="_blank" rel="noopener">Open-Meteo</a></small>
</div>
