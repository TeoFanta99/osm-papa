<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $client = Client :: inRandomOrder()->first();

        return [
            'price' => fake() -> randomFloat(2, 100, 1000000),
            'invoice_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'sold_by' => $client->consultant->name . $client->consultant->lastname,
            'paid' => fake() -> boolean(),
            'quantity' => fake() -> randomDigit(),
        ];
    }
}
