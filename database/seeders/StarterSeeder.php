<?php

namespace Database\Seeders;

use Database\Seeders\Starter\ConfigurationSeeder;
use Illuminate\Database\Seeder;

class StarterSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ConfigurationSeeder::class,
        ]);
    }
}
