<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceSold;
use App\Models\ServicePerInstallment;

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

            $servicesSold = ServiceSold::where('invoice_id', $invoice->id)->get();
            $serviceIds = $servicesSold->pluck('service_id')->toArray();

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


                // Aggiungi randomicamente delle istanze di ServicePerInstallment
                // Numero di servizi per questa rata
                // $servicesCount = rand(1, 3); 

                // for ($j = 0; $j < $servicesCount; $j++) {
                //     $randomServiceId = $serviceIds[array_rand($serviceIds)];
    
                //     $servicePerInstallment = new ServicePerInstallment();
                //     $servicePerInstallment->installment_id = $installment->id;
                //     $servicePerInstallment->service_sold_id = $randomServiceId;
                //     $servicePerInstallment->save();
                // }

                $randomServiceId = $serviceIds[array_rand($serviceIds)];

                $randomService = Service :: findOrFail($randomServiceId)->name;
            }
        });
    }
}
