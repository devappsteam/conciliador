<?php

namespace App\Modules\Core\Company\Database\Factories;

use App\Modules\Core\Company\Enums\Status;
use App\Modules\Core\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'document' => $this->faker->cnpj(false),
            'corporate_name' => $this->faker->company(),
            'trade_name' => $this->faker->companySuffix(),
            'state_registration' => $this->faker->numerify('########'),
            'municipal_registration' => $this->faker->numerify('########'),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'complement' => $this->faker->secondaryAddress(),
            'neighborhood' => $this->faker->citySuffix(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip_code' => $this->faker->postcode(),
            'country' => 'BR',
            'status' => $this->faker->randomElement(Status::values()),
        ];
    }
}
