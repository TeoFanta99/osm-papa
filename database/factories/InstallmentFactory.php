<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InstallmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
        'amount' => fake() -> randomFloat(2, 200, 10000),
        'expire_date' => fake() -> dateTime(),
        'paid' => fake() -> boolean(),
        ];
    }
}
