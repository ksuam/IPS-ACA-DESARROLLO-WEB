<?php
// database/migrations/2026_03_01_000001_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('users'); }
};

// ─────────────────────────────────────────────────────────────────────────────
// database/migrations/2026_03_01_000002_create_empresas_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('nit', 30)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('empresas'); }
};

// ─────────────────────────────────────────────────────────────────────────────
// database/migrations/2026_03_01_000003_create_tipos_examen_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipos_examen', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('tipos_examen'); }
};

// ─────────────────────────────────────────────────────────────────────────────
// database/migrations/2026_03_01_000004_create_pacientes_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            // Datos personales
            $table->string('nombre_completo', 150);
            $table->enum('tipo_documento', ['CC','TI','CE','PA','RC']);
            $table->string('numero_documento', 30)->unique();
            $table->date('fecha_nacimiento');
            $table->unsignedTinyInteger('edad');
            $table->string('direccion', 200);
            $table->string('telefono', 20)->nullable();
            $table->string('celular', 20);
            $table->string('eps', 100);

            // Contacto adicional
            $table->string('contacto_nombre', 150);
            $table->string('contacto_parentesco', 60);
            $table->string('contacto_telefono', 20);

            // Examen
            $table->foreignId('empresa_id')->constrained('empresas')->restrictOnDelete();
            $table->foreignId('tipo_examen_id')->constrained('tipos_examen')->restrictOnDelete();
            $table->date('fecha_examen');

            $table->enum('estado', ['activo','cancelado','completado'])->default('activo');

            $table->timestamps();
            $table->index('numero_documento');
            $table->index('fecha_examen');
        });
    }

    public function down(): void { Schema::dropIfExists('pacientes'); }
};
