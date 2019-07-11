<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-28
 * Time: 15:19
 */

namespace Anacreation\Cms\Models\Permissions;


use MyCLabs\Enum\Enum;

/**
 * Class CmsAction
 * @method static CmsAction Index
 * @method static CmsAction Create
 * @method static CmsAction Edit
 * @method static CmsAction Delete
 * @method static CmsAction Show
 * @package Anacreation\Cms\Models\Permissions
 */
class CmsAction extends Enum
{
    private const Index  = 'index';
    private const Create = 'create';
    private const Edit   = 'edit';
    private const Delete = 'delete';
    private const Show   = 'show';
}