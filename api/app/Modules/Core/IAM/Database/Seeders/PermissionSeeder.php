<?php

namespace App\Modules\Core\IAM\Database\Seeders;

use App\Modules\Core\IAM\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Lista de permissões organizadas por módulos
        $permissions = [
            // IAM
            ['name' => 'Visualizar Perfis', 'slug' => 'view_roles', 'guard_name' => 'api', 'module' => 'iam'],
            ['name' => 'Criar Perfis', 'slug' => 'create_roles', 'guard_name' => 'api', 'module' => 'iam'],
            ['name' => 'Editar Perfis', 'slug' => 'edit_roles', 'guard_name' => 'api', 'module' => 'iam'],
            ['name' => 'Excluir Perfis', 'slug' => 'delete_roles', 'guard_name' => 'api', 'module' => 'iam'],
            ['name' => 'Visualizar Permissões', 'slug' => 'view_permissions', 'guard_name' => 'api', 'module' => 'iam'],

            // Users
            ['name' => 'Visualizar Usuários', 'slug' => 'view_users', 'guard_name' => 'api', 'module' => 'users'],
            ['name' => 'Criar Usuários', 'slug' => 'create_users', 'guard_name' => 'api', 'module' => 'users'],
            ['name' => 'Editar Usuários', 'slug' => 'edit_users', 'guard_name' => 'api', 'module' => 'users'],
            ['name' => 'Excluir Usuários', 'slug' => 'delete_users', 'guard_name' => 'api', 'module' => 'users'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'] ?? 'api',
                    'module' => $permission['module'],
                    'uuid' => Permission::where('slug', $permission['slug'])->value('uuid') ?? Str::uuid()->toString()
                ]
            );
        }
    }
}
