<?php

namespace Anacreation\Cms\Policies;

use Anacreation\MultiAuth\Model\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class CmsSettingsPolicy
{
    use HandlesAuthorization;

    public function index(Admin $admin) {
        return $admin->hasPermission('index_setting');
    }

    public function edit(Admin $admin) {
        return $admin->hasPermission('edit_setting');
    }

    public function update(Admin $admin) {
        return $admin->hasPermission('update_setting');
    }

}
