<?php

namespace App\Modules\Core\Company\Database\Seeders;

use App\Modules\Core\Company\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::factory()->count(10)->create();
    }
}
