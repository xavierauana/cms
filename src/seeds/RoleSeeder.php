<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $roles = [];
        foreach ($roles as $role) {
            \Anacreation\Cms\Models\Role::create($role);
        }
    }


}