<?php

namespace App\Modules\Integrations\Acquirer\Database\Seeders;

use Illuminate\Support\Str;
use App\Modules\Integrations\Acquirer\Models\Acquirer;
use Illuminate\Database\Seeder;

class AcquirerSeeder extends Seeder
{
    public function run(): void
    {
        $acquirers = [
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Cielo',
                'slug' => 'cielo',
                'code' => '362',
                'logo' => 'https://www.cielo.com.br/assets_cielo/shared/menu/images/logo.svg',
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Rede',
                'slug' => 'rede',
                'code' => '00005',
                'logo' => 'https://www.itau.com.br/media/dam/m/7e4cb707f49ed886/original/rede-nl-logo-rede-amarelo-1221-95x29.png',
            ]
        ];

        foreach ($acquirers as $acquirer) {
            Acquirer::updateOrCreate(
                ['slug' => $acquirer['slug']],
                $acquirer
            );
        }
    }
}
