<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // $this->call(UsersTableSeeder::class);
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
