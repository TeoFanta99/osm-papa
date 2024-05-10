<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\ServiceSold;

class InvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Invoice :: factory()->count(1)->create()->each(function ($invoice) {
            ServiceSold :: factory()->count(3)->create(['invoice_id' => $invoice->id]);
            
            $totalPrice = $invoice->servicesSold()->sum('price');
            $invoice->update(['price' => $totalPrice]);
        });
    }
}
