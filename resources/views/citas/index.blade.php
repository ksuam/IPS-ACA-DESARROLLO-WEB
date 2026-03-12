@extends('layouts.app')

@section('title', 'Citas')
@section('page-title', 'Gestión de Citas')

@section('topbar-actions')
    <a href="{{ route('citas.calendario') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-calendar3 me-1"></i>Ver Calendario
    </a>
    <a href="{{ route('citas.create') }}" class="btn btn-sm" style="background:#0d9488;color:#fff">
        <i class="bi bi-plus-circle me-1"></i>Nueva Cita
    </a>
@endsection

@section('content')


<div class="card content-card mb-3">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('citas.index') }}" id="filtrosForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase">Fecha desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm"
                           value="{{ $filtros['fecha_desde'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase">Fecha hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                           value="{{ $filtros['fecha_hasta'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase">Empresa</label>
                    <select name="empresa_id" class="form-select form-select-sm">
                        <option value="">Todas las empresas</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}"
                                {{ ($filtros['empresa_id'] ?? '') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach(['pendiente','confirmada','cancelada','completada'] as $est)
                            <option value="{{ $est }}"
                                {{ ($filtros['estado'] ?? '') === $est ? 'selected' : '' }}>
                                {{ ucfirst($est) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('citas.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card content-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-calendar-check me-2 text-muted"></i>Citas registradas
            <span class="text-muted fw-normal ms-1" style="font-size:14px">({{ $citas->total() }} en total)</span>
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Empresa</th>
                        <th>Tipo Examen</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                    <tr id="fila-cita-{{ $cita->id }}">
                        <td class="ps-4">
                            <div class="fw-medium">{{ $cita->fecha->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $cita->fecha->translatedFormat('l') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-clock me-1"></i>{{ substr($cita->hora, 0, 5) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $cita->paciente->nombre_completo ?? '—' }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $cita->paciente->numero_documento ?? '' }}</div>
                        </td>
                        <td>{{ $cita->empresa->nombre ?? '—' }}</td>
                        <td>{{ $cita->tipoExamen->nombre ?? '—' }}</td>
                        <td>
                            {{-- Selector AJAX de estado --}}
                            <select class="form-select form-select-sm selector-estado"
                                    data-cita-id="{{ $cita->id }}"
                                    style="width:140px;font-size:13px">
                                @foreach(['pendiente','confirmada','cancelada','completada'] as $est)
                                    <option value="{{ $est }}" {{ $cita->estado === $est ? 'selected' : '' }}>
                                        {{ ucfirst($est) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td style="max-width:160px">
                            <span class="text-muted" style="font-size:12px">
                                {{ $cita->observaciones ? Str::limit($cita->observaciones, 40) : '—' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('citas.show', $cita) }}"
                                   class="btn btn-sm btn-outline-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('citas.edit', $cita) }}"
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('citas.destroy', $cita) }}"
                                      onsubmit="return confirm('¿Eliminar esta cita?\nEsta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
                            <h6 class="fw-semibold">No hay citas registradas</h6>
                            <a href="{{ route('citas.create') }}" class="btn btn-sm mt-2" style="background:#0d9488;color:#fff">
                                <i class="bi bi-plus-circle me-1"></i>Agendar primera cita
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($citas->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-center py-3">
        {{ $citas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.selector-estado').forEach(select => {
    select.addEventListener('change', function () {
        const citaId = this.dataset.citaId;
        const estado = this.value;
        const sel    = this;

        sel.disabled = true;

        fetch(`/citas/${citaId}/estado`, {
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
                
                const fila = document.getElementById('fila-cita-' + citaId);
                fila.style.transition = 'background .3s';
                fila.style.background = '#d1fae5';
                setTimeout(() => { fila.style.background = ''; }, 1000);
            }
        })
        .catch(() => alert('Error al cambiar el estado. Intente de nuevo.'))
        .finally(() => { sel.disabled = false; });
    });
});
</script>
@endpush
