<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Anacreation\MultiAuth\Model\AdminPermission;
use Anacreation\MultiAuth\Model\AdminRole;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $roles = [
            [
                'code'  => 'super_admin',
                'label' => 'Super Administrator',
            ],
            [
                'code'  => 'editor',
                'label' => 'Editors',
            ]
        ];

        foreach ($roles as $role) {
            if (!AdminRole::whereCode($role['code'])->count()) {
                $role = AdminRole::create($role);

                if ($role['code'] === 'editor') {

                    $permissionIds = AdminPermission::where('code', 'like',
                        '%page%')->orWhere('code', 'like', '%content%')
                                                    ->pluck('id')->toArray();
                    $role->permissions()
                         ->sync($permissionIds);
                } else {
                    $role->permissions()->sync(AdminPermission::pluck('id')
                                                              ->toArray());
                }
            }
        }

    }


}