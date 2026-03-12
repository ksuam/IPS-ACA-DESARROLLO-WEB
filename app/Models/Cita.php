<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'empresa_id',
        'tipo_examen_id',
        'fecha',
        'hora',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // ── Relaciones ──────────────────────────────────────────

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tipoExamen()
    {
        return $this->belongsTo(TipoExamen::class, 'tipo_examen_id');
    }

    // ── Scopes ──────────────────────────────────────────────

    public function scopeFiltrar($query, array $filtros)
    {
        if (!empty($filtros['fecha_desde'])) {
            $query->where('fecha', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->where('fecha', '<=', $filtros['fecha_hasta']);
        }
        if (!empty($filtros['empresa_id'])) {
            $query->where('empresa_id', $filtros['empresa_id']);
        }
        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }
        return $query;
    }

    // ── Verificar doble asignación ───────────────────────────
    // Retorna true si ya existe una cita en esa fecha y hora (excluyendo la cita actual en edición)

    public static function existeConflicto(string $fecha, string $hora, int $exceptId = 0): bool
    {
        return self::where('fecha', $fecha)
            ->where('hora', $hora)
            ->whereNotIn('estado', ['cancelada'])
            ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
            ->exists();
    }

    // ── Accesor: color badge según estado ────────────────────

    public function getBadgeClassAttribute(): string
    {
        return match ($this->estado) {
            'pendiente'   => 'bg-warning text-dark',
            'confirmada'  => 'bg-success',
            'cancelada'   => 'bg-danger',
            'completada'  => 'bg-primary',
            default       => 'bg-secondary',
        };
    }

    // ── Accesor: color para el calendario ───────────────────

    public function getCalendarColorAttribute(): string
    {
        return match ($this->estado) {
            'pendiente'  => '#f59e0b',
            'confirmada' => '#10b981',
            'cancelada'  => '#ef4444',
            'completada' => '#3b82f6',
            default      => '#6b7280',
        };
    }
}
