<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-25
 * Time: 11:10
 */

namespace Anacreation\Cms\Enums;


use MyCLabs\Enum\Enum;

/**
 * Class DesignType
 * @method static DesignType Layout
 * @method static DesignType Definition
 * @method static DesignType Partial
 * @package Anacreation\Cms\Enums
 */
class DesignType extends Enum
{
    private const Layout     = "layouts";
    private const Definition = "definition";
    private const Partial    = "partials";
}