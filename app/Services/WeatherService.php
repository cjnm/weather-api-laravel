<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class WeatherService
{
    /**
     * Get weather data from openweathermap.org.
     * The response contains and object with status code, Weather data if status code is 200,
     * and message incase if any issue.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function getWeatherMetadata($lat, $lon)
    {
        $apikey = env("OPEN_WEATHER_MAP_API_KEY", "");
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apikey";

        $weatherdata = Http::get($url);

        return $weatherdata->json();
    }
}
