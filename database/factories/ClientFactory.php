<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Consultant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $consultant = Consultant :: inRandomOrder() -> first();

        return [
            'name' => fake() -> company(),
            'address' => fake() -> address(),
            'consultant_id' => $consultant -> id,
        ];
    }
}
