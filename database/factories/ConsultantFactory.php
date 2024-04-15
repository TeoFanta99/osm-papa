<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Level;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConsultantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        // recupera l'id di uno user esistente
        $user = User :: inRandomOrder() -> first();
        $level = Level :: inRandomOrder() -> first();

        return [
            'name' => fake() -> firstName(),
            'lastname' => fake() -> lastName(),
            'user_id' => $user->id,
            'level_id' => $level ->id,
        ];
    }
}
