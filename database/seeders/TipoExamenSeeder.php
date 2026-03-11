<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoExamenSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Ingreso',          'descripcion' => 'Examen médico ocupacional de ingreso a empresa'],
            ['nombre' => 'Periódico',         'descripcion' => 'Control médico periódico anual'],
            ['nombre' => 'Egreso',            'descripcion' => 'Examen médico al finalizar contrato laboral'],
            ['nombre' => 'Post-incapacidad',  'descripcion' => 'Evaluación médica post-incapacidad superior a 30 días'],
            ['nombre' => 'Altura',            'descripcion' => 'Certificación para trabajo en alturas'],
            ['nombre' => 'Confinados',        'descripcion' => 'Certificación para espacios confinados'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_examen')->updateOrInsert(
                ['nombre' => $tipo['nombre']],
                array_merge($tipo, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Tipos de examen cargados: ' . count($tipos));
    }
}
