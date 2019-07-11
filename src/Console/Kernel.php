<?php

namespace Anacreation\Cms\Console;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule) {

        parent::schedule($schedule);

        $plugins = app()->make("CmsPlugins");

        foreach ($plugins as $pluginName => $options) {
            if (isset($options['Scheduler']) and is_callable($options['Scheduler'])) {
                $options['Scheduler']($schedule);
                Log::info("scheduler for {$pluginName} has run");
            }
        }
    }
}
