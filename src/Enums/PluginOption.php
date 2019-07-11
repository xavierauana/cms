<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-28
 * Time: 11:23
 */

namespace Anacreation\Cms\Enums;


use MyCLabs\Enum\Enum;

/**
 * Class PluginOptions
 * @method static PluginOption Schedule
 * @method static PluginOption Commands
 * @package Anacreation\Cms\Enums
 */
class PluginOption extends Enum
{
    private const Scheduler = 'Scheduler';
    private const Commands   = 'Commands';
}