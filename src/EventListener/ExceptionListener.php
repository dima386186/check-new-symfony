<?php
namespace App\EventListener;

use App\Exceptions\CurrentWeatherException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @package App\EventListener
 */
class ExceptionListener
{
    private LoggerInterface $mainLogger;

    private LoggerInterface $weatherLogger;

    /**
     * ExceptionListener constructor.
     *
     * @param LoggerInterface $mainLogger
     * @param LoggerInterface $weatherLogger
     */
    public function __construct(LoggerInterface $mainLogger, LoggerInterface $weatherLogger)
    {
        $this->mainLogger = $mainLogger;
        $this->weatherLogger = $weatherLogger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $logger = $this->mainLogger;

        if ($exception instanceof CurrentWeatherException) {
            $logger = $this->weatherLogger;
        }

        $logger->error(sprintf(
            'Exception: %s. Error: %s in %s:%s',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));
    }
}
