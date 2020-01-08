<?php

namespace Anacreation\Cms\Tests\Feature\UploadAssets;

use Anacreation\Cms\Enums\AdminPermissionAction;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Tests\TestCase;
use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminPermission;
use Anacreation\MultiAuth\Model\AdminRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadLayoutTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $role;

    public function setUp() {
        parent::setUp(); // TODO: Change the autogenerated stub
        factory(Language::class)->create([
                                             'is_default' => true,
                                         ]);

        $this->admin = factory(Admin::class)->create();

        $this->role = factory(AdminRole::class)->create();

        $this->admin->roles()->save($this->role);
    }

    /**
     * @test
     */
    public function show_upload_template_link_in_menu() {

        $this->withoutExceptionHandling();
        collect([
                    "upload_layout",
                    AdminPermissionAction::Index()->getValue()."_layout",
                    AdminPermissionAction::Create()->getValue()."_layout",
                ])->each(function($permissionCode) {
            $permission = new AdminPermission();
            $permission->label = $permissionCode;
            $permission->code = $permissionCode;
            $permission->save();
            $this->role->permissions()->attach($permission->id);
        });

        $this->actingAs($this->admin,
                        'admin');

        $uri = url("admin/designs");

        $response = $this->get($uri);

        $response->assertSee('Upload Layout')
                 ->assertSee(route('designs.upload.layout'));

    }

    /**
     * @test
     */
    public function visit_upload_layout() {
        $this->withoutExceptionHandling();

        collect(["upload_layout"])->each(function($permissionCode) {
            $permission = new AdminPermission();
            $permission->label = $permissionCode;
            $permission->code = $permissionCode;
            $permission->save();
            $this->role->permissions()->attach($permission->id);
        });
        $this->actingAs($this->admin,
                        'admin');

        $uri = route('designs.upload.layout');

        $this->get($uri)
             ->assertSuccessful()
             ->assertViewIs("cms::admin.designs.upload.layout")
             ->assertSee("Upload Template");
    }
}

