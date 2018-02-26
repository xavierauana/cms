<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksControllerTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index_no_user() {
        $response = $this->get("/admin/links");

        $response->assertRedirect('/login');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_index_with_user() {
        $admin = createAdminWithPermission('index_link');
        $response = $this->actingAs($admin, 'admin')->get("/admin/links");

        $response->assertStatus(200);
    }
}
