<?php

namespace App\Modules\Integrations\Acquirer\Database\Factories;

use App\Modules\Integrations\Acquirer\Models\Acquirer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AcquirerFactory extends Factory
{
    protected $model = Acquirer::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
        ];
    }
}
