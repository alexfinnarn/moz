<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class StyleAggregatorListenerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
          KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $content = $response->getContent();
        $unique_styles = [];

        // Extract all <style> tags with a 'comp' attribute
        if (preg_match_all('/<style comp="([^"]+)">([\s\S]*?)<\/style>/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $comp = $match[1];
                $style_content = $match[2];
                // Store unique styles keyed by the 'comp' value
                $unique_styles[$comp] = $style_content;
            }
        }

        // Aggregate the unique styles into a single <style> block, or multiple blocks if you prefer
        $all_styles = '';
        foreach ($unique_styles as $comp => $style_content) {
            $all_styles .= "<style comp=\"$comp\">$style_content</style>\n";
        }

        // Minify the aggregated styles
        $all_styles = $this->minify_css($all_styles);

        // Remove all original <style> blocks with a 'comp' attribute
        $content = preg_replace('/<style comp="[^"]+">[\s\S]*?<\/style>/', '', $content);

        // Insert the aggregated styles at the end of the </head> element
        $content = preg_replace('/<\/head>/', "$all_styles</head>", $content);

        // Update the Response object
        $response->setContent($content);
    }

    function minify_css(string $css): string {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove space after colons
        $css = str_replace(': ', ':', $css);
        // Remove whitespace
        $css = str_replace(['\r\n', '\r', '\n', '\t', '  ', '    ', '    '], '', $css);
        // Remove whitespace and collapse into a single line
        $css = preg_replace('/\s+/', ' ', $css);

        return trim($css);
    }
}
