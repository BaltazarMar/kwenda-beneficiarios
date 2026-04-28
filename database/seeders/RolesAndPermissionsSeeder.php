<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        Role::firstOrCreate(['name' => 'director',        'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'assistente_dados','guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'assistente_local','guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'recepcionista',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'motorista',       'guard_name' => 'web']);
    }
}
