<?php
/**
 * Author: Xavier Au
 * Date: 20/2/2018
 * Time: 3:36 PM
 */

namespace Anacreation\Cms\traits;


use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

trait RolePermissionTrait
{
    public function roles(): Relation {
        return $this->belongsToMany(\Anacreation\Cms\Models\Role::class);
    }

    public function permissions(): Collection {
        $roles = $this->roles()->with('permissions')->get();
        $permissions = $roles->map(function(\Anacreation\Cms\Models\Role $role
        ) {
            return $role->permissions;
        })->flatten()->unique('id')->values();

        return $permissions;
    }

    /**
     * @param string|string[] $code
     * @return bool
     * @throws \Exception
     */
    public function hasPermission($code): bool {
        if( !is_string($code) and !is_array($code)) {
            throw new \InvalidArgumentException("Argument must be a string or an array of string!");
        }
        $codes = is_string($code) ? [$code]: $code;

        $permissions = $this->permissions();

        $permissionCodes = $permissions->pluck('code')->toArray();

        return count(array_intersect($codes,
                                     $permissionCodes)) > 0;
    }

    public function hasRole($role): bool {
        if( !is_string($role) and !is_array($role)) {
            throw new \Exception("Invalide code!");
        }
        $codes = is_string($role) ? [$role]: $role;

        $roleCodes = $this->roles()->pluck('code')->toArray();

        return count(array_intersect($codes,
                                     $roleCodes)) > 0;
    }
}