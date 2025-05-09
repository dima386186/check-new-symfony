<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Class ResponseListener
 *
 * @package App\EventListener
 */
class ResponseListener
{
    private $logger;

    public function __construct(LoggerInterface $weatherLogger)
    {
        $this->logger = $weatherLogger;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        $response = $event->getResponse();

        if ($routeName == 'current_weather') {
            $data = json_decode($response->getContent(), true);
            $log = " - Погода в {$data['data']['city']}: {$data['data']['temperature']}, {$data['data']['condition']}.";
            $this->logger->info(date('Y-m-d H:i:s') . $log);
        }
    }
}