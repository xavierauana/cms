<?php

use Illuminate\Database\Seeder;

class RecipientsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(DummyRecipientSeeder::class);
    }
}
