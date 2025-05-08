<?php
namespace App\Exceptions;

use Exception;

/**
 * Class CurrentWeatherException
 *
 * @package App\Exceptions
 */
class CurrentWeatherException extends Exception
{
    /**
     * CurrentWeatherException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message = 'Server Error', $code = 400)
    {
        parent::__construct($message, $code);
    }
}