<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PrettyPrintSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
          KernelEvents::RESPONSE => ['onKernelResponse', 1000],
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $originalContent = $response->getContent();

        // Format the HTML content (just an example using DOMDocument)
        $dom = new \DOMDocument();
        @$dom->loadHTML($originalContent); // The "@" suppresses warnings, use with caution
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $formattedHtml = $dom->saveHTML();

        $response->setContent($formattedHtml);
    }
}
