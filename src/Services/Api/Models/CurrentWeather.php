<?php
namespace App\Services\Api\Models;

use App\Services\Api\Contracts\CurrentWeatherInterface;

/**
 * Class CurrentWeather
 *
 * @package App\Services\Api\Models
 */
class CurrentWeather implements CurrentWeatherInterface
{
    /**
     * @var string city
     */
    protected string $name = '';

    /**
     * @var string
     */
    protected string $country = '';

    /**
     * @var string temperature in Celsius
     */
    protected string $temp_c = '';

    /**
     * @var string weather conditions, such as 'Partly cloudy'
     */
    protected string $condition = '';

    /**
     * @var string
     */
    protected string $humidity = '';

    /**
     * @var string wind speed in km/h
     */
    protected string $wind_kph = '';

    /**
     * @var string the weather was last updated, for example '2025-05-08 23:00'
     */
    protected string $last_updated = '';

    /**
     * CurrentWeather constructor.
     *
     * @param array $currentWeatherContents
     */
    public function __construct(array $currentWeatherContents) {

        $location = $currentWeatherContents['location'] ?? [];
        $current = $currentWeatherContents['current'] ?? [];

        foreach ($location as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        foreach ($current as $key => $value) {
            if (property_exists($this, $key) && is_scalar($value)) {
                $this->{$key} = strval($value);
            }
            if ($key == 'condition') {
                $this->condition = $value['text'];
            }
        }
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getTemperature(): string
    {
        return $this->temp_c;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getHumidity(): string
    {
        return $this->humidity;
    }

    /**
     * @return string
     */
    public function getWindSpeed(): string
    {
        return $this->wind_kph;
    }

    /**
     * @return string
     */
    public function getLastUpdated(): string
    {
        return $this->last_updated;
    }
}