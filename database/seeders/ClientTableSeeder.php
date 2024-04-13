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

        // creo i clienti
        Client :: factory() -> count(30) -> create() -> each(function($client) use ($consultants) {

            // associo un consulente randomicamente
            $randomConsultant = $consultants->random();
            $client -> consultant_id = $randomConsultant->id;
            
            $client->save();
        });
    }
}
