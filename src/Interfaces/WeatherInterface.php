<?php
namespace App\Interfaces;

interface WeatherInterface
{
    /**
     * @param array $data data required for api request
     * @return array
     */
    public function get(array $data): array;
}