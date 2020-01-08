<?php

namespace Anacreation\Cms\Tests\Feature\Pages;

use Anacreation\Cms\CacheKey;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class ActivePageHashTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function get_active_pages_hash_table() {

        $urls = [
            'url_1',
            'url_2',
            'url_3',
            'url_4',
        ];

        foreach($urls as $url) {
            factory(Page::class)->create([
                                             'uri' => $url,
                                         ]);
        }

        $pages = Page::ActivePages();

        $this->assertEquals(count($urls),
                            count($pages));

        $paths = array_keys($pages);

        $this->assertEquals($urls,
                            $paths);
    }

    /**
     * @test
     */
    public function pages_with_parent_page() {

        $parentPage = factory(Page::class)->create();

        $page = factory(Page::class)->create([
                                                 'parent_id' => $parentPage->id,
                                             ]);

        $pages = Page::ActivePages();

        $paths = array_keys($pages);

        $targetPath = [
            $parentPage->uri,
            "{$parentPage->uri}/{$page->uri}",
        ];

        $this->assertEquals(2,
                            count($pages));
        $this->assertEquals($targetPath,
                            $paths);

        Cache::shouldReceive('put')
             ->with(CacheKey::ACTIVE_PAGES);
    }

    /**
     * @test
     */
    public function cache_update_with_page() {

        $page = factory(Page::class)->create();

        $pages = Page::ActivePages();

        $this->assertEquals($page->uri,
                            array_keys($pages)[0]);

        $page->update([
                          'uri' => 'new_uri',
                      ]);

        $pages = Page::ActivePages();

        $this->assertEquals($page->uri,
                            array_keys($pages)[0]);

    }

    /**
     * @test
     */
    public function active_page_exclude_non_active_pages() {

        $numberOfActivePages = 4;

        factory(Page::class,
                $numberOfActivePages)->create();

        $numberOfNonActivePages = 3;

        factory(Page::class,
                $numberOfNonActivePages)->create([
                                                     'is_active' => false,
                                                 ]);

        $pages = Page::ActivePages();

        $allPages = Page::all();

        $this->assertEquals($numberOfActivePages,
                            count($pages));

        $this->assertEquals($numberOfActivePages + $numberOfNonActivePages,
                            count($allPages));

    }

    /**
     * @test
     */
    public function page_query_successful_and_failed() {

        $this->withoutExceptionHandling();

        factory(Language::class)->create([
                                             'is_default' => true,
                                             'code'       => 'en',
                                         ]);

        $pages = factory(Page::class,
                         10)->create([
                                         'template' => 'home',
                                     ]);

        $page = $pages->random();

        $response = $this->get($page->uri);

        $response->assertSuccessful();

        $this->expectException(PageNotFoundHttpException::class);

        $this->get("this_is_not_a_valid_page");

    }


}
