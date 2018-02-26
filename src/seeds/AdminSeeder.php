<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminRole;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $admin = [
            'name'     => 'Xavier Au',
            'email'    => 'xavier.au@anacreation.com',
            'password' => bcrypt('aukaiyuen'),
        ];
        $editor = [
            'name'     => 'Editor',
            'email'    => 'editor@anacreation.com',
            'password' => bcrypt('password'),
        ];

        if (!Admin::whereEmail($admin['email'])->count()) {
            $newAdmin = Admin::create($admin);

            $newAdmin->roles()->save(AdminRole::whereCode('super_admin')
                                              ->first());
        }
        if (!Admin::whereEmail($editor['email'])->count()) {
            $newAdmin = Admin::create($editor);

            $newAdmin->roles()->save(AdminRole::whereCode('editor')
                                              ->first());
        }
    }


}