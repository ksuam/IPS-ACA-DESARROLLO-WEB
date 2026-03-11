<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'edad',
        'direccion',
        'telefono',
        'celular',
        'eps',
        'contacto_nombre',
        'contacto_parentesco',
        'contacto_telefono',
        'empresa_id',
        'tipo_examen_id',
        'fecha_examen',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_examen'     => 'date',
    ];

    // ── Relaciones ──────────────────────────────────────────

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tipoExamen()
    {
        return $this->belongsTo(TipoExamen::class, 'tipo_examen_id');
    }

    // ── Scopes de búsqueda ──────────────────────────────────

    public function scopeBuscar($query, string $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombre_completo',   'like', "%{$termino}%")
              ->orWhere('numero_documento', 'like', "%{$termino}%")
              ->orWhere('eps',              'like', "%{$termino}%");
        });
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // ── Accesor: etiqueta del tipo de documento ─────────────

    public function getTipoDocumentoLabelAttribute(): string
    {
        return match ($this->tipo_documento) {
            'CC'  => 'Cédula de Ciudadanía',
            'TI'  => 'Tarjeta de Identidad',
            'CE'  => 'Cédula de Extranjería',
            'PA'  => 'Pasaporte',
            'RC'  => 'Registro Civil',
            default => $this->tipo_documento,
        };
    }
}
