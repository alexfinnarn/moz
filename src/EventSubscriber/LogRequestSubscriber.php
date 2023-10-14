<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LogRequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
          KernelEvents::TERMINATE => 'onKernelTerminate',
        ];
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        // Get the current path
        $request = $event->getRequest();
        $currentPath = $request->getPathInfo();

        // Get the current year and month
        $date = new \DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');

        $filename = sprintf('visits/%s-%s.csv', $year, $month);

        // Initialize the data array with headers
        $data = [['Route', 'Views']];

        // Read the existing data if the file exists
        if (file_exists($filename)) {
            $existingData = array_map('str_getcsv', file($filename));
            if (count($existingData) > 0) {
                // Skip the header row for processing but keep it for later
                $data = $existingData;
            }
        }

        // Search for the route and update the view count
        $found = false;
        foreach ($data as $index => $row) {
            if ($row[0] === $currentPath) {
                $data[$index][1] = intval($row[1]) + 1;
                $found = true;
                break;
            }
        }

        // If the route was not found, add a new row
        if (!$found) {
            $data[] = [$currentPath, 1];
        }

        // Write the updated data back to the file
        $handle = fopen($filename, 'w');
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }
}
