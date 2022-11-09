<?php

namespace Database\Seeders;

use Database\Seeders\Standard\NotificationSeeder;
use Database\Seeders\Standard\PermissionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            NotificationSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
