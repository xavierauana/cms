<?php

namespace Anacreation\Cms\Tests\Feature\Pages;

use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeletePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_create_page() {
        $this->withoutExceptionHandling();

        $this->adminSingIn(['delete_page']);

        $page = factory(Page::class)->create();
        $uri = route("pages.destroy",
                     $page);
        $this->delete($uri)->assertSuccessful();

        $this->assertDatabaseMissing('pages',
                                     [
                                         'id' => $page->id,
                                     ]);
    }
}
