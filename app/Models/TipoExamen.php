<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoExamen extends Model
{
    protected $table    = 'tipos_examen';
    protected $fillable = ['nombre', 'descripcion'];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
