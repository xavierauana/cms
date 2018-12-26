<?php

namespace Anacreation\Cms\Policies;

use Anacreation\MultiAuth\Model\Admin;

class AdministratorPolicy extends CommonPolicy
{
    protected $shortName = 'admin';

    public function updatePassword(Admin $admin, Admin $administrator): bool {

        return $this->isSamePerson($admin, $administrator);
    }

    public function profile(Admin $admin, Admin $administrator): bool {
        return $this->isSamePerson($admin, $administrator);
    }

    /**
     * @param \Anacreation\MultiAuth\Model\Admin $admin
     * @param \Anacreation\MultiAuth\Model\Admin $administrator
     * @return bool
     */
    private function isSamePerson(Admin $admin, Admin $administrator): bool {
        return $admin->id === $administrator->id;
    }
}
