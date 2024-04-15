<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Consultant;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // recupero i dati nel json
        $jsonLevels = file_get_contents(public_path('levels.json'));

        // li decodifico, così da permettere a php di leggerli
        $levelsJson = json_decode($jsonLevels, true);

        // ciclo sui livelli presenti nel json e li salvo nel DB
        foreach ($levelsJson as $level) {
            
            $newLevel = new Level;
            $newLevel -> name = $level['name'];
            $newLevel -> save();
        }

        // recupero i consulenti
        $consultants = Consultant :: all();

        // recupero i livelli
        $levels = Level :: all();

        // Cicla su tutti i consulenti e assegna casualmente un livello a ciascun consulente
        $consultants->each(function ($consultant) use ($levels) {
            // Associo casualmente un livello tra quelli disponibili
            $randomLevel = $levels->random();
            $consultant->level()->associate($randomLevel);
            $consultant->save();
        });
    }
}
