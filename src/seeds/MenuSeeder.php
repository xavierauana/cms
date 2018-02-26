<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $menus = [
            [
                'name' => 'Main Menu',
                'code' => 'main'
            ],
            [
                'name' => 'Footer Menu',
                'code' => 'footer'
            ],
        ];

        foreach ($menus as $menu) {
            if (!\Anacreation\Cms\Models\Menu::whereCode($menu['code'])
                                             ->count()) {
                \Anacreation\Cms\Models\Menu::create($menu);
            }
        }
    }


}