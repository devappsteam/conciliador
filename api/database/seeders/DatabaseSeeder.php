<?php

namespace Database\Seeders;

use App\Modules\Core\Company\Database\Seeders\CompanySeeder;
use App\Modules\Core\User\Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
        ]);
    }
}
