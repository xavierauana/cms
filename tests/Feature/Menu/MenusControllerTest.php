<?php

namespace Anacreation\Cms\Tests\Feature\Menu;

use Anacreation\Cms\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
        $admin = $this->createAdminWithPermission('index_menu');
        $response = $this->actingAs($admin,
                                    'admin')->get("/admin/menus");
        $response->assertStatus(200);
    }


}
