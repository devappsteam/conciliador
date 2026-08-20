<?php

namespace App\Modules\Integrations\AcquirerTransaction\Database\Factories;

use App\Modules\Integrations\AcquirerTransaction\Models\AcquirerTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AcquirerTransactionFactory extends Factory
{
    protected $model = AcquirerTransaction::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
        ];
    }
}
