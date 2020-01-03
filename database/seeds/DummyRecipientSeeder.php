<?php

use Illuminate\Database\Seeder;

class DummyRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for ($i = 0; $i < 10; $i++) {
            factory(\Anacreation\CmsEmail\Models\Recipient::class,
                10000)->create([
                'status'        => 'pending',
                'email_list_id' => 1
            ]);
        }

    }
}
