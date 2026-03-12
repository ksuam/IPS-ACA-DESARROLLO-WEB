@extends('layouts.app')

@section('title', 'Calendario de Citas')
@section('page-title', 'Calendario de Citas')

@section('topbar-actions')
    <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-list-ul me-1"></i>Ver listado
    </a>
    <a href="{{ route('citas.create') }}" class="btn btn-sm" style="background:#0d9488;color:#fff">
        <i class="bi bi-plus-circle me-1"></i>Nueva Cita
    </a>
@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
    #calendario-container { background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 8px rgba(0,0,0,.06); }
    .fc .fc-toolbar-title { font-size:18px; font-weight:600; color:#1a2332; }
    .fc .fc-button-primary { background:#0d9488 !important; border-color:#0d9488 !important; }
    .fc .fc-button-primary:hover { background:#0f766e !important; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { background:#0f766e !important; }
    .fc-event { cursor:pointer; border:none !important; padding:2px 5px; font-size:12px; }
    .fc-daygrid-event-dot { display:none; }

    /* Modal de detalle */
    .modal-estado-badge { font-size:14px; padding:5px 14px; border-radius:20px; }
</style>
@endpush

@section('content')

<!-- Leyenda de colores -->
<div class="d-flex gap-3 mb-3 flex-wrap">
    @foreach([
        ['color'=>'#f59e0b','label'=>'Pendiente'],
        ['color'=>'#10b981','label'=>'Confirmada'],
        ['color'=>'#ef4444','label'=>'Cancelada'],
        ['color'=>'#3b82f6','label'=>'Completada'],
    ] as $item)
    <span class="d-flex align-items-center gap-2" style="font-size:13px">
        <span style="width:14px;height:14px;border-radius:3px;background:{{ $item['color'] }};display:inline-block"></span>
        {{ $item['label'] }}
    </span>
    @endforeach
</div>

<!-- Calendario -->
<div id="calendario-container">
    <div id="calendario"></div>
</div>

<!-- Modal de detalle al hacer clic en evento -->
<div class="modal fade" id="modalCita" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold" id="modal-paciente">—</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="mb-3">
                    <span id="modal-estado-badge" class="badge rounded-pill modal-estado-badge">—</span>
                </div>
                <div class="row g-2" style="font-size:14px">
                    <div class="col-6">
                        <div class="detail-label">Hora</div>
                        <div id="modal-hora" class="fw-medium">—</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Tipo de examen</div>
                        <div id="modal-examen">—</div>
                    </div>
                    <div class="col-12 mt-1">
                        <div class="detail-label">Empresa</div>
                        <div id="modal-empresa">—</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a id="modal-link-detalle" href="#" class="btn btn-sm" style="background:#0d9488;color:#fff">
                    <i class="bi bi-eye me-1"></i>Ver detalle completo
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
    const modal  = new bootstrap.Modal(document.getElementById('modalCita'));

    const calendario = new FullCalendar.Calendar(document.getElementById('calendario'), {
        locale:          'es',
        initialView:     'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listWeek',
        },
        buttonText: {
            today:    'Hoy',
            month:    'Mes',
            week:     'Semana',
            list:     'Lista',
        },
        height:       'auto',
        nowIndicator: true,

        events: function (info, successCallback, failureCallback) {
            fetch(`/citas-eventos?start=${info.startStr}&end=${info.endStr}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            })
            .then(r => r.json())
            .then(data => successCallback(data))
            .catch(() => failureCallback());
        },

        // Clic en un evento: abrir modal con detalle
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const props = info.event.extendedProps;

            document.getElementById('modal-paciente').textContent = info.event.title;
            document.getElementById('modal-hora').textContent     = props.hora;
            document.getElementById('modal-examen').textContent   = props.tipo_examen;
            document.getElementById('modal-empresa').textContent  = props.empresa;
            document.getElementById('modal-link-detalle').href    = props.url_detalle;

            const badge = document.getElementById('modal-estado-badge');
            badge.textContent = props.estado.charAt(0).toUpperCase() + props.estado.slice(1);
            const colores = {
                pendiente:  'bg-warning text-dark',
                confirmada: 'bg-success text-white',
                cancelada:  'bg-danger text-white',
                completada: 'bg-primary text-white',
            };
            badge.className = 'badge rounded-pill modal-estado-badge ' + (colores[props.estado] || 'bg-secondary');

            modal.show();
        },

        dateClick: function (info) {
            window.location = '/citas/create';
        },
    });

    calendario.render();
});
</script>
@endpush
