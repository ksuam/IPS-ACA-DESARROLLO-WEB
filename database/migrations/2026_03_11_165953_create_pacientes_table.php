<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150);
            $table->enum('tipo_documento', ['CC','TI','CE','PA','RC']);
            $table->string('numero_documento', 30)->unique();
            $table->date('fecha_nacimiento');
            $table->unsignedTinyInteger('edad');
            $table->string('direccion', 200);
            $table->string('telefono', 20)->nullable();
            $table->string('celular', 20);
            $table->string('eps', 100);
            $table->string('contacto_nombre', 150);
            $table->string('contacto_parentesco', 60);
            $table->string('contacto_telefono', 20);
            $table->foreignId('empresa_id')->constrained('empresas')->restrictOnDelete();
            $table->foreignId('tipo_examen_id')->constrained('tipos_examen')->restrictOnDelete();
            $table->date('fecha_examen');
            $table->enum('estado', ['activo','cancelado','completado'])->default('activo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pacientes'); }
};