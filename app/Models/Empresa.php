<?php
// ── app/Models/Empresa.php ──────────────────────────────────

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table    = 'empresas';
    protected $fillable = ['nombre', 'nit', 'telefono', 'direccion'];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
