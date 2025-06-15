<?php

namespace Database\Factories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MerchantFactory extends Factory
{
    protected $model = Merchant::class;

    public function definition(): array
    {
        return [
            'uid' => (string) Str::uuid(),
            'name' => $this->faker->company,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->companyEmail,
            'two_factor_enabled' => $this->faker->boolean(20), // 20% chance enabled
            'category' => $this->faker->randomElement(['restaurant', 'auto', 'retail', 'wholesale', 'pharmacy']),
        ];
    }
}
