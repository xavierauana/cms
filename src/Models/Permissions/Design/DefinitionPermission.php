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
 * @method static DefinitionPermission Create
 * @method static DefinitionPermission Upload
 * @method static DefinitionPermission Edit
 * @method static DefinitionPermission Delete
 * @method static DefinitionPermission Show
 * @package Anacreation\Cms\Models\Permissions
 */
class DefinitionPermission extends Enum
{
    private const Create = 'create_definition';
    private const Upload = 'upload_definition';
    private const Edit   = 'edit_definition';
    private const Delete = 'delete_definition';
    private const Show   = 'show_definition';
}