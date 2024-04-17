<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Consultant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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

        $randomConsultant = Consultant :: inRandomOrder() -> first();

        return [
            'price' => fake() -> randomFloat(2, 100, 1000000),
            'customized_price' => fake() -> randomFloat(2, 100, 1000000),
            'invoice_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'sold_by' => $randomConsultant->name . $randomConsultant->lastname,
            'delivered_by' => $randomConsultant->name . $randomConsultant->lastname,
            'paid' => fake() -> boolean(),
        ];
    }
}
