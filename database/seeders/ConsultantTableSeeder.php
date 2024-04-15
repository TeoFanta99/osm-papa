<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Consultant;
use App\Models\User;


class ConsultantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Creiamo 5 consulenti
        $consultants = Consultant::factory()->count(5)->create();

        // Recuperiamo un utente a caso
        $user = User::inRandomOrder()->first();

        // Associamo i consulenti all'utente
        foreach ($consultants as $consultant) {
            $consultant->user()->associate($user);
            $consultant->save();
        }

    }
}
