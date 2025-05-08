<?php
namespace App\Controller;

use App\Exceptions\CurrentWeatherException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ErrorController
 *
 * @package App\Controller
 */
class ErrorController extends AbstractController
{
    #[Route('/_error/{code}', name: 'app_error')]
    public function show(FlattenException $exception, Request $request): Response
    {
        $statusCode = $exception->getStatusCode();
        $message = $exception->getMessage();

        if ($request->headers->get('Accept') == 'application/json') {
            $response = ['error' => $message, 'success' => false];

            if ($exception->getClass() == CurrentWeatherException::class) {
                $response['weather_api_failed'] = true;
                $statusCode = $exception->getCode();
            }

            return $this->json($response, $statusCode);
        }

        return $this->render('error/error.html.twig', [
            'message' => 'Server Error',
            'code' => $statusCode,
        ]);
    }
}
