<?php

namespace Tests\Feature;

use Anacreation\Cms\ContentModels\TextContent;
use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_page() {
        $msg = "this is the content from etsting suite";
        $identifier = "help_page_content";

        $language = factory(Language::class)->create([
            'code'       => 'en',
            'is_default' => true
        ]);

        $content = factory(TextContent::class)->create([
            'content' => $msg
        ]);

        factory(ContentIndex::class)->create([
            'language_id'  => $language->id,
            'identifier'   => $identifier,
            'content_type' => get_class($content),
            'content_id'   => $content->id,
        ]);

        factory(Page::class)->create([
            'uri'      => '/about',
            'template' => 'default',
        ]);

        $this->get('/about')->assertSee($msg);
    }
}
