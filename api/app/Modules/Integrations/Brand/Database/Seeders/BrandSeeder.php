<?php

namespace App\Modules\Integrations\Brand\Database\Seeders;

use App\Modules\Integrations\Brand\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Visa', 'code' => 'visa'],
            ['name' => 'Mastercard', 'code' => 'mastercard'],
            ['name' => 'Elo', 'code' => 'elo'],
            ['name' => 'American Express', 'code' => 'amex'],
            ['name' => 'Hipercard', 'code' => 'hipercard'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['code' => $brand['code']],
                [
                    'uuid' => Brand::where('code', $brand['code'])->value('uuid') ?? Str::uuid()->toString(),
                    'name' => $brand['name'],
                    'status' => true,
                ]
            );
        }
    }
}
