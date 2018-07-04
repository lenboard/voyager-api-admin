<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VoyagerDatabaseSeeder::class);
        $this->call(HtmlBlockSeeder::class);
        $this->call(TranslatesSeeder::class);
        $this->call(VoyagerDummyDatabaseSeeder::class);
    }
}
