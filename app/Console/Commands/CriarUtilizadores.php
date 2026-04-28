<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CriarUtilizadores extends Command
{
    protected $signature   = 'criar:utilizadores';
    protected $description = 'Cria os utilizadores e roles do sistema';

    public function handle()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        Role::firstOrCreate(['name' => 'director',         'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'assistente_dados', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'assistente_local', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'recepcionista',    'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'motorista',        'guard_name' => 'web']);

        // Utilizadores
        $utilizadores = [
            ['name' => 'Director',           'email' => 'director@fas.ao',  'role' => 'director'],
            ['name' => 'Assistente de Dados','email' => 'dados@fas.ao',     'role' => 'assistente_dados'],
            ['name' => 'Assistente Local',   'email' => 'local@fas.ao',     'role' => 'assistente_local'],
            ['name' => 'Recepcionista',      'email' => 'recepcao@fas.ao',  'role' => 'recepcionista'],
            ['name' => 'Motorista',          'email' => 'motorista@fas.ao', 'role' => 'motorista'],
        ];

        foreach ($utilizadores as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => bcrypt('password123')]
            );
            $user->syncRoles([$u['role']]);
            $this->info("✅ {$u['name']} criado/actualizado.");
        }

        $this->info('Concluído!');
    }
}