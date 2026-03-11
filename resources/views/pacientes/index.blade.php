@extends('layouts.app')

@section('title', 'Pacientes')
@section('page-title', 'Gestión de Pacientes')

@section('topbar-actions')
    <a href="{{ route('pacientes.create') }}" class="btn btn-sm" style="background:#0d9488;color:#fff">
        <i class="bi bi-person-plus-fill me-1"></i> Registrar Paciente
    </a>
@endsection

@section('content')

<!-- Barra de búsqueda -->
<div class="card content-card mb-3">
    <div class="card-body py-3 px-4">
        <div class="row g-2 align-items-center">

            <!-- AJAX: búsqueda en tiempo real -->
            <div class="col-md-5">
                <label class="form-label mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#6c757d">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Búsqueda en tiempo real (AJAX)
                </label>
                <div class="position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                        <i class="bi bi-search" style="font-size:13px"></i>
                    </span>
                    <input type="text" id="ajax-search" class="form-control ps-5"
                           placeholder="Nombre, documento, EPS…" autocomplete="off">
                    <div id="search-dropdown"></div>
                </div>
            </div>

            <!-- Búsqueda server-side -->
            <div class="col-md-5">
                <label class="form-label mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#6c757d">
                    Filtrar listado
                </label>
                <form method="GET" action="{{ route('pacientes.index') }}" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control"
                           value="{{ $busqueda }}" placeholder="Buscar y filtrar lista…">
                    <button type="submit" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="bi bi-funnel"></i>
                    </button>
                    @if($busqueda)
                        <a href="{{ route('pacientes.index') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="col-md-2 d-flex align-items-end justify-content-end">
                <span class="text-muted" style="font-size:13px">
                    <strong>{{ $pacientes->total() }}</strong> resultado(s)
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de pacientes -->
<div class="card content-card">
    <div class="card-header">
        <i class="bi bi-table me-2 text-muted"></i>Listado de Pacientes
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Paciente</th>
                        <th>Documento</th>
                        <th>Celular</th>
                        <th>EPS</th>
                        <th>Empresa</th>
                        <th>Examen</th>
                        <th>Fecha Examen</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                    <tr>
                        <td class="ps-4 text-muted" style="font-size:13px">{{ $paciente->id }}</td>
                        <td>
                            <div class="fw-medium">{{ $paciente->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:12px">
                                Nac. {{ $paciente->fecha_nacimiento->format('d/m/Y') }} · {{ $paciente->edad }} años
                            </div>
                        </td>
                        <td>
                            <div style="font-size:11px" class="text-muted">{{ $paciente->tipo_documento }}</div>
                            <div>{{ $paciente->numero_documento }}</div>
                        </td>
                        <td>{{ $paciente->celular }}</td>
                        <td>{{ $paciente->eps }}</td>
                        <td>{{ $paciente->empresa->nombre ?? '—' }}</td>
                        <td>{{ $paciente->tipoExamen->nombre ?? '—' }}</td>
                        <td>
                            <div class="fw-medium">{{ $paciente->fecha_examen->format('d/m/Y') }}</div>
                            @php $dias = now()->diffInDays($paciente->fecha_examen, false); @endphp
                            <div class="text-muted" style="font-size:11px">
                                {{ $dias >= 0 ? "En {$dias} día(s)" : 'Pasado' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-estado-{{ $paciente->estado }}">
                                {{ ucfirst($paciente->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('pacientes.show', $paciente) }}"
                                   class="btn btn-sm btn-outline-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('pacientes.edit', $paciente) }}"
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('pacientes.destroy', $paciente) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ addslashes($paciente->nombre_completo) }}?\nEsta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <h6 class="fw-semibold">Sin pacientes registrados</h6>
                            <p class="mb-3">Comience registrando el primer paciente.</p>
                            <a href="{{ route('pacientes.create') }}" class="btn btn-sm" style="background:#0d9488;color:#fff">
                                <i class="bi bi-person-plus me-1"></i>Registrar paciente
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pacientes->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-center py-3">
        {{ $pacientes->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
(function () {
    const input    = document.getElementById('ajax-search');
    const dropdown = document.getElementById('search-dropdown');
    const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
    let   timer;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) {
            dropdown.style.display = 'none';
            dropdown.innerHTML = '';
            return;
        }

        timer = setTimeout(() => {
            fetch(`/pacientes-buscar?q=${encodeURIComponent(q)}`, {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                dropdown.innerHTML = '';
                if (!data.length) {
                    dropdown.innerHTML = `<div class="search-result-item text-muted">
                        <i class="bi bi-search me-2"></i>Sin resultados para "<strong>${q}</strong>"
                    </div>`;
                } else {
                    data.forEach(p => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item';
                        div.innerHTML = `
                            <div class="search-result-name">${p.nombre_completo}</div>
                            <div class="search-result-meta">
                                ${p.tipo_documento} ${p.numero_documento} &nbsp;·&nbsp;
                                ${p.empresa} &nbsp;·&nbsp; ${p.tipo_examen} &nbsp;·&nbsp;
                                <span class="badge rounded-pill ${p.estado === 'activo' ? 'text-success' : 'text-secondary'}" style="font-size:10px">${p.estado}</span>
                            </div>`;
                        div.addEventListener('click', () => { window.location = '/pacientes/' + p.id; });
                        dropdown.appendChild(div);
                    });
                }
                dropdown.style.display = 'block';
            })
            .catch(() => { dropdown.style.display = 'none'; });
        }, 350);
    });

    document.addEventListener('click', e => {
        if (!input.contains(e.target)) dropdown.style.display = 'none';
    });
    input.addEventListener('focus', () => {
        if (dropdown.innerHTML) dropdown.style.display = 'block';
    });
})();
</script>
@endpush
