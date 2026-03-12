<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        $paciente1 = DB::table('pacientes')->where('numero_documento', '1020304050')->value('id');
        $paciente2 = DB::table('pacientes')->where('numero_documento', '1030405060')->value('id');
        $paciente3 = DB::table('pacientes')->where('numero_documento', '1040506070')->value('id');

        $empresa1 = DB::table('empresas')->where('nit', '900123456-1')->value('id');
        $empresa2 = DB::table('empresas')->where('nit', '800987654-2')->value('id');
        $empresa3 = DB::table('empresas')->where('nit', '700456789-3')->value('id');

        $exIngreso   = DB::table('tipos_examen')->where('nombre', 'Ingreso')->value('id');
        $exPeriodico = DB::table('tipos_examen')->where('nombre', 'Periódico')->value('id');
        $exAltura    = DB::table('tipos_examen')->where('nombre', 'Altura')->value('id');

        $citas = [
            [
                'paciente_id'    => $paciente1,
                'empresa_id'     => $empresa1,
                'tipo_examen_id' => $exIngreso,
                'fecha'          => now()->addDays(3)->format('Y-m-d'),
                'hora'           => '08:00:00',
                'estado'         => 'confirmada',
                'observaciones'  => 'Paciente debe traer exámenes de laboratorio previos.',
            ],
            [
                'paciente_id'    => $paciente2,
                'empresa_id'     => $empresa2,
                'tipo_examen_id' => $exPeriodico,
                'fecha'          => now()->addDays(3)->format('Y-m-d'),
                'hora'           => '09:00:00',
                'estado'         => 'pendiente',
                'observaciones'  => null,
            ],
            [
                'paciente_id'    => $paciente3,
                'empresa_id'     => $empresa3,
                'tipo_examen_id' => $exAltura,
                'fecha'          => now()->addDays(5)->format('Y-m-d'),
                'hora'           => '10:30:00',
                'estado'         => 'pendiente',
                'observaciones'  => 'Verificar certificado vigente.',
            ],
            [
                'paciente_id'    => $paciente1,
                'empresa_id'     => $empresa1,
                'tipo_examen_id' => $exIngreso,
                'fecha'          => now()->subDays(5)->format('Y-m-d'),
                'hora'           => '08:00:00',
                'estado'         => 'completada',
                'observaciones'  => 'Examen realizado sin novedades.',
            ],
        ];

        foreach ($citas as $cita) {
            DB::table('citas')->insert(
                array_merge($cita, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('✅ Citas de prueba cargadas: ' . count($citas));
    }
}
