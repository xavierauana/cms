<?php

namespace Anacreation\Cms\Tests;

use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Entities\ContentObject;
use Anacreation\Cms\Services\ContentService;
use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminPermission;
use Anacreation\MultiAuth\Model\AdminRole;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends \Tests\TestCase
{

    public function setUp(): void {
        parent::setUp();
        $path = __DIR__.'/../database/factories';
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * @param array $permissionCodes
     * @return \Anacreation\MultiAuth\Model\Admin
     */
    public function adminSingIn(array $permissionCodes = []): Admin {

        $admin = factory(Admin::class)->create();

        if(count($permissionCodes)) {
            $role = factory(AdminRole::class)->create();

            foreach($permissionCodes as $code) {
                $permission = factory(AdminPermission::class)->create([
                                                                          'code' => $code,
                                                                      ]);
                $role->permissions()->save($permission);
            }
            $admin->roles()->save($role);
        }

        $this->actingAs($admin,
                        'admin');

        return $admin;
    }

    /**
     * @param array $permissionCodes
     * @return string
     */
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

    /**
     * @param $permissionCode
     * @return \Anacreation\MultiAuth\Model\Admin
     */
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

    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param array                                            $contents
     * @param string                                           $identifier
     * @param string                                           $contentFieldType
     * @return \Anacreation\Cms\Contracts\ContentGroupInterface
     * @throws \Anacreation\Cms\Exceptions\IncorrectContentTypeException
     */
    protected function saveContent(ContentGroupInterface $contentOwner, array $contents,
        string $identifier, string $contentFieldType = 'text'
    ): ContentGroupInterface {
        $service = new ContentService;
        foreach($contents as $data) {
            $contentObject = new ContentObject($identifier,
                                               $data['lang_id'],
                                               $data['content'],
                                               $contentFieldType);
            $service->updateOrCreateContentIndexWithContentObject($contentOwner,
                                                                  $contentObject);
        }

        return $contentOwner;
    }
}
