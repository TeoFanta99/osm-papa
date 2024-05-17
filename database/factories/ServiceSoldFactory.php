<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Consultant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ServiceSoldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $invoice = Invoice :: inRandomOrder() -> first();
        $service = Service :: inRandomOrder() -> first();
        

        return [
            'price' => $service->price,
            'issue_date' => fake() -> dateTimeThisYear()->format('Y-m-d'),
            'invoice_id' => $invoice->id,
            'service_id' => $service->id,
        ];
    }
}
