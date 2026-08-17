<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Database\Factories;

use App\Modules\Reconciliation\AcquirerConfig\Models\AcquirerConfig;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AcquirerConfigFactory extends Factory
{
    protected $model = AcquirerConfig::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
        ];
    }
}
