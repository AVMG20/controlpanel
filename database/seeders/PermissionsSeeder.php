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

    public function createPermissions(){
        Permission::create(['name' => '*']);

        Permission::create(['name' => 'admin.roles.write']);
        Permission::create(['name' => 'admin.roles.read']);

        Permission::create(['name' => 'admin.users.read']);
        Permission::create(['name' => 'admin.users.read']);
    }

    public function createRoles(){
        /** @var Role $adminRole */
        $adminRole = Role::create(['name' => 'admin']);
        $memberRole = Role::create(['name' => 'member']);

        $adminRole->givePermissionTo(Permission::findByName('*'));
    }
}
