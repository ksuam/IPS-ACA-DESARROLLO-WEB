@extends('layouts.app')

@section('title', 'Detalle Paciente')
@section('page-title', 'Detalle del Paciente')

@section('topbar-actions')
    <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil me-1"></i>Editar
    </a>
    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
@endsection

@section('content')

<div class="row g-4">

    <!-- Columna principal -->
    <div class="col-lg-8">

        <!-- Datos personales -->
        <div class="card content-card mb-4">
            <div class="card-header">
                <i class="bi bi-person-circle me-2 text-muted"></i>Datos Personales
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $campos = [
                            ['label' => 'Nombre completo',   'value' => $paciente->nombre_completo,     'col' => 'col-12'],
                            ['label' => 'Tipo de documento', 'value' => $paciente->tipo_documento_label,'col' => 'col-md-4'],
                            ['label' => 'Número documento',  'value' => $paciente->numero_documento,    'col' => 'col-md-4'],
                            ['label' => 'Fecha nacimiento',  'value' => $paciente->fecha_nacimiento->format('d/m/Y'), 'col' => 'col-md-4'],
                            ['label' => 'Edad',              'value' => $paciente->edad . ' años',      'col' => 'col-md-3'],
                            ['label' => 'Teléfono',          'value' => $paciente->telefono ?: '—',    'col' => 'col-md-3'],
                            ['label' => 'Celular',           'value' => $paciente->celular,             'col' => 'col-md-3'],
                            ['label' => 'EPS',               'value' => $paciente->eps,                 'col' => 'col-md-3'],
                            ['label' => 'Dirección',         'value' => $paciente->direccion,           'col' => 'col-12'],
                        ];
                    @endphp

                    @foreach($campos as $campo)
                    <div class="{{ $campo['col'] }}">
                        <div class="detail-label">{{ $campo['label'] }}</div>
                        <div class="detail-value">{{ $campo['value'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Examen laboral -->
        <div class="card content-card">
            <div class="card-header">
                <i class="bi bi-clipboard2-pulse me-2 text-muted"></i>Examen Laboral
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-label">Empresa solicitante</div>
                        <div class="detail-value">{{ $paciente->empresa->nombre ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">NIT de la empresa</div>
                        <div class="detail-value">{{ $paciente->empresa->nit ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tipo de examen</div>
                        <div class="detail-value">{{ $paciente->tipoExamen->nombre ?? '—' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Fecha del examen</div>
                        <div class="detail-value fw-semibold">{{ $paciente->fecha_examen->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value">
                            <span class="badge rounded-pill badge-estado-{{ $paciente->estado }}">
                                {{ ucfirst($paciente->estado) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna lateral -->
    <div class="col-lg-4">

        <!-- Contacto adicional -->
        <div class="card content-card mb-3">
            <div class="card-header">
                <i class="bi bi-telephone me-2 text-muted"></i>Contacto Adicional
            </div>
            <div class="card-body d-flex flex-column gap-3">
                <div>
                    <div class="detail-label">Nombre</div>
                    <div class="detail-value">{{ $paciente->contacto_nombre }}</div>
                </div>
                <div>
                    <div class="detail-label">Parentesco</div>
                    <div class="detail-value">{{ $paciente->contacto_parentesco }}</div>
                </div>
                <div>
                    <div class="detail-label">Teléfono</div>
                    <div class="detail-value">{{ $paciente->contacto_telefono }}</div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card content-card mb-3">
            <div class="card-header"><i class="bi bi-gear me-2 text-muted"></i>Acciones</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning w-100">
                    <i class="bi bi-pencil-square me-1"></i>Editar paciente
                </a>
                <form method="POST" action="{{ route('pacientes.destroy', $paciente) }}"
                      onsubmit="return confirm('¿Eliminar definitivamente a {{ addslashes($paciente->nombre_completo) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-1"></i>Eliminar paciente
                    </button>
                </form>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i>Volver al listado
                </a>
            </div>
        </div>

        <!-- Metadatos -->
        <div class="card content-card">
            <div class="card-body">
                <div class="detail-label mb-1">Registrado</div>
                <div style="font-size:13px" class="text-muted mb-2">{{ $paciente->created_at->format('d/m/Y H:i') }}</div>
                <div class="detail-label mb-1">Última actualización</div>
                <div style="font-size:13px" class="text-muted">{{ $paciente->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
