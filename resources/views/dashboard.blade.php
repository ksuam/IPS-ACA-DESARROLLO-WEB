@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('topbar-actions')
    <a href="{{ route('pacientes.create') }}" class="btn btn-sm" style="background:#0d9488;color:#fff">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo paciente
    </a>
@endsection

@section('content')
@php
    $total     = \App\Models\Paciente::count();
    $activos   = \App\Models\Paciente::where('estado','activo')->count();
    $hoy       = \App\Models\Paciente::whereDate('fecha_examen', today())->count();
    $semana    = \App\Models\Paciente::whereBetween('fecha_examen',[today(), today()->addDays(7)])->count();
    $recientes = \App\Models\Paciente::with(['empresa','tipoExamen'])->orderByDesc('created_at')->limit(8)->get();
@endphp

<!-- Tarjetas de estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e0f2fe;color:#0369a1">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-label">Total pacientes</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#d1fae5;color:#065f46">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $activos }}</div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fef3c7;color:#92400e">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $hoy }}</div>
                    <div class="stat-label">Exámenes hoy</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ede9fe;color:#5b21b6">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $semana }}</div>
                    <div class="stat-label">Próximos 7 días</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de registros recientes -->
<div class="card content-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clock-history me-2 text-muted"></i>Registros recientes</span>
        <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary btn-sm">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Documento</th>
                        <th>Empresa</th>
                        <th>Tipo Examen</th>
                        <th>Fecha Examen</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recientes as $p)
                    <tr>
                        <td>
                            <div class="fw-medium">{{ $p->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:12px">{{ $p->celular }}</div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:11px">{{ $p->tipo_documento }}</span><br>
                            {{ $p->numero_documento }}
                        </td>
                        <td>{{ $p->empresa->nombre ?? '—' }}</td>
                        <td>{{ $p->tipoExamen->nombre ?? '—' }}</td>
                        <td>{{ $p->fecha_examen->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge rounded-pill badge-estado-{{ $p->estado }}">
                                {{ ucfirst($p->estado) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No hay pacientes registrados aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
