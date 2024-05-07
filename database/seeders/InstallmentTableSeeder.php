<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Installment;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceSold;

class InstallmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // recupero gli acquisti
        $invoices = Invoice :: all();

        // ciclo su ogni acquisto e creo da 1 a 3 rate
        $invoices -> each (function ($invoice) {

            // recupero il prezzo di ogni acquisto
            $price = $invoice -> price;

            // genera un numero casuale di rate
            $installmentsNumber = rand(1, 3);

            // calcolo l'importo di ogni rata
            $amountPerInstallment = round($price / $installmentsNumber, 2);


            // genero le rate sulla base di quante ne ha l'acquisto
            for ($i = 1; $i <= $installmentsNumber; $i++) {

                // calcolo l'importo della rata corrente
                $currentAmount = ($i < $installmentsNumber) ? $amountPerInstallment : $price - ($amountPerInstallment * ($installmentsNumber - 1));

                // creo una rata dal model
                $installment = new Installment();

                // le assegno l'id dell'acquisto
                $installment -> invoice_id = $invoice -> id;

                // assegno alla rata il suo valore monetario (dico ad ogni rata quanto vale)
                $installment -> amount = $currentAmount;

                // assegno una data di scadenza random da 30 a 90 giorni partendo da oggi
                $installment-> expire_date = now()->addDays(rand(30, 90));

                $installment -> paid = true;

                $installment->save();

                $servicesSold = ServiceSold :: where('invoice_id', $installment->invoice_id)->get();
                
                $serviceIds = $servicesSold->pluck('service_id')->toArray();

                $randomServiceId = $serviceIds[array_rand($serviceIds)];

                $randomService = Service :: findOrFail($randomServiceId)->name;

                // Commission :: factory()->count($installmentsNumber)->create([
                // 'installment_id' => $installment->id,
                // 'price' => $currentAmount / $installmentsNumber,
                // 'service' => $randomService
                // ]);
            }
        });
    }
}
