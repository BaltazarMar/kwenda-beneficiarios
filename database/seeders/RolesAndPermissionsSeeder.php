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

        // Criar as permissões
        Permission::firstOrCreate(['name' => 'editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'eliminar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'importar', 'guard_name' => 'web']);

        // Criar os roles
        $director = Role::firstOrCreate(['name' => 'director', 'guard_name' => 'web']);
        $assistente = Role::firstOrCreate(['name' => 'assistente_dados', 'guard_name' => 'web']);

        // Director: sem permissões (apenas view)
        $director->syncPermissions([]);

        // Assistente: todas as permissões
        $assistente->syncPermissions(['editar', 'eliminar', 'importar']);
    }
}