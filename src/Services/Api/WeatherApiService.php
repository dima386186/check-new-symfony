<?php
namespace App\Services\Api;

use App\Services\Api\Contracts\CurrentWeatherInterface;
use App\Services\Api\Models\CurrentWeather;
use GuzzleHttp\Client;

/**
 * Class WeatherApiService
 *
 * @package App\Services\Api
 */
class WeatherApiService
{
    protected Client $client;

    protected string $weatherApiKey;

    /**
     * WeatherApiService constructor.
     *
     * @param string $weatherApiKey
     * @param string $weatherApiBaseUrl
     */
    public function __construct(string $weatherApiKey, string $weatherApiBaseUrl) {
        $this->weatherApiKey = $weatherApiKey;
        $this->client = new Client(['base_uri' => $weatherApiBaseUrl]);
    }

    /**
     * @param array $data
     * @return CurrentWeatherInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getCurrentWeather(array $data): CurrentWeatherInterface
    {
        $response = $this->client->request('GET', 'current.json', [
            'query' => ['key' => $this->weatherApiKey] + $data
        ]);

        return new CurrentWeather(json_decode($response->getBody()->getContents(), true));
    }
}