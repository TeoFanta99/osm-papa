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
            'amount' => fake() -> randomFloat(2, 100, 1000000),
            'expire_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'paid' => fake() -> boolean(),
        ];
    }
}
