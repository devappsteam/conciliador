<?php

namespace App\Modules\Core\IAM\Database\Seeders;

use App\Modules\Core\IAM\Models\Permission;
use App\Modules\Core\IAM\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            switch ($role->slug) {
                case 'master':
                case 'admin':
                    $slugs = [
                        'view_roles',
                        'create_roles',
                        'edit_roles',
                        'delete_roles',
                        'view_permissions',
                        'view_users',
                        'create_users',
                        'edit_users',
                        'delete_users',
                    ];
                    break;
                case 'support':
                    $slugs = [
                        'view_roles',
                        'view_permissions',
                        'view_users',
                    ];
                    break;
                case 'collaborator':
                    $slugs = [
                        'view_roles',
                        'view_permissions',
                    ];
                    break;
                default:
                    $slugs = [];
            }

            if (!empty($slugs)) {
                $permissionIds = Permission::whereIn('slug', $slugs)->pluck('id')->toArray();
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
