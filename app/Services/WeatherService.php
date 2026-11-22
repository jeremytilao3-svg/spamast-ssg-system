<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    // Weather for the campus, or null if we have nothing at all
    public function current(): ?array
    {
        // Same answer for 30 minutes; if the call fails, use the last good answer
        return Cache::remember('weather.current', now()->addMinutes(30), function () {
            return $this->fetch() ?? Cache::get('weather.last');
        });
    }

    // Ask Open-Meteo; returns null when it fails
    private function fetch(): ?array
    {
        try {
            $response = Http::timeout(3)->get(config('services.weather.url'), [
                'latitude'  => config('services.weather.lat'),
                'longitude' => config('services.weather.lon'),
                'timezone'  => 'Asia/Manila',
                'current'   => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
            ]);
        } catch (ConnectionException $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $data = $response->json('current');

        // Keep the last good answer for one day
        Cache::put('weather.last', $data, now()->addDay());

        return $data;
    }
}
