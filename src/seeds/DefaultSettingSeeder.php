<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $keys = [
            [
                'label'      => "Google Analytic Id",
                'key'        => 'analytic_id',
                'is_default' => true
            ],
            [
                'label'      => "Web Site Name",
                'key'        => 'web_site_name',
                'is_default' => true
            ],
            [
                'label'      => "Contact Us Email",
                'key'        => 'contact_us_email',
                'is_default' => true
            ],
        ];

        foreach ($keys as $setting) {
            DB::table('cms_settings')->insert([
                'key'        => $setting['key'],
                'label'      => $setting['label'],
                'is_default' => $setting['is_default'],
                'value'      => null,
            ]);
        }

    }
}
