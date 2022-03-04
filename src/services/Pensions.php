<?php

namespace percipiolondon\staff\services;

use craft\base\Component;
use percipiolondon\staff\helpers\Logger;
use percipiolondon\staff\jobs\FetchPensionSchemesJob;
use percipiolondon\staff\jobs\CreatePensionJob;
use Craft;

class Pensions extends Component
{
    public function fetchPension(array $employee, string $employer, string $progress = "")
    {
        $queue = Craft::$app->getQueue();
        $queue->push(new CreatePensionJob([
            'description' => 'Fetch pension schemes',
            'criteria' => [
                'employee' => $employee,
                'employer' => $employer,
                'progress' => $progress,
            ]
        ]));
    }

    public function savePension(array $pension, string $progress = "")
    {
        $logger = new Logger();
        $logger->stdout($progress."✓ Save pension ...", $logger::RESET);
        $logger->stdout(" done" . PHP_EOL, $logger::FG_GREEN);
    }

    public function fetchPensionSchemes(array $employers)
    {
        $queue = Craft::$app->getQueue();
        $queue->push(new FetchPensionSchemesJob([
            'description' => 'Fetch pension schemes',
            'criteria' => [
                'employers' => $employers,
            ]
        ]));
    }

    public function savePensionScheme(array $pensionScheme, string $progress = "")
    {
        $logger = new Logger();
        $logger->stdout($progress."✓ Save pension scheme ...", $logger::RESET);
        $logger->stdout(" done" . PHP_EOL, $logger::FG_GREEN);
    }
}