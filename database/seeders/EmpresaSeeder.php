<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [
            ['nombre' => 'Constructora Horizonte SAS',  'nit' => '900123456-1', 'telefono' => '6014567890', 'direccion' => 'Cra 15 # 80-20, Bogotá'],
            ['nombre' => 'Logística del Norte Ltda',    'nit' => '800987654-2', 'telefono' => '6015678901', 'direccion' => 'Av. El Dorado # 68B-35, Bogotá'],
            ['nombre' => 'Distribuidora Central SA',    'nit' => '700456789-3', 'telefono' => '6016789012', 'direccion' => 'Calle 13 # 37-12, Bogotá'],
            ['nombre' => 'Servicios Técnicos JM SAS',   'nit' => '600321654-4', 'telefono' => '6017890123', 'direccion' => 'Carrera 7 # 32-45, Bogotá'],
        ];

        foreach ($empresas as $empresa) {
            DB::table('empresas')->updateOrInsert(
                ['nit' => $empresa['nit']],
                array_merge($empresa, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Empresas cargadas: ' . count($empresas));
    }
}
