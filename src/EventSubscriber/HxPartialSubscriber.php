<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class HxPartialSubscriber implements EventSubscriberInterface
{

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
          KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Throwable
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function onKernelResponse(ResponseEvent $event): void
    {

    }
}
