<?php

namespace App\Modules\Core\IAM\Database\Seeders;

use App\Modules\Core\IAM\Models\Role;
use App\Modules\Core\User\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $masterRole = Role::where('slug', 'master')->first();
        $user = User::first();
        $user->roles()->syncWithoutDetaching([$masterRole->id]);
    }
}
