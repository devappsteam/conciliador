<?php

namespace App\Modules\Core\User\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Core\User\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@conciliador.com.br',
            'password' => bcrypt(env('DEFAULT_ADMIN_PASSWORD', 'Adm1n@Concili4dor#2026')),
            'last_login_at' => now(),
            'avatar_url' => 'https://ui-avatars.com/api/?name=Admin&background=random&size=128',
        ]);
    }
}
