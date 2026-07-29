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
            'password' => bcrypt('admin123'),
        ]);
    }
}
