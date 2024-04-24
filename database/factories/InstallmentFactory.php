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

        // $client_service = ClientService :: inRandomOrder() -> first();

        return [
            'amount' => fake() -> randomFloat(2, 100, 1000000),
            'expire_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'paid' => fake() -> boolean(),
            // 'client_service_id' => $client_service->id,
        ];
    }
}
