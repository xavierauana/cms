<?php

namespace Anacreation\Cms\Policies;

use Anacreation\MultiAuth\Model\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

abstract class CommonPolicy
{
    use HandlesAuthorization;

    protected $shortName = "object";

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function index(Admin $admin): bool {

        return $admin->hasPermission("index_" . strtolower($this->shortName));
    }

    public function show(Admin $admin, Model $model): bool {
        return $admin->hasPermission("edit_" . strtolower($this->shortName));
    }

    public function create(Admin $admin): bool {
        return $admin->hasPermission("create_" . strtolower($this->shortName));
    }

    public function edit(Admin $admin, Model $model): bool {
        return $admin->hasPermission("edit_" . strtolower($this->shortName));
    }

    public function update(Admin $admin, Model $model): bool {
        return $admin->hasPermission("create_" . strtolower($this->shortName));
    }

    public function store(Admin $admin, Model $model): bool {
        return $admin->hasPermission("store_" . strtolower($this->shortName));
    }

    public function delete(Admin $admin, Model $model): bool {
        return $admin->hasPermission("delete_" . strtolower($this->shortName));
    }
}
