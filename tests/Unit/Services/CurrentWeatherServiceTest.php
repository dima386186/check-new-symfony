<?php
namespace Tests\Unit\Services;

use App\Exceptions\CurrentWeatherException;
use App\Services\Api\WeatherApiService;
use App\Services\CurrentWeatherService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Mockery;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * Class CurrentWeatherServiceTest
 *
 * @package Tests\Unit\Services
 */
class CurrentWeatherServiceTest extends TestCase
{
    private $loggerMock;

    public function setUp(): void
    {
        parent::setUp();

        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock->shouldReceive('info', 'error');

        $this->loggerMock = $loggerMock;
    }

    public function testGet(): void
    {
        $fakeResponseData = [
            'location' => ['name' => 'Austin'],
            'current' => [
                'temp_c' => 15.0,
                'condition' => ['text' => 'Sunny'],
            ],
        ];
        $fakeResponseBody = json_encode($fakeResponseData);

        $api = $this->setWeatherApiService($fakeResponseBody, 200);

        $currentWeatherService = new CurrentWeatherService($api, $this->loggerMock);
        $result = $currentWeatherService->get([]);

        $this->assertEquals('Austin', $result['city']);
        $this->assertEquals('15°C', $result['temperature']);
        $this->assertEquals('Sunny', $result['condition']);
    }

    public function testCurrentWeatherException(): void
    {
        $api = $this->setWeatherApiService(json_encode(['error' => 'message']), 400);

        $currentWeatherService = new CurrentWeatherService($api, $this->loggerMock);

        $this->expectException(CurrentWeatherException::class);

        $currentWeatherService->get([]);
    }

    private function setWeatherApiService(string $response, int $statusCode): WeatherApiService
    {
        $fakeResponse = new Response($statusCode, [], $response);
        $mockClient = $this->createMock(Client::class);

        if ($statusCode != 200) {
            $mockClient->expects($this->once())
                ->method('request')
                ->willThrowException(
                    new RequestException(
                        'API connection failed',
                        new Request('GET', 'current.json')
                    )
                );
        } else {
            $mockClient->expects($this->once())
                ->method('request')
                ->willReturn($fakeResponse);
        }

        $service = new WeatherApiService('test_api_key', 'https://fake-api.com');

        $reflection = new ReflectionClass($service);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($service, $mockClient);

        return $service;
    }
}