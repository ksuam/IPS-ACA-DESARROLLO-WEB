<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs reales de empresa y tipo_examen para no hardcodear
        $empresa1 = DB::table('empresas')->where('nit', '900123456-1')->value('id');
        $empresa2 = DB::table('empresas')->where('nit', '800987654-2')->value('id');
        $empresa3 = DB::table('empresas')->where('nit', '700456789-3')->value('id');

        $exIngreso    = DB::table('tipos_examen')->where('nombre', 'Ingreso')->value('id');
        $exPeriodico  = DB::table('tipos_examen')->where('nombre', 'Periódico')->value('id');
        $exAltura     = DB::table('tipos_examen')->where('nombre', 'Altura')->value('id');

        $pacientes = [
            [
                'nombre_completo'     => 'Carlos Andrés Martínez López',
                'tipo_documento'      => 'CC',
                'numero_documento'    => '1020304050',
                'fecha_nacimiento'    => '1990-05-12',
                'edad'                => 34,
                'direccion'           => 'Calle 45 # 23-10, Bogotá',
                'telefono'            => '6012345678',
                'celular'             => '3101234567',
                'eps'                 => 'Sanitas',
                'contacto_nombre'     => 'María López',
                'contacto_parentesco' => 'Madre',
                'contacto_telefono'   => '3209876543',
                'empresa_id'          => $empresa1,
                'tipo_examen_id'      => $exIngreso,
                'fecha_examen'        => now()->addDays(10)->format('Y-m-d'),
                'estado'              => 'activo',
            ],
            [
                'nombre_completo'     => 'Laura Sofía Gómez Ramos',
                'tipo_documento'      => 'CC',
                'numero_documento'    => '1030405060',
                'fecha_nacimiento'    => '1995-08-22',
                'edad'                => 30,
                'direccion'           => 'Carrera 20 # 50-30, Bogotá',
                'telefono'            => null,
                'celular'             => '3152345678',
                'eps'                 => 'Compensar',
                'contacto_nombre'     => 'Pedro Gómez',
                'contacto_parentesco' => 'Padre',
                'contacto_telefono'   => '3163456789',
                'empresa_id'          => $empresa2,
                'tipo_examen_id'      => $exPeriodico,
                'fecha_examen'        => now()->addDays(15)->format('Y-m-d'),
                'estado'              => 'activo',
            ],
            [
                'nombre_completo'     => 'Jhon Sebastián Torres Vargas',
                'tipo_documento'      => 'CC',
                'numero_documento'    => '1040506070',
                'fecha_nacimiento'    => '1988-11-03',
                'edad'                => 37,
                'direccion'           => 'Av. Boyacá # 12-45, Bogotá',
                'telefono'            => '6013456789',
                'celular'             => '3183456789',
                'eps'                 => 'Nueva EPS',
                'contacto_nombre'     => 'Ana Vargas',
                'contacto_parentesco' => 'Cónyuge',
                'contacto_telefono'   => '3004567890',
                'empresa_id'          => $empresa3,
                'tipo_examen_id'      => $exAltura,
                'fecha_examen'        => now()->addDays(20)->format('Y-m-d'),
                'estado'              => 'activo',
            ],
        ];

        foreach ($pacientes as $paciente) {
            DB::table('pacientes')->updateOrInsert(
                ['numero_documento' => $paciente['numero_documento']],
                array_merge($paciente, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Pacientes de prueba cargados: ' . count($pacientes));
    }
}
