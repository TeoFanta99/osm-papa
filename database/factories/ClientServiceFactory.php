<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientService>
 */
class ClientServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'price' => fake() -> randomFloat(2, 200, 10000),
            'customized_price' => fake() -> randomFloat(2, 200, 10000),
            'purchase_date' => fake() -> dateTime(),
            'sold_by' => fake() -> firstName(),
            'delivered_by' => fake() -> firstName(),
            'paid' => fake() -> boolean(),
        ];
    }
}
