<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\MyOwnDatabaseMigration;
use Tests\TestCase;

class DesignControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index_no_admin() {

        $response = $this->get('/admin/designs');

        $response->assertRedirect('/login');

    }

    public function test_index_with_admin() {

        $admin = createAdminWithPermission('index_design');
        $response = $this->actingAs($admin, 'admin')->get('/admin/designs');
        $response->assertStatus(200);

    }


}
