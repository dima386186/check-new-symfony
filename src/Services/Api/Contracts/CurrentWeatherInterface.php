<?php
namespace App\Services\Api\Contracts;

interface CurrentWeatherInterface
{
    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @return string
     */
    public function getTemperature(): string;

    /**
     * @return string weather conditions, such as 'Partly cloudy'
     */
    public function getCondition(): string;

    /**
     * @return string
     */
    public function getHumidity(): string;

    /**
     * @return string
     */
    public function getWindSpeed(): string;

    /**
     * @return string the weather was last updated, for example '2025-05-08 23:00'
     */
    public function getLastUpdated(): string;
}