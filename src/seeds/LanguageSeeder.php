<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:22 PM
 */


use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $language = [
            'label'      => 'English',
            'code'       => 'en',
            'is_default' => true,
            'is_active'  => true,
        ];

        if (!\Anacreation\Cms\Models\Language::whereCode($language['code'])
                                             ->count()) {
            \Anacreation\Cms\Models\Language::create($language);
        }

    }


}