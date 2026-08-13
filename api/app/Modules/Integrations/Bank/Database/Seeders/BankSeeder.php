<?php

namespace App\Modules\Integrations\Bank\Database\Seeders;

use App\Modules\Integrations\Bank\Models\Bank;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Itau',
                'code' => '341',
                'logo' => null,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Bradesco',
                'code' => '237',
                'logo' => null,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Santander',
                'code' => '033',
                'logo' => null,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Banco do Brasil',
                'code' => '001',
                'logo' => null,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Caixa Econômica Federal',
                'code' => '104',
                'logo' => null,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Banco Inter',
                'code' => '077',
                'logo' => null,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Nubank',
                'code' => '260',
                'logo' => null,
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
