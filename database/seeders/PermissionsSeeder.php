<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermissions();
        $this->createRoles();
    }

    //TODO run on each seed
    public function createPermissions(){
        foreach (config('permissions_web') as $name) {
            Permission::findOrCreate($name);
        }
    }

    //TODO run only once
    public function createRoles(){
        /** @var Role $adminRole */
        $adminRole = Role::findOrCreate('Admin');
        $memberRole = Role::findOrCreate('Member');

        $adminRole->givePermissionTo(Permission::findByName('*'));
    }
}