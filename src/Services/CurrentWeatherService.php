<?php
namespace App\Services;

use App\Exceptions\CurrentWeatherException;
use App\Interfaces\WeatherInterface;
use App\Services\Api\WeatherApiService;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class WeatherService
 *
 * @package App\Services
 */
class CurrentWeatherService implements WeatherInterface
{
    protected WeatherApiService $api;

    protected LoggerInterface $logger;

    /**
     * WeatherService constructor.
     *
     * @param WeatherApiService $api
     * @param LoggerInterface $weatherLogger
     */
    public function __construct(WeatherApiService $api) {
        $this->api = $api;
    }

    /**
     * @param array $data
     * @return array
     * @throws CurrentWeatherException
     */
    public function get(array $data): array
    {
        try {
            $currentWeather = $this->api->getCurrentWeather($data);
        } catch (GuzzleException $e) {
            $message = 'Weather service is not working.';
            $exceptionData = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw new CurrentWeatherException($exceptionData['error']['message'] ?? $message, $e->getCode());
        }

        return [
            'city' => $currentWeather->getCity(),
            'country' => $currentWeather->getCountry(),
            'temperature' => $currentWeather->getTemperature() . '°C',
            'condition' => $currentWeather->getCondition(),
            'humidity' => $currentWeather->getHumidity(),
            'wind-speed' => $currentWeather->getWindSpeed() . ' км/ч',
            'last-updated' => $currentWeather->getLastUpdated()
        ];
    }
}