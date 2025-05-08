<?php
namespace App\Services;

use App\Exceptions\CurrentWeatherException;
use App\Interfaces\WeatherInterface;
use App\Services\Api\WeatherApiService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

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
    public function __construct(WeatherApiService $api, LoggerInterface $weatherLogger) {
        $this->api = $api;
        $this->logger = $weatherLogger;
    }

    /**
     * @param array $data
     * @return array
     * @throws CurrentWeatherException
     * @throws Exception
     */
    public function get(array $data): array
    {
        try {
            $currentWeather = $this->api->getCurrentWeather($data);

            $log = " - Погода в {$currentWeather->getCity()}: {$currentWeather->getTemperature()}°C, {$currentWeather->getCondition()}.";
            $this->logger->info(date('Y-m-d H:i:s') . $log);
        } catch (GuzzleException $e) {
            $this->logger->error(sprintf(
                'Error: %s in %s:%s',
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            throw new CurrentWeatherException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw $e;
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