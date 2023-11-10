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
        $request = $event->getRequest();
        $response = $event->getResponse();
        $content = $response->getContent();
        $unique_styles = [];

        // Get the "styles" header from the request.
        $current_styles = $request->headers->get('styles');

        // The styles tags have a `comp` attribute that is used to identify the component.
        // Extract all <style> tags with a 'comp' attribute from the current styles.
        if (preg_match_all('/<style comp="([^"]+)">([\s\S]*?)<\/style>/', $current_styles, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $comp = $match[1];
                $style_content = $match[2];
                // Store unique styles keyed by the 'comp' value
                $unique_styles[$comp] = $style_content;
            }
        }

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

        // Remove any `<div id="styles-container">` blocks.
        $content = preg_replace('/<div id="styles-container">[\s\S]*?<\/div>/', '', $content);

        // Insert the aggregated styles at the start of the <body> tag within a <div id="styles-container"> block.
        if (!empty($current_styles)) {
            $content = preg_replace('/(<body[^>]*>)/', '$1<div id="styles-container" hx-oob-swap="true">' . $all_styles . '</div>', $content);
        } else {
            $content = preg_replace('/(<body[^>]*>)/', '$1<div id="styles-container">' . $all_styles . '</div>', $content);
        }

        // Update the Response object
//        $response->setContent($content);
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
