<?php
 
namespace Database\Seeders\Standard;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class NewOptionsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'group' => "system",
            'name' => "creation_of_new_users",
            'locked' => 0,
            'payload' => "true",
            'created_at' => now()
        ]);

        DB::table('settings')->insert([
            'group' => "system",
            'name' => "creation_of_new_servers",
            'locked' => 0,
            'payload' => "true",
            'created_at' => now()
        ]);
    }
}