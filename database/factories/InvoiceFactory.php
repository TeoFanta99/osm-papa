<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\ServiceSold;

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
            'price' => fake() -> randomFloat(2, 100, 1000),
            'invoice_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'paid' => fake() -> boolean(),
            'client_id' => $client->id,
        ];
    }
}
