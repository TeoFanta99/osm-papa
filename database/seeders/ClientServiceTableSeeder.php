<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Service;
use App\Models\ClientService;


class ClientServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // recupero dei clienti e dei servizi
        $clients = Client :: all();
        $services = Service :: all();

        // associo randomicamente i servizi ai clienti
        $clients->each(function ($client) use ($services) {
            // Scegli randomicamente alcuni servizi per il cliente
            $randomServices = $services->random(rand(1, 5));
            foreach ($randomServices as $service) {
                // Crea un'istanza della tabella ponte e salvala con l'ID del cliente e del servizio associati
                $clientService = new ClientService();
                $clientService->client_id = $client->id;
                $clientService->service_id = $service->id;
                $clientService->save();
            }
        });
    }
}
