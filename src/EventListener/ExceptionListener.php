<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @package App\EventListener
 */
class ExceptionListener
{
    private LoggerInterface $logger;

    /**
     * ExceptionListener constructor.
     *
     * @param LoggerInterface $mainLogger
     */
    public function __construct(LoggerInterface $mainLogger)
    {
        $this->logger = $mainLogger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logger->error(sprintf(
            'Exception: %s. Error: %s in %s:%s',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));
    }
}
