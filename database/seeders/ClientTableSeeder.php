<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Consultant;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // recupero tutti i consulenti
        $consultants = Consultant :: all();

        // creo 50 clienti
        Client :: factory() -> count(50) -> create() -> each(function ($client) use ($consultants) {
            
            // seleziona un consulente casuale
            $randomConsultant = $consultants -> random();

            // assegna il consulente al cliente
            $client -> consultant() -> associate($randomConsultant);
            $client -> save();
        });
    }
}
