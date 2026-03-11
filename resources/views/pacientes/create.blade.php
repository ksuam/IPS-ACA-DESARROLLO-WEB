@extends('layouts.app')

@php $editMode = isset($paciente); @endphp

@section('title', $editMode ? 'Editar Paciente' : 'Registrar Paciente')
@section('page-title', $editMode ? 'Editar Paciente' : 'Registrar Paciente')

@section('topbar-actions')
    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
@endsection

@section('content')

<div class="card content-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-{{ $editMode ? 'pencil-square' : 'person-plus-fill' }} me-2 text-muted"></i>
            {{ $editMode ? 'Actualizar datos del paciente' : 'Nuevo registro de paciente' }}
        </span>
        @if($editMode)
            <span class="badge rounded-pill badge-estado-{{ $paciente->estado }}">{{ ucfirst($paciente->estado) }}</span>
        @endif
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ $editMode ? route('pacientes.update', $paciente) : route('pacientes.store') }}"
              id="formPaciente" novalidate>
            @csrf
            @if($editMode) @method('PUT') @endif

            <!-- SECCIÓN 1: DATOS PERSONALES -->
            <div class="form-section-title"><i class="bi bi-person me-2"></i>Datos Personales</div>

            <div class="row g-3 mb-3">

                {{-- Nombre completo --}}
                <div class="col-12">
                    <label for="nombre_completo" class="form-label">
                        Nombre completo <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="nombre_completo" name="nombre_completo"
                           class="form-control {{ $errors->has('nombre_completo') ? 'is-invalid' : '' }}"
                           value="{{ old('nombre_completo', $paciente->nombre_completo ?? '') }}"
                           placeholder="Ej. Andrés Felipe García Ruiz"
                           maxlength="150" required>
                    @error('nombre_completo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tipo documento --}}
                <div class="col-md-4">
                    <label for="tipo_documento" class="form-label">
                        Tipo de documento <span class="text-danger">*</span>
                    </label>
                    <select id="tipo_documento" name="tipo_documento"
                            class="form-select {{ $errors->has('tipo_documento') ? 'is-invalid' : '' }}" required>
                        <option value="">Seleccione…</option>
                        @foreach($tiposDocumento as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('tipo_documento', $paciente->tipo_documento ?? '') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_documento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Número documento --}}
                <div class="col-md-4">
                    <label for="numero_documento" class="form-label">
                        Número de documento <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="numero_documento" name="numero_documento"
                           class="form-control {{ $errors->has('numero_documento') ? 'is-invalid' : '' }}"
                           value="{{ old('numero_documento', $paciente->numero_documento ?? '') }}"
                           placeholder="Ej. 1020304050"
                           maxlength="30" required
                           data-paciente-id="{{ $paciente->id ?? '' }}">
                    <div id="doc-feedback" class="doc-feedback mt-1"></div>
                    @error('numero_documento')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Fecha nacimiento --}}
                <div class="col-md-4">
                    <label for="fecha_nacimiento" class="form-label">
                        Fecha de nacimiento <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           class="form-control {{ $errors->has('fecha_nacimiento') ? 'is-invalid' : '' }}"
                           value="{{ old('fecha_nacimiento', isset($paciente) ? $paciente->fecha_nacimiento->format('Y-m-d') : '') }}"
                           max="{{ date('Y-m-d') }}" required>
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Edad --}}
                <div class="col-md-3">
                    <label for="edad" class="form-label">Edad <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" id="edad" name="edad"
                               class="form-control {{ $errors->has('edad') ? 'is-invalid' : '' }}"
                               value="{{ old('edad', $paciente->edad ?? '') }}"
                               min="0" max="120" placeholder="Auto-calculada"
                               readonly style="background:#f8f9fa" required>
                        <span class="input-group-text text-muted" style="font-size:13px">años</span>
                    </div>
                    <div class="form-text">Calculada automáticamente.</div>
                    @error('edad')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="col-md-3">
                    <label for="telefono" class="form-label">Teléfono fijo</label>
                    <input type="tel" id="telefono" name="telefono"
                           class="form-control"
                           value="{{ old('telefono', $paciente->telefono ?? '') }}"
                           placeholder="Ej. 6012345678" maxlength="20">
                </div>

                {{-- Celular --}}
                <div class="col-md-3">
                    <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                    <input type="tel" id="celular" name="celular"
                           class="form-control {{ $errors->has('celular') ? 'is-invalid' : '' }}"
                           value="{{ old('celular', $paciente->celular ?? '') }}"
                           placeholder="Ej. 3101234567" maxlength="20" required>
                    @error('celular')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EPS --}}
                <div class="col-md-3">
                    <label for="eps" class="form-label">EPS <span class="text-danger">*</span></label>
                    <input type="text" id="eps" name="eps"
                           class="form-control {{ $errors->has('eps') ? 'is-invalid' : '' }}"
                           value="{{ old('eps', $paciente->eps ?? '') }}"
                           placeholder="Ej. Compensar" maxlength="100" required>
                    @error('eps')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div class="col-12">
                    <label for="direccion" class="form-label">
                        Dirección <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="direccion" name="direccion"
                           class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }}"
                           value="{{ old('direccion', $paciente->direccion ?? '') }}"
                           placeholder="Ej. Calle 45 # 23-10, Bogotá" maxlength="200" required>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- SECCIÓN 2: CONTACTO ADICIONAL -->
            <div class="form-section-title mt-4"><i class="bi bi-telephone me-2"></i>Contacto Adicional</div>

            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label for="contacto_nombre" class="form-label">
                        Nombre del contacto <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="contacto_nombre" name="contacto_nombre"
                           class="form-control {{ $errors->has('contacto_nombre') ? 'is-invalid' : '' }}"
                           value="{{ old('contacto_nombre', $paciente->contacto_nombre ?? '') }}"
                           maxlength="150" required>
                    @error('contacto_nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="contacto_parentesco" class="form-label">
                        Parentesco <span class="text-danger">*</span>
                    </label>
                    <select id="contacto_parentesco" name="contacto_parentesco"
                            class="form-select {{ $errors->has('contacto_parentesco') ? 'is-invalid' : '' }}" required>
                        <option value="">Seleccione…</option>
                        @foreach(['Madre','Padre','Cónyuge','Hermano/a','Hijo/a','Otro'] as $par)
                            <option value="{{ $par }}"
                                {{ old('contacto_parentesco', $paciente->contacto_parentesco ?? '') === $par ? 'selected' : '' }}>
                                {{ $par }}
                            </option>
                        @endforeach
                    </select>
                    @error('contacto_parentesco')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="contacto_telefono" class="form-label">
                        Teléfono de contacto <span class="text-danger">*</span>
                    </label>
                    <input type="tel" id="contacto_telefono" name="contacto_telefono"
                           class="form-control {{ $errors->has('contacto_telefono') ? 'is-invalid' : '' }}"
                           value="{{ old('contacto_telefono', $paciente->contacto_telefono ?? '') }}"
                           maxlength="20" required>
                    @error('contacto_telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- SECCIÓN 3: EXAMEN LABORAL -->
            <div class="form-section-title mt-4"><i class="bi bi-clipboard2-pulse me-2"></i>Examen Laboral</div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="empresa_id" class="form-label">
                        Empresa solicitante <span class="text-danger">*</span>
                    </label>
                    <select id="empresa_id" name="empresa_id"
                            class="form-select {{ $errors->has('empresa_id') ? 'is-invalid' : '' }}" required>
                        <option value="">Seleccione empresa…</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}"
                                {{ old('empresa_id', $paciente->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tipo_examen_id" class="form-label">
                        Tipo de examen <span class="text-danger">*</span>
                    </label>
                    <select id="tipo_examen_id" name="tipo_examen_id"
                            class="form-select {{ $errors->has('tipo_examen_id') ? 'is-invalid' : '' }}" required>
                        <option value="">Seleccione examen…</option>
                        @foreach($tiposExamen as $examen)
                            <option value="{{ $examen->id }}"
                                {{ old('tipo_examen_id', $paciente->tipo_examen_id ?? '') == $examen->id ? 'selected' : '' }}>
                                {{ $examen->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_examen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="fecha_examen" class="form-label">
                        Fecha del examen <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="fecha_examen" name="fecha_examen"
                           class="form-control {{ $errors->has('fecha_examen') ? 'is-invalid' : '' }}"
                           value="{{ old('fecha_examen', isset($paciente) ? $paciente->fecha_examen->format('Y-m-d') : '') }}"
                           min="{{ date('Y-m-d') }}" required>
                    @error('fecha_examen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($editMode)
                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-select">
                        @foreach(['activo','cancelado','completado'] as $est)
                            <option value="{{ $est }}"
                                {{ old('estado', $paciente->estado) === $est ? 'selected' : '' }}>
                                {{ ucfirst($est) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <!-- ── Botones ── -->
            <hr class="mt-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn" style="background:#0d9488;color:#fff" id="submitBtn">
                    <i class="bi bi-{{ $editMode ? 'floppy' : 'person-check-fill' }} me-1"></i>
                    {{ $editMode ? 'Actualizar Paciente' : 'Registrar Paciente' }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/**
 * Validaciones JavaScript del lado del cliente
 * + AJAX para verificar unicidad del número de documento
 */
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // ── 1. Auto-calcular edad desde fecha de nacimiento ────
    document.getElementById('fecha_nacimiento').addEventListener('change', function () {
        if (!this.value) return;
        const nacimiento = new Date(this.value);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const m = hoy.getMonth() - nacimiento.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) edad--;
        document.getElementById('edad').value = edad >= 0 ? edad : 0;
    });

    // ── 2. AJAX: verificar documento único en tiempo real ──
    const docInput   = document.getElementById('numero_documento');
    const docFeedback = document.getElementById('doc-feedback');
    const pacienteId  = docInput.dataset.pacienteId || '';
    let   docTimer;

    docInput.addEventListener('input', function () {
        clearTimeout(docTimer);
        const val = this.value.trim();
        docFeedback.textContent = '';
        docFeedback.className = 'doc-feedback mt-1';
        docInput.classList.remove('is-valid', 'is-invalid');

        if (val.length < 4) return;

        docFeedback.textContent = '⏳ Verificando disponibilidad…';
        docFeedback.className = 'doc-feedback mt-1 checking';

        docTimer = setTimeout(() => {
            const url = `/pacientes-validar-documento?documento=${encodeURIComponent(val)}&id=${pacienteId}`;
            fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
                .then(r => r.json())
                .then(data => {
                    if (data.disponible) {
                        docFeedback.textContent = '✔ Número de documento disponible';
                        docFeedback.className = 'doc-feedback mt-1 ok';
                        docInput.classList.add('is-valid');
                    } else {
                        docFeedback.textContent = '✖ Este número de documento ya está registrado';
                        docFeedback.className = 'doc-feedback mt-1 taken';
                        docInput.classList.add('is-invalid');
                    }
                })
                .catch(() => { docFeedback.textContent = ''; });
        }, 500);
    });

    document.getElementById('formPaciente').addEventListener('submit', function (e) {
        let hasError = false;

        const required = [
            { id: 'nombre_completo',     msg: 'El nombre completo es obligatorio.' },
            { id: 'tipo_documento',      msg: 'Seleccione el tipo de documento.' },
            { id: 'numero_documento',    msg: 'Ingrese el número de documento.' },
            { id: 'fecha_nacimiento',    msg: 'Ingrese la fecha de nacimiento.' },
            { id: 'celular',             msg: 'El celular es obligatorio.' },
            { id: 'eps',                 msg: 'La EPS es obligatoria.' },
            { id: 'direccion',           msg: 'La dirección es obligatoria.' },
            { id: 'contacto_nombre',     msg: 'El nombre del contacto es obligatorio.' },
            { id: 'contacto_parentesco', msg: 'Seleccione el parentesco.' },
            { id: 'contacto_telefono',   msg: 'El teléfono de contacto es obligatorio.' },
            { id: 'empresa_id',          msg: 'Seleccione la empresa.' },
            { id: 'tipo_examen_id',      msg: 'Seleccione el tipo de examen.' },
            { id: 'fecha_examen',        msg: 'Seleccione la fecha del examen.' },
        ];

        required.forEach(({ id, msg }) => {
            const field = document.getElementById(id);
            if (!field) return;

            // Limpiar error JS previo
            field.classList.remove('is-invalid');
            const prevFb = field.parentElement.querySelector('.js-invalid-feedback');
            if (prevFb) prevFb.remove();

            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                const fb = document.createElement('div');
                fb.className = 'invalid-feedback d-block js-invalid-feedback';
                fb.textContent = msg;
                field.after(fb);
                hasError = true;
            }
        });

        // Celular: solo dígitos
        const celular = document.getElementById('celular');
        if (celular.value && !/^\d{7,15}$/.test(celular.value.trim())) {
            celular.classList.add('is-invalid');
            const prevFb = celular.parentElement.querySelector('.js-invalid-feedback');
            if (!prevFb) {
                const fb = document.createElement('div');
                fb.className = 'invalid-feedback d-block js-invalid-feedback';
                fb.textContent = 'Ingrese un número de celular válido (solo dígitos, 7-15 caracteres).';
                celular.after(fb);
            }
            hasError = true;
        }

        // Fecha examen no puede ser pasada
        const fechaExamen = document.getElementById('fecha_examen');
        if (fechaExamen.value) {
            const hoy = new Date(); hoy.setHours(0,0,0,0);
            const fe  = new Date(fechaExamen.value + 'T00:00:00');
            if (fe < hoy) {
                fechaExamen.classList.add('is-invalid');
                const prevFb = fechaExamen.parentElement.querySelector('.js-invalid-feedback');
                if (!prevFb) {
                    const fb = document.createElement('div');
                    fb.className = 'invalid-feedback d-block js-invalid-feedback';
                    fb.textContent = 'La fecha del examen no puede ser anterior a hoy.';
                    fechaExamen.after(fb);
                }
                hasError = true;
            }
        }

        // Documento duplicado (AJAX ya marcó el campo)
        if (docInput.classList.contains('is-invalid')) {
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            // Scroll al primer error
            document.querySelector('.is-invalid')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
})();
</script>
@endpush
