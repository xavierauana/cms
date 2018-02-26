<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MenusControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index_no_user() {
        $response = $this->get("/admin/menus");
        $response->assertRedirect("/login");
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index_with_permission_user() {
        $admin = createAdminWithPermission('index_menu');
        $response = $this->actingAs($admin, 'admin')->get("/admin/menus");
        $response->assertStatus(200);
    }
}
