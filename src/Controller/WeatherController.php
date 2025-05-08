<?php
namespace App\Controller;

use App\Interfaces\WeatherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WeatherController
 *
 * @package App\Controller
 */
class WeatherController extends AbstractController
{
    #[Route('/', name: 'weather_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('weather/index.html.twig', [
            'cities' => ['Stockholm', 'Amsterdam', 'Sydney', 'Austin']
        ]);
    }

    #[Route(
        '/current-weather',
        name: 'weather_data',
        methods: ['GET'],
        condition: "request.headers.get('Accept') == 'application/json'"
    )]
    public function getCurrentWeather(WeatherInterface $currentWeatherService, Request $request): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $currentWeatherService->get($request->query->all())
        ]);
    }
}