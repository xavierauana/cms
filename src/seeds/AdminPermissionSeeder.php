<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Anacreation\MultiAuth\Model\AdminPermission;
use Illuminate\Database\Seeder;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $objects = [
            'menu',
            'link',
            'design',
            'page',
            'content',
            'group',
            'user',
            'role',
            'permission',
            'language',
            'admin',
            'admin_role',
            'admin_permission',
        ];
        $actions = [
            'index',
            'show',
            'create',
            'store',
            'edit',
            'update',
            'delete'
        ];

        foreach ($objects as $object) {
            foreach ($actions as $action) {
                $code = $action . "_" . $object;
                $label = ucwords($action) . " " . ucwords($object);

                if (!AdminPermission::whereCode($code)->count()) {
                    AdminPermission::create(compact('code', 'label'));
                }
            }
        }
    }


}