<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientService;
use App\Models\Installment;


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
        $purchases = ClientService :: all();

        // ciclo su ogni acquisto e creo da 1 a 3 rate
        $purchases -> each (function ($clientService) {

            // recupero il prezzo di ogni acquisto
            $price = $clientService -> price;

            // genera un numero casuale di rate
            $installmentsNumber = rand(1, 3);

            // calcolo l'importo di ogni rata
            $amountPerInstallment = round($price / $installmentsNumber, 2);


            // genero le rate sulla base di quante ne ha l'acquisto
            for ($i = 1; $i <= $installmentsNumber; $i++) {

                // calcolo l'importo della rata corrente
                $currentAmount = ($i < $numberOfInstallments) ? $amountPerInstallment : $price - ($amountPerInstallment * ($numberOfInstallments - 1));

                // creo una rata dal model
                $installment = new Installment();

                // le assegno l'id dell'acquisto
                $installment -> client_service_id = $clientService -> id;

                // assegno alla rata il suo valore monetario (dico ad ogni rata quanto vale)
                $installment -> amount = $currentAmount;

                // assegno una data di scadenza random da 30 a 90 giorni partendo da oggi
                $installment-> expire_date = now()->addDays(rand(30, 90));

                $installment -> save();
            }
        });
    }
}
