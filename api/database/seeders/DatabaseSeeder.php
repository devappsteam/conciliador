<?php

namespace Database\Seeders;

use App\Modules\Core\Company\Database\Seeders\CompanySeeder;
use App\Modules\Core\IAM\Database\Seeders\PermissionRoleSeeder;
use App\Modules\Core\IAM\Database\Seeders\PermissionSeeder;
use App\Modules\Core\IAM\Database\Seeders\RoleSeeder;
use App\Modules\Core\IAM\Database\Seeders\RoleUserSeeder;
use App\Modules\Core\User\Database\Seeders\UserSeeder;
use App\Modules\ERP\Department\Database\Seeders\DepartmentSeeder;
use App\Modules\ERP\Position\Database\Seeders\PositionSeeder;
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
            PermissionSeeder::class,
            RoleSeeder::class,
            PermissionRoleSeeder::class,
            RoleUserSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
        ]);
    }
}
