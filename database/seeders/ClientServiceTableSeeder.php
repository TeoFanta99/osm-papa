<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientService;
use App\Models\Client;
use App\Models\Consultant;
use App\Models\Service;
use Carbon\Carbon;


class ClientServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = Service :: all();
        $clients = Client :: all();
        $consultants = Consultant :: all();

        for ($i = 0; $i < 20; $i++) {

            $service = $services->random();
            $client = $clients->random();
            $sellerConsultant = $consultants->random();
            $deliverConsultant = $consultants->random();

            // Creazione dell'acquisto con i dati casuale
            $clientService = new ClientService();

            $clientService->client_id = $client->id;
            $clientService->service_id = $service->id;
            $clientService->price = $service->price;
            $clientService->customized_price = $service->price;
            $clientService->invoice_date = Carbon::now(); 
            $clientService->sold_by = $sellerConsultant->id; 
            $clientService->delivered_by = $deliverConsultant->id; 
            
            // Genera casualmente lo stato "paid"
            $isPaid = rand(0, 1);
            $clientService->paid = $isPaid; // Indica se l'acquisto è stato pagato o meno

            $clientService->save();
        }
    }
}
