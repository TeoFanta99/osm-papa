<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'price' => fake() -> randomFloat(2, 100, 1000000),
            'invoice_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'sold_by' => $randomConsultant->name . $randomConsultant->lastname,
            'paid' => fake() -> boolean(),
            'quantity' => fake() -> randomDigit(),
        ];
    }
}
