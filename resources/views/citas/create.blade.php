@extends('layouts.app')

@php $editMode = isset($cita); @endphp

@section('title', $editMode ? 'Editar Cita' : 'Nueva Cita')
@section('page-title', $editMode ? 'Editar Cita' : 'Agendar Nueva Cita')

@section('topbar-actions')
    <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card content-card">
            <div class="card-header">
                <i class="bi bi-calendar-plus me-2 text-muted"></i>
                {{ $editMode ? 'Modificar datos de la cita' : 'Registrar nueva cita de examen' }}
            </div>
            <div class="card-body">

                <form method="POST"
                      action="{{ $editMode ? route('citas.update', $cita) : route('citas.store') }}"
                      id="formCita" novalidate>
                    @csrf
                    @if($editMode) @method('PUT') @endif

                    <div class="form-section-title"><i class="bi bi-person me-2"></i>Paciente</div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label for="paciente_id" class="form-label">
                                Paciente <span class="text-danger">*</span>
                            </label>
                            <select id="paciente_id" name="paciente_id"
                                    class="form-select {{ $errors->has('paciente_id') ? 'is-invalid' : '' }}" required>
                                <option value="">Seleccione un paciente…</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}"
                                        {{ old('paciente_id', $cita->paciente_id ?? $paciente_preseleccionado ?? '') == $paciente->id ? 'selected' : '' }}>
                                        {{ $paciente->nombre_completo }} — {{ $paciente->numero_documento }}
                                    </option>
                                @endforeach
                            </select>
                            @error('paciente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title mt-4"><i class="bi bi-clipboard2-pulse me-2"></i>Examen</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="empresa_id" class="form-label">
                                Empresa solicitante <span class="text-danger">*</span>
                            </label>
                            <select id="empresa_id" name="empresa_id"
                                    class="form-select {{ $errors->has('empresa_id') ? 'is-invalid' : '' }}" required>
                                <option value="">Seleccione empresa…</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}"
                                        {{ old('empresa_id', $cita->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empresa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_examen_id" class="form-label">
                                Tipo de examen <span class="text-danger">*</span>
                            </label>
                            <select id="tipo_examen_id" name="tipo_examen_id"
                                    class="form-select {{ $errors->has('tipo_examen_id') ? 'is-invalid' : '' }}" required>
                                <option value="">Seleccione tipo…</option>
                                @foreach($tiposExamen as $examen)
                                    <option value="{{ $examen->id }}"
                                        {{ old('tipo_examen_id', $cita->tipo_examen_id ?? '') == $examen->id ? 'selected' : '' }}>
                                        {{ $examen->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_examen_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title mt-4"><i class="bi bi-clock me-2"></i>Fecha y Hora</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="fecha" class="form-label">
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="fecha" name="fecha"
                                   class="form-control {{ $errors->has('fecha') ? 'is-invalid' : '' }}"
                                   value="{{ old('fecha', isset($cita) ? $cita->fecha->format('Y-m-d') : '') }}"
                                   {{ !$editMode ? 'min='.date('Y-m-d') : '' }} required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="hora" class="form-label">
                                Hora <span class="text-danger">*</span>
                            </label>
                            <input type="time" id="hora" name="hora"
                                   class="form-control {{ $errors->has('hora') ? 'is-invalid' : '' }}"
                                   value="{{ old('hora', isset($cita) ? substr($cita->hora, 0, 5) : '') }}"
                                   min="06:00" max="18:00" required>
                            @error('hora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- AJAX: feedback de disponibilidad --}}
                            <div id="disponibilidad-feedback" class="mt-1" style="font-size:12px"></div>
                        </div>

                        <div class="col-md-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                @foreach($estados as $est)
                                    <option value="{{ $est }}"
                                        {{ old('estado', $cita->estado ?? 'pendiente') === $est ? 'selected' : '' }}>
                                        {{ ucfirst($est) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea id="observaciones" name="observaciones"
                                      class="form-control" rows="3" maxlength="500"
                                      placeholder="Indicaciones especiales, documentos requeridos, etc.">{{ old('observaciones', $cita->observaciones ?? '') }}</textarea>
                            <div class="form-text">Máximo 500 caracteres.</div>
                        </div>
                    </div>

                    <hr class="mt-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn" style="background:#0d9488;color:#fff" id="submitBtn">
                            <i class="bi bi-{{ $editMode ? 'floppy' : 'calendar-check' }} me-1"></i>
                            {{ $editMode ? 'Guardar cambios' : 'Agendar cita' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
    const citaId  = '{{ $cita->id ?? '' }}';
    const fechaEl = document.getElementById('fecha');
    const horaEl  = document.getElementById('hora');
    const fb      = document.getElementById('disponibilidad-feedback');
    let   timer;

    // AJAX: verificar disponibilidad de horario 
    function verificarDisponibilidad() {
        clearTimeout(timer);
        const fecha = fechaEl.value;
        const hora  = horaEl.value;

        if (!fecha || !hora) { fb.innerHTML = ''; return; }

        fb.innerHTML = '<span class="text-muted">⏳ Verificando horario…</span>';

        timer = setTimeout(() => {
            fetch(`/citas-disponibilidad?fecha=${fecha}&hora=${hora}&id=${citaId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            })
            .then(r => r.json())
            .then(data => {
                if (data.disponible) {
                    fb.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Horario disponible</span>';
                    horaEl.classList.remove('is-invalid');
                    horaEl.classList.add('is-valid');
                } else {
                    fb.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>${data.mensaje}</span>`;
                    horaEl.classList.add('is-invalid');
                    horaEl.classList.remove('is-valid');
                }
            })
            .catch(() => { fb.innerHTML = ''; });
        }, 600);
    }

    fechaEl.addEventListener('change', verificarDisponibilidad);
    horaEl.addEventListener('change', verificarDisponibilidad);

    // Verificar al cargar si ya hay fecha y hora (modo edición)
    if (fechaEl.value && horaEl.value) verificarDisponibilidad();

    // Validación completa al enviar
    document.getElementById('formCita').addEventListener('submit', function (e) {
        let hasError = false;

        const required = [
            { id: 'paciente_id',    msg: 'Seleccione un paciente.' },
            { id: 'empresa_id',     msg: 'Seleccione la empresa.' },
            { id: 'tipo_examen_id', msg: 'Seleccione el tipo de examen.' },
            { id: 'fecha',          msg: 'La fecha es obligatoria.' },
            { id: 'hora',           msg: 'La hora es obligatoria.' },
        ];

        required.forEach(({ id, msg }) => {
            const field = document.getElementById(id);
            field.classList.remove('is-invalid');
            const prev = field.parentElement.querySelector('.js-invalid-feedback');
            if (prev) prev.remove();

            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                const div = document.createElement('div');
                div.className = 'invalid-feedback d-block js-invalid-feedback';
                div.textContent = msg;
                field.after(div);
                hasError = true;
            }
        });

        // Bloquear si el horario está ocupado
        if (horaEl.classList.contains('is-invalid')) {
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            document.querySelector('.is-invalid')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
})();
</script>
@endpush
