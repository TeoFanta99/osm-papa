<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Consultant;
use App\Models\Installment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InstallmentInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $consultant = Consultant :: inRandomOrder()->first();
        $installment = Installment :: inRandomOrder()->first();

        return [
            'service' => fake() -> word(),
            'price' => fake() -> randomFloat(2, 100, 1000),
            'sold_by' => $consultant -> name . ' ' . $consultant ->lastname,
            'delivered_by' => $consultant -> name . ' ' . $consultant ->lastname,
            'installment_id' => $installment -> id,
        ];
    }
}
