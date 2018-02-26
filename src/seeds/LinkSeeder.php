<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Anacreation\Cms\Entities\ContentObject;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $links = [
            [
                'name'         => [
                    [
                        'lang_id' => 1,
                        'content' => 'Main Menu 1'
                    ]
                ],
                'external_uri' => '/menu_1',
                'order'        => 0,
                'menu_id'      => 1,
            ],
            [
                'name'         => [
                    [
                        'lang_id' => 1,
                        'content' => 'Main Menu 2'
                    ]
                ],
                'external_uri' => '/menu_2',
                'order'        => 2,
                'menu_id'      => 1,
            ],
            [

                'name'         => [
                    [
                        'lang_id' => 1,
                        'content' => 'Main Menu 3'
                    ]
                ],
                'external_uri' => '/menu_3',
                'order'        => 3,
                'menu_id'      => 1,
                'parent_id'    => 2
            ],
            [
                'name'         => [
                    [
                        'lang_id' => 1,
                        'content' => 'Footer Menu 1'
                    ]
                ],
                'external_uri' => '/footer_menu_1',
                'order'        => 0,
                'menu_id'      => 2,
            ],
            [
                'name'         => [
                    [
                        'lang_id' => 1,
                        'content' => 'Footer Menu 2'
                    ]
                ],
                'external_uri' => '/footer_menu_2',
                'order'        => 2,
                'menu_id'      => 2,
            ],
            [
                'name'         => [
                    [
                        'lang_id' => 1,
                        'content' => 'Footer Menu 3'
                    ]
                ],
                'external_uri' => '/footer_menu_3',
                'order'        => 3,
                'menu_id'      => 2,
                'parent_id'    => 5
            ],
        ];

        $service = new \Anacreation\Cms\Services\ContentService();

        foreach ($links as $link) {
            if (!\Anacreation\Cms\Models\Link::whereExternalUri($link['external_uri'])
                                             ->count()) {
                $newLink = \Anacreation\Cms\Models\Link::create([
                    'external_uri' => $link['external_uri'],
                    'order'        => $link['order'],
                    'menu_id'      => $link['menu_id'],
                    'parent_id'    => $link['parent_id'] ?? 0,
                ]);
                foreach ($link['name'] as $data) {
                    $contentObject = new ContentObject('link', $data['lang_id'],
                        $data['content'], 'string');
                    $service->updateOrCreateContentIndex($newLink,
                        $contentObject);
                }

            }
        }
    }


}