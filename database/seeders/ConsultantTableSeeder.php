<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Level;
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
        // recupero gli users e i levels
        $users = User :: all();
        $levels = Level :: all();

        // creo 10 consulenti
        Consultant :: factory() -> count(10) -> create() -> each(function ($consultant) use ($users, $levels) {

            // seleziona uno user e un level casuale
            $randomUser = $users -> random();
            $randomLevel = $levels -> random();

            // assegna lo user e il level al consultant
            $consultant -> user() -> associate($randomUser);
            $consultant -> level() -> associate($randomLevel);
            $consultant -> save();

        });
    }
}
