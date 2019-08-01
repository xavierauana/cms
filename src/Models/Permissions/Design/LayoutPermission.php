<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-27
 * Time: 18:58
 */

namespace Anacreation\Cms\Models\Permissions\Design;


use MyCLabs\Enum\Enum;

/**
 * Class LayoutPermission
 * @method static LayoutPermission Create
 * @method static LayoutPermission Upload
 * @method static LayoutPermission Edit
 * @method static LayoutPermission Delete
 * @method static LayoutPermission Show
 * @package Anacreation\Cms\Models\Permissions
 */
class LayoutPermission extends Enum
{
    private const Create = 'create_layout';
    private const Upload = 'upload_layout';
    private const Edit   = 'edit_layout';
    private const Delete = 'delete_layout';
    private const Show   = 'show_layout';
}