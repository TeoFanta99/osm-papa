<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder :: class,
            LevelTableSeeder :: class,
            ConsultantTableSeeder :: class,
            ClientTableSeeder :: class,
            ServiceTableSeeder :: class,
            InvoiceTableSeeder :: class,
            InstallmentTableSeeder :: class,
            NoteTableSeeder :: class,
        ]);
    }
}
