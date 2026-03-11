<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Evita duplicados si se corre el seeder más de una vez
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@almavida.com'],
            [
                'name'       => 'Administrador',
                'email'      => 'admin@almavida.com',
                // Hash::make usa la config de hashing de tu proyecto (bcrypt por defecto)
                'password'   => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Usuario admin creado: admin@almavida.com / password');
    }
}
