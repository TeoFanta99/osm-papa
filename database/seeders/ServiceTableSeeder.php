<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonServices = file_get_contents(public_path('services.json'));
        $servicesDecoded = json_decode($jsonServices, true);

        foreach ($servicesDecoded as $service) {

            $newService = new Service();
            $newService->name = $service['name'];
            $newService->price = $service['price'];
            $newService->save();
            
        }
    }
}
