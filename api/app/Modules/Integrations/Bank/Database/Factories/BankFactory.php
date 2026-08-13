<?php

namespace App\Modules\Integrations\Bank\Database\Factories;

use App\Modules\Integrations\Bank\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BankFactory extends Factory
{
    protected $model = Bank::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
        ];
    }
}
