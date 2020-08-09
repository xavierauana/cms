<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $permissions = [];
        foreach ($permissions as $permission) {
            \Anacreation\Cms\Models\Permission::create($permission);
        }
    }
}
