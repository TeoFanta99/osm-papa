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
        Consultant :: factory() -> count(10) -> make() -> each(function($consultant) {

            // associazione ad uno user casuale
            $user = User :: inRandomOrder() -> first();
            $consultant -> user() -> associate($user);
            
            $consultant -> save();
        });
    }
}
