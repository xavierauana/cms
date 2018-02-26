<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:29 PM
 */


use Illuminate\Database\Seeder;

class CmsDatabaseSeeder extends Seeder
{
    public function run() {
        $this->call(LanguageSeeder::class);
        $this->call(AdminPermissionSeeder::class);
        $this->call(AdminRoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(LinkSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}