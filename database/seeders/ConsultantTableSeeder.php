<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consultant;

class ConsultantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonConsultants = file_get_contents(public_path('consultants.json'));
        $consultantsDecoded = json_decode($jsonConsultants, true);

        foreach ($consultantsDecoded as $consultant) {

            $newConsultant = new Consultant();
            $newConsultant->name = $consultant['name'];
            $newConsultant->lastname = $consultant['lastname'];
            $newConsultant->level_id = $consultant['level_id'];
            $newConsultant->user_id = $consultant['user_id'];
            $newConsultant->save();
            
        };
    }
}
