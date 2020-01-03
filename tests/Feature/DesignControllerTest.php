<?php

namespace Anacreation\Cms\Tests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DesignControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index_no_admin() {

        $response = $this->get('/admin/designs');

        $response->assertRedirect('/login');

    }

    public function test_index_with_admin() {
        $admin = $this->createAdminWithPermission('index_design');
        $response = $this->actingAs($admin,
                                    'admin')->get('/admin/designs');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function get_upload_layout_without_permission() {
        $url = route('designs.upload.layout');

        $this->withoutExceptionHandling();

        $this->expectException(AuthorizationException::class);
        $admin = $this->createAdminWithPermission('something');
        $this->actingAs($admin,
                        'admin')
             ->get($url);
    }

    /**
     * @test
     */
    public function get_upload_layout_page() {
        $url = route('designs.upload.layout');
        $permissionCode = "upload_layout";

        $admin = $this->createAdminWithPermission($permissionCode);
        $response = $this->actingAs($admin,
                                    'admin')
                         ->get($url);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function get_upload_definition_without_permission() {
        $url = route('designs.upload.definition');

        $this->withoutExceptionHandling();

        $this->expectException(AuthorizationException::class);
        $admin = $this->createAdminWithPermission('something');
        $this->actingAs($admin,
                        'admin')
             ->get($url);
    }

    /**
     * @test
     */
    public function get_upload_definition_page() {
        $url = route('designs.upload.definition');
        $permissionCode = "upload_definition";

        $admin = $this->createAdminWithPermission($permissionCode);
        $response = $this->actingAs($admin,
                                    'admin')
                         ->get($url);
        $response->assertStatus(200);
    }
}
