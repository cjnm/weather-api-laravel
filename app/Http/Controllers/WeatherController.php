<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;

//Controls Weather ⛈️
class WeatherController extends Controller
{
    protected $weatherservice;

    public function __construct(WeatherService $weatherservice)
    {
        $this->weatherservice = $weatherservice;
    }

    /**
     * Get weather metadata from WeatherService.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function getWeather(Request $request)
    {
        // Latitide and longitude sent by the user
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        // Get weather data from WeatherService
        $weatherdata = $this->weatherservice->getWeatherMetadata($lat, $lon);

        // Status code sent by WeatherService
        $sataus = $weatherdata['cod'];

        // Message sent by the WeatherService. `message` is sent only incase of an issue.
        $message = isset($weatherdata['message'])
            ? $weatherdata['message']
            : ($sataus == 200 ? 'Weather data' : 'Weather data not available');

        // Weather data is always sent with status code 200.
        $data = $sataus == 200 ? $weatherdata : '';

        return response()->json([
            'success' => $sataus == 200 ? true : false,
            'message' => $message,
            'data' => $data,
        ], $sataus);
    }
}
