<?php
/**
 * Author: Xavier Au
 * Date: 2019-02-12
 * Time: 11:11
 */

namespace Anacreation\Cms\Enums;


use MyCLabs\Enum\Enum;

/**
 * Class AdminPermissionAction
 * @method static AdminPermissionAction Index
 * @method static AdminPermissionAction Show
 * @method static AdminPermissionAction Create
 * @method static AdminPermissionAction Edit
 * @method static AdminPermissionAction Delete
 * @package Anacreation\Cms\Enums
 */
class AdminPermissionAction extends Enum
{
    private const Index  = 'index';
    private const Show   = 'show';
    private const Create = 'create';
    private const Edit   = 'edit';
    private const Delete = 'delete';
}