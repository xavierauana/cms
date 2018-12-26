<?php

namespace Anacreation\Cms\Policies;

use Anacreation\MultiAuth\Model\Admin;

class AdminRolePolicy extends CommonPolicy
{
    protected $shortName = 'admin_role';

    public function updatePassword(Admin $admin, Admin $administrator): bool {

        return $admin->id === $administrator->id;
    }
}
