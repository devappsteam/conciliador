<?php

namespace App\Modules\Core\IAM\Database\Seeders;

use App\Modules\Core\IAM\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Master', 'slug' => 'master', 'guard_name' => 'api', 'description' => 'Acesso total ao sistema'],
            ['name' => 'Administrador', 'slug' => 'admin', 'guard_name' => 'api', 'description' => 'Administrador do sistema'],
            ['name' => 'Suporte', 'slug' => 'support', 'guard_name' => 'api', 'description' => 'Suporte do sistema'],
            ['name' => 'Colaborador', 'slug' => 'collaborator', 'guard_name' => 'api', 'description' => 'Colaborador do sistema'],
            ['name' => 'Cliente', 'slug' => 'client', 'guard_name' => 'api', 'description' => 'Cliente do sistema'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'guard_name' => $role['guard_name'] ?? 'api',
                    'description' => $role['description'] ?? '',
                    'uuid' => Role::where('slug', $role['slug'])->value('uuid') ?? Str::uuid()->toString()
                ]
            );
        }
    }
}
