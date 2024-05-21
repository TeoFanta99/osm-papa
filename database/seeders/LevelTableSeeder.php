<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonLevels = file_get_contents(public_path('levels.json'));
        $levelsDecoded = json_decode($jsonLevels, true);

        foreach ($levelsDecoded as $level) {

            $newLevel = new Level();
            $newLevel->name = $level['name'];
            $newLevel->save();
            
        }
    }
}
