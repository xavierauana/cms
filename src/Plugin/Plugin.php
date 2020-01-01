<?php
/**
 * Author: Xavier Au
 * Date: 2019-07-12
 * Time: 20:19
 */

namespace Anacreation\Cms\Plugin;


use Illuminate\Console\Application as Artisan;

class Plugin
{
    /**
     * @var string
     */
    private $pluginName;

    /**
     * @var string
     */
    private $entryPath;

    /**
     * @var string
     */
    private $entryPathName;

    /**
     * @var callable|null
     */
    private $scheduleFunctions = null;
    /**
     * @var callable|null
     */
    private $routes = null;

    /**
     * @var array
     */
    private $commands = [];


    /**
     * @return callable
     */
    public function getScheduleFunction(): ?callable {
        return $this->scheduleFunctions;
    }

    /**
     * @param callable $scheduleFunction
     * @return \Anacreation\Cms\Plugin\Plugin
     */
    public function setScheduleFunction(callable $scheduleFunction): Plugin {

        $this->scheduleFunctions = $scheduleFunction;

        if (config('cms.enable_scheduler', false)) {
            app()->booted(function () {
                ($this->scheduleFunctions)();
            });
        }

        return $this;
    }

    /**
     * Plugin constructor.
     * @param string $pluginName
     */
    public function __construct(string $pluginName) {
    }

    /**
     * @return string
     */
    public function getPluginName(): string {
        return $this->pluginName;
    }

    /**
     * @param string $entryPath
     * @param string $pathName
     * @return Plugin
     */
    public function setEntryPath(string $entryPath, string $pathName): Plugin {
        $this->entryPath = $entryPath;
        $this->entryPathName = $pathName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntryPath(): string {
        return $this->entryPath;
    }

    /**
     * @return string
     */
    public function getEntryPathName(): string {
        return $this->entryPathName;
    }

    /**
     * @param callable $routes
     * @return \Anacreation\Cms\Plugin\Plugin
     */
    public function setRoutes(callable $routes): Plugin {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @return callable|null
     */
    public function getRoutes(): ?callable {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getCommands(): array {
        return $this->commands;
    }

    /**
     * @param array $commands
     */
    public function setCommands(array $commands): Plugin {
        $this->commands = $commands;

        if (app()->runningInConsole()) {
            Artisan::starting(function ($artisan) {
                $artisan->resolveCommands($this->commands);
            });
        }

        return $this;
    }

}