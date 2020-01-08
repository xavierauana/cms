<?php

namespace Anacreation\Cms\Tests;

use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminPermission;
use Anacreation\MultiAuth\Model\AdminRole;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends \Tests\TestCase
{

    public function setUp() {
        parent::setUp();
        Artisan::call('cache:clear');
        $this->app->make('Illuminate\Database\Eloquent\Factory')
                  ->load(__DIR__.'/../database/factories');
    }

    public function adminSingIn(array $permissionCodes = []): Admin {

        $admin = factory(Admin::class)->create();

        if(count($permissionCodes)) {

            $permissions = [];
            foreach($permissionCodes as $code) {
                $permissions[] = factory(AdminPermission::class)->create([
                                                                             'code' => $code,
                                                                         ]);
            }

            $role = factory(AdminRole::class)->create();

            $role->permissions()->saveMany($permissions);

            $admin->roles()->save($role);
        }

        $this->actingAs($admin,
                        'admin');

        return $admin;
    }

    public function adminApiSignIn(array $permissionCodes = []): string {
        $admin = $this->adminSingIn($permissionCodes);

        Artisan::call('passport:client',
                      [
                          "--personal" => true,
                          '--name'     => 'cms',
                      ]);

        $token = $admin->createToken('Token Name')->accessToken;

        return $token;
    }

    public function createAdminWithPermission($permissionCode): Admin {
        $permissionCodes = collect((is_array($permissionCode) ? $permissionCode:
            [$permissionCode]));

        $permissions = $permissionCodes->map(function(string $code) {
            return factory(AdminPermission::class)->create([
                                                               'code' => $code,
                                                           ]);
        });

        $role = factory(AdminRole::class)->create();
        $role->permissions()->saveMany($permissions);
        $admin = factory(Admin::class)->create();
        $admin->roles()->save($role);

        return $admin;
    }
}
