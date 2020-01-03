<?php

namespace Anacreation\Cms\Tests;

use Anacreation\Cms\Models\CommonContent;
use Anacreation\Cms\Models\Language;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CommonContentControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $language;

    public function setUp(): void {

        parent::setUp();

        $this->language = factory(Language::class)->create([
                                                               'code'       => 'en',
                                                               'is_default' => true,
                                                           ]);
    }

    /**
     * @test
     */
    public function get_index_page() {

        $uri = route("cms::common_contents.index");
        $user = $this->createAdminWithPermission('index_common_content');
        $this->withoutExceptionHandling();
        $respnose = $this->actingAs($user,
                                    'admin')
                         ->get($uri)
                         ->assertSuccessful()
                         ->assertViewIs('cms::admin.common_contents.index')
                         ->assertViewHas("commonContents");

    }

    /**
     * @test
     */
    public function get_create_page() {

        $uri = route("cms::common_contents.create");
        $user = $this->createAdminWithPermission('create_common_content');
        $this->withoutExceptionHandling();
        $respnose = $this->actingAs($user,
                                    'admin')
                         ->get($uri)
                         ->assertSuccessful()
                         ->assertViewIs('cms::admin.common_contents.create')
                         ->assertViewHas("languages");

    }

    /**
     * @test
     */
    public function post_store_page() {

        $uri = route("cms::common_contents.store");
        $user = $this->createAdminWithPermission('create_common_content');
        $key = "content_key";
        $label = "content label";
        $content = "testing content";

        $data = [
            'label'   => $label,
            'type'    => false,
            'key'     => $key,
            'content' => [
                [
                    'lang_id' => $this->language->id,
                    'content' => $content,
                ],
            ],
        ];

        $this->withoutExceptionHandling();

        $this->actingAs($user,
                        'admin')
             ->post($uri,
                    $data)
             ->assertRedirect(route('cms::common_contents.index'))
             ->assertSessionHas('status');

        $this->assertDatabaseHas('common_contents',
                                 [
                                     'key'   => $key,
                                     'label' => $label,
                                 ]);

        $this->assertDatabaseHas('text_contents',
                                 [
                                     'content' => $content,
                                 ]);
    }

    /**
     * @test
     */
    public function get_edit_page() {

        $uri = route("cms::common_contents.store");
        $user = $this->createAdminWithPermission('create_common_content');
        $key = "content_key";
        $label = "content label";
        $content = "testing content";

        $data = [
            'label'   => $label,
            'type'    => false,
            'key'     => $key,
            'content' => [
                [
                    'lang_id' => $this->language->id,
                    'content' => $content,
                ],
            ],
        ];

        $this->withoutExceptionHandling();

        $this->actingAs($user,
                        'admin')
             ->post($uri,
                    $data);

        // Update content

        $commonContent = CommonContent::first();

        $uri = route("cms::common_contents.edit",
                     $commonContent);

        $user = $this->createAdminWithPermission('edit_common_content');

        $this->withoutExceptionHandling();

        $this->actingAs($user,
                        'admin')
             ->get($uri)
             ->assertSuccessful()
             ->assertViewIs('cms::admin.common_contents.edit')
             ->assertViewHas(["languages", "commonContent"]);

    }

    /**
     * @test
     */
    public function put_update_page() {

        $uri = route("cms::common_contents.store");
        $user = $this->createAdminWithPermission(['create_common_content', 'edit_common_content']);
        $key = "content_key";
        $label = "content label";
        $content = "testing content";

        $data = [
            'label'   => $label,
            'type'    => false,
            'key'     => $key,
            'content' => [
                [
                    'lang_id' => $this->language->id,
                    'content' => $content,
                ],
            ],
        ];

        $this->withoutExceptionHandling();

        $this->actingAs($user,
                        'admin')
             ->post($uri,
                    $data);

        // Update content

        $commonContent = CommonContent::first();

        $data = [
            'label'   => $label,
            'type'    => false,
            'key'     => $key,
            'content' => [
                [
                    'lang_id' => $this->language->id,
                    'content' => 'update content',
                ],
            ],
        ];

        $uri = route("cms::common_contents.update",
                     $commonContent);

        $this->withoutExceptionHandling();

        $this->put($uri,
                   $data)
             ->assertRedirect(route('cms::common_contents.index'))
             ->assertSessionHas('status');

        $this->assertDatabaseHas('text_contents',
                                 [
                                     'content' => 'update content',
                                 ]);

        $this->assertDatabaseHas('text_contents',
                                 [
                                     'content' => 'update content',
                                 ]);
        $this->assertEquals('update content',
                            $commonContent->getContent(CommonContent::Identifier));

    }

    /**
     * @test
     */
    public function delete_common_content() {
        $uri = route("cms::common_contents.store");
        $user = $this->createAdminWithPermission(['create_common_content', 'delete_common_content']);
        $key = "content_key";
        $label = "content label";
        $content = "testing content";

        $data = [
            'label'   => $label,
            'type'    => false,
            'key'     => $key,
            'content' => [
                [
                    'lang_id' => $this->language->id,
                    'content' => $content,
                ],
            ],
        ];

        $this->withoutExceptionHandling();

        $this->actingAs($user,
                        'admin')
             ->post($uri,
                    $data);

        // Delete common content

        $commonContent = CommonContent::first();

        $uri = route('cms::common_contents.destroy',
                     $commonContent);

        $this->json('DELETE',
                    $uri)
             ->assertSuccessful();

        $this->assertDatabaseMissing('common_contents',
                                     [
                                         'id' => $commonContent->id,
                                     ]);

    }

}
