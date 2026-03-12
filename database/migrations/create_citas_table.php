<?php
// database/migrations/xxxx_create_citas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->restrictOnDelete();
            $table->foreignId('tipo_examen_id')->constrained('tipos_examen')->restrictOnDelete();

            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Índices para búsquedas frecuentes
            $table->index('fecha');
            $table->index('estado');
            $table->index(['fecha', 'hora']); // Para verificar doble asignación
        });
    }

    public function down(): void { Schema::dropIfExists('citas'); }
};
