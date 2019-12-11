<?php

namespace Anacreation\Cms\Policies;

use Anacreation\Cms\Models\Permissions\CmsAction;
use Anacreation\MultiAuth\Model\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class PartialPolicy
{
    use HandlesAuthorization;

    private $shortName = 'partial';

    /**
     * CommonPolicy constructor.
     */
    public function __construct() {
        //
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin $admin
     * @return bool
     */
    public function index(Admin $admin): bool {

        $permissionCode = $this->constructPermissionCode(CmsAction::Index());

        return $admin->hasPermission($permissionCode);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin  $admin
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function show(Admin $admin, Model $model): bool {
        $permissionCode = $this->constructPermissionCode(CmsAction::Show());

        return $admin->hasPermission($permissionCode);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin $admin
     * @return bool
     */
    public function create(Admin $admin): bool {
        $permissionCode = $this->constructPermissionCode(CmsAction::Create());

        return $admin->hasPermission($permissionCode);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin  $admin
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function store(Admin $admin, Model $model): bool {

        $permissionCode = $this->constructPermissionCode(CmsAction::Create());

        return $admin->hasPermission($permissionCode);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin  $admin
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function edit(Admin $admin): bool {
        $permissionCode = $this->constructPermissionCode(CmsAction::Edit());

        return $admin->hasPermission($permissionCode);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin  $admin
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function update(Admin $admin, Model $model): bool {
        $permissionCode = $this->constructPermissionCode(CmsAction::Edit());

        return $admin->hasPermission($permissionCode);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin  $admin
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function delete(Admin $admin, Model $model): bool {
        $permissionCode = $this->constructPermissionCode(CmsAction::Delete());

        return $admin->hasPermission($permissionCode);

    }

    /**
     * @param \Anacreation\Cms\Models\Permissions\CmsAction $action
     * @return string
     */
    final protected function constructPermissionCode(CmsAction $action
    ): string {
        return sprintf("%s_%s",
                       $action->getValue(),
                       strtolower($this->shortName)
        );
    }
}
