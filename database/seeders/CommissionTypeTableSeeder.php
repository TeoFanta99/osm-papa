<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CommissionType;

class CommissionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonTypes = file_get_contents(public_path('commissionTypes.json'));
        $typesDecoded = json_decode($jsonTypes, true);  

        foreach ($typesDecoded as $type) {
            $newType = new CommissionType();
            $newType->name = $type['name'];
            $newType->save();
        };
    }
}
