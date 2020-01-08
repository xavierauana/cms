<?php

namespace Anacreation\Cms\Tests\Feature\Route;

use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutingTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() {
        parent::setUp(); // TODO: Change the autogenerated stub
        factory(Language::class)->create([
                                             'is_default' => true,
                                         ]);
    }

    /**
     * @test
     */
    public function get_active_page() {
        $this->withoutExceptionHandling();

        $uri = 'test_page';
        factory(Page::class)->create([
                                         'uri'      => $uri,
                                         'template' => 'home',
                                     ]);
        $response = $this->get(url($uri));
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function get_inactive_page() {
        $this->withoutExceptionHandling();

        $uri = 'test_page';
        factory(Page::class)->create([
                                         'uri'       => $uri,
                                         'template'  => 'test_layout',
                                         'is_active' => false,
                                     ]);

        $this->expectException(PageNotFoundHttpException::class);

        $this->get(url($uri));
    }

    /**
     * @test
     */
    public function test_nested_pages() {

        $this->withoutExceptionHandling();

        $parentUri = 'parent_page';
        $childUri = 'child_page';

        $parent = factory(Page::class)->create([
                                                   'uri'      => $parentUri,
                                                   'template' => 'home',
                                               ]);

        $page = factory(Page::class)->create([
                                                 'uri'      => $childUri,
                                                 'template' => 'home',
                                             ]);

        $parent->children()->save($page);

        $response = $this->get(url("{$parentUri}/{$childUri}"));
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_nested_child_page_not_active() {

        $this->withoutExceptionHandling();

        $parentUri = 'parent_page';
        $childUri = 'child_page';

        $parent = factory(Page::class)->create([
                                                   'uri'      => $parentUri,
                                                   'template' => 'home',
                                               ]);

        $page = factory(Page::class)->create([
                                                 'uri'       => $childUri,
                                                 'template'  => 'test_layout',
                                                 'is_active' => false,
                                             ]);

        $parent->children()->save($page);

        $this->get(url("{$parentUri}"))->assertSuccessful();

        $this->expectException(PageNotFoundHttpException::class);
        $this->get(url("{$parentUri}/{$childUri}"));
    }

    /**
     * @test
     */
    public function test_nested_parent_page_not_active() {

        $this->withoutExceptionHandling();

        $parentUri = 'parent_page';
        $childUri = 'child_page';

        $parent = factory(Page::class)->create([
                                                   'uri'       => $parentUri,
                                                   'template'  => 'test_layout',
                                                   'is_active' => false,
                                               ]);

        $page = factory(Page::class)->create([
                                                 'uri'      => $childUri,
                                                 'template' => 'home',
                                             ]);

        $parent->children()->save($page);

        $this->get(url("{$parentUri}/{$childUri}"))->assertSuccessful();

        $this->expectException(PageNotFoundHttpException::class);

        $this->get(url("{$parentUri}"));
    }
}
