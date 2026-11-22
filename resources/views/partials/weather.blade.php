{{-- Weather box: sky, temperature, humidity and wind at the campus (Open-Meteo) --}}
@php
    // Turn the weather code into a short word
    $label = fn ($code) => match (true) {
        $code === 0 => 'Clear',
        $code <= 3  => 'Partly Cloudy',
        $code <= 48 => 'Fog',
        $code <= 67 => 'Rain',
        $code <= 82 => 'Rain Showers',
        default     => 'Thunderstorm',
    };
@endphp
<div>
    <h3>Weather at campus</h3>
    @if ($weather)
        <p>{{ $label(data_get($weather, 'weather_code')) }}, {{ data_get($weather, 'temperature_2m') }}&deg;C</p>
        <p>Humidity: {{ data_get($weather, 'relative_humidity_2m') }}% | Wind: {{ data_get($weather, 'wind_speed_10m') }} km/h</p>
    @else
        <p>Weather is currently unavailable</p>
    @endif
    <small>Weather data by <a href="https://open-meteo.com" target="_blank" rel="noopener">Open-Meteo</a></small>
</div>
