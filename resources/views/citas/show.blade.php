@extends('layouts.app')

@section('title', 'Detalle de Cita')
@section('page-title', 'Detalle de Cita')

@section('topbar-actions')
    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil me-1"></i>Editar
    </a>
    <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
@endsection

@section('content')

<div class="row g-4">
    <div class="col-lg-8">

        <!-- Datos principales -->
        <div class="card content-card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-calendar-event me-2 text-muted"></i>Información de la Cita</span>
                <span class="badge {{ $cita->badge_class }} rounded-pill px-3">{{ ucfirst($cita->estado) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="detail-label">Fecha</div>
                        <div class="detail-value fw-semibold">{{ $cita->fecha->format('d/m/Y') }}</div>
                        <div class="text-muted" style="font-size:12px">{{ $cita->fecha->translatedFormat('l') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Hora</div>
                        <div class="detail-value fw-semibold">
                            <i class="bi bi-clock me-1 text-muted"></i>{{ substr($cita->hora, 0, 5) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value">
                            <span class="badge {{ $cita->badge_class }} rounded-pill">{{ ucfirst($cita->estado) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Empresa solicitante</div>
                        <div class="detail-value">{{ $cita->empresa->nombre ?? '—' }}</div>
                        <div class="text-muted" style="font-size:12px">NIT: {{ $cita->empresa->nit ?? '' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tipo de examen</div>
                        <div class="detail-value">{{ $cita->tipoExamen->nombre ?? '—' }}</div>
                    </div>
                    @if($cita->observaciones)
                    <div class="col-12">
                        <div class="detail-label">Observaciones</div>
                        <div class="detail-value">{{ $cita->observaciones }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Datos del paciente -->
        <div class="card content-card">
            <div class="card-header"><i class="bi bi-person-circle me-2 text-muted"></i>Datos del Paciente</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-label">Nombre completo</div>
                        <div class="detail-value fw-medium">{{ $cita->paciente->nombre_completo ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Documento</div>
                        <div class="detail-value">
                            {{ $cita->paciente->tipo_documento ?? '' }} {{ $cita->paciente->numero_documento ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Celular</div>
                        <div class="detail-value">{{ $cita->paciente->celular ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">EPS</div>
                        <div class="detail-value">{{ $cita->paciente->eps ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-label">Edad</div>
                        <div class="detail-value">{{ $cita->paciente->edad ?? '—' }} años</div>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('pacientes.show', $cita->paciente_id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Ver perfil completo del paciente
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="col-lg-4">
        <div class="card content-card mb-3">
            <div class="card-header"><i class="bi bi-gear me-2 text-muted"></i>Acciones</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning w-100">
                    <i class="bi bi-pencil-square me-1"></i>Editar cita
                </a>
                <form method="POST" action="{{ route('citas.destroy', $cita) }}"
                      onsubmit="return confirm('¿Eliminar esta cita definitivamente?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-1"></i>Eliminar cita
                    </button>
                </form>
                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i>Volver al listado
                </a>
                <a href="{{ route('citas.calendario') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-calendar3 me-1"></i>Ver calendario
                </a>
            </div>
        </div>

        <!-- Cambio de estado rápido -->
        <div class="card content-card mb-3">
            <div class="card-header"><i class="bi bi-arrow-repeat me-2 text-muted"></i>Cambiar Estado</div>
            <div class="card-body">
                <select id="cambio-estado" class="form-select mb-2">
                    @foreach(['pendiente','confirmada','cancelada','completada'] as $est)
                        <option value="{{ $est }}" {{ $cita->estado === $est ? 'selected' : '' }}>
                            {{ ucfirst($est) }}
                        </option>
                    @endforeach
                </select>
                <button id="btn-cambiar-estado" class="btn w-100" style="background:#0d9488;color:#fff">
                    Aplicar cambio
                </button>
                <div id="estado-feedback" class="mt-2 text-center" style="font-size:13px"></div>
            </div>
        </div>

        <!-- Metadatos -->
        <div class="card content-card">
            <div class="card-body">
                <div class="detail-label mb-1">Creada</div>
                <div class="text-muted mb-2" style="font-size:13px">{{ $cita->created_at->format('d/m/Y H:i') }}</div>
                <div class="detail-label mb-1">Última actualización</div>
                <div class="text-muted" style="font-size:13px">{{ $cita->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// AJAX: cambio de estado desde la vista de detalle
document.getElementById('btn-cambiar-estado').addEventListener('click', function () {
    const estado   = document.getElementById('cambio-estado').value;
    const feedback = document.getElementById('estado-feedback');
    const btn      = this;
    const CSRF     = document.querySelector('meta[name="csrf-token"]').content;

    btn.disabled = true;
    feedback.innerHTML = '<span class="text-muted">Actualizando…</span>';

    fetch('/citas/{{ $cita->id }}/estado', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ estado }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Estado actualizado a <strong>' + estado + '</strong></span>';
            // Actualizar el badge del header
            const badge = document.querySelector('.card-header .badge');
            if (badge) {
                badge.className = 'badge rounded-pill px-3 ' + data.badge_class;
                badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            }
        }
    })
    .catch(() => {
        feedback.innerHTML = '<span class="text-danger">Error al actualizar. Intente de nuevo.</span>';
    })
    .finally(() => { btn.disabled = false; });
});
</script>
@endpush
