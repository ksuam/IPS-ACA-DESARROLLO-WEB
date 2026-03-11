<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EmpresaSeeder::class,
            TipoExamenSeeder::class,
            PacienteSeeder::class,
        ]);
    }
}
