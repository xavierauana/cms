<?php
/**
 * Author: Xavier Au
 * Date: 2019-07-12
 * Time: 20:10
 */

namespace Anacreation\Cms\Plugin;


use Illuminate\Support\Collection;

/**
 * Class CmsPluginCollection
 * @package Anacreation\Cms\views\admin
 */
class CmsPluginCollection
{

    private static $instance;


    /**
     * @var Plugin[]
     */
    private $plugins = [];

    /**
     * CmsPlugins constructor.
     */
    private function __construct() {
        $this->plugins = collect([]);
    }

    public static function Instance(): CmsPluginCollection {
        if (is_null(static::$instance)) {
            static::$instance = new CmsPluginCollection();
        }

        return static::$instance;
    }

    public static function EachPlugin(callable $callable): void {
        static::Instance()->getPlugins()->each($callable);
    }

    public static function AddPlugin(Plugin $plugin): void {
        static::Instance()->add($plugin);
    }

    public function add(Plugin $plugin): void {
        $this->plugins[] = $plugin;
    }

    public function getPlugins(): Collection {
        return collect($this->plugins);
    }

    public static function RegisterRoutes(): void {
        static::EachPlugin(function (Plugin $plugin) {
            if ($callable = $plugin->getRoutes()) {
                $callable();
            }
        });
    }

}