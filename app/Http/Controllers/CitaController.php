<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Empresa;
use App\Models\TipoExamen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CitaController extends Controller
{
    // ── Catálogos compartidos ────────────────────────────────

    private function catalogs(): array
    {
        return [
            'pacientes'   => Paciente::orderBy('nombre_completo')->get(['id', 'nombre_completo', 'numero_documento']),
            'empresas'    => Empresa::orderBy('nombre')->get(),
            'tiposExamen' => TipoExamen::orderBy('nombre')->get(),
            'estados'     => ['pendiente', 'confirmada', 'cancelada', 'completada'],
        ];
    }

    // ── Reglas de validación ─────────────────────────────────

    private function rules(int $exceptId = 0): array
    {
        return [
            'paciente_id'    => 'required|exists:pacientes,id',
            'empresa_id'     => 'required|exists:empresas,id',
            'tipo_examen_id' => 'required|exists:tipos_examen,id',
            'fecha'          => 'required|date|after_or_equal:today',
            'hora'           => 'required|date_format:H:i',
            'estado'         => 'required|in:pendiente,confirmada,cancelada,completada',
            'observaciones'  => 'nullable|string|max:500',
        ];
    }

    private function messages(): array
    {
        return [
            'paciente_id.required'    => 'Seleccione un paciente.',
            'paciente_id.exists'      => 'El paciente seleccionado no es válido.',
            'empresa_id.required'     => 'Seleccione la empresa.',
            'tipo_examen_id.required' => 'Seleccione el tipo de examen.',
            'fecha.required'          => 'La fecha es obligatoria.',
            'fecha.after_or_equal'    => 'La fecha no puede ser anterior a hoy.',
            'hora.required'           => 'La hora es obligatoria.',
            'hora.date_format'        => 'La hora debe tener formato HH:MM.',
            'estado.required'         => 'Seleccione el estado de la cita.',
        ];
    }

    // ── INDEX ────────────────────────────────────────────────

    public function index(Request $request)
    {
        $filtros = $request->only(['fecha_desde', 'fecha_hasta', 'empresa_id', 'estado']);

        $citas = Cita::with(['paciente', 'empresa', 'tipoExamen'])
            ->filtrar($filtros)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->paginate(12)
            ->withQueryString();

        $empresas = Empresa::orderBy('nombre')->get();

        return view('citas.index', compact('citas', 'filtros', 'empresas'));
    }

    // ── CALENDARIO (vista agenda) ────────────────────────────

    public function calendario(Request $request)
    {
        return view('citas.calendario');
    }

    // ── AJAX: eventos para el calendario ────────────────────

    public function eventos(Request $request)
    {
        $desde = $request->input('start', now()->startOfMonth()->toDateString());
        $hasta = $request->input('end',   now()->endOfMonth()->toDateString());

        $citas = Cita::with(['paciente', 'empresa', 'tipoExamen'])
            ->whereBetween('fecha', [$desde, $hasta])
            ->get()
            ->map(fn($c) => [
                'id'              => $c->id,
                'title'           => $c->paciente->nombre_completo ?? 'Sin paciente',
                'start'           => $c->fecha->format('Y-m-d') . 'T' . $c->hora,
                'color'           => $c->calendar_color,
                'extendedProps'   => [
                    'empresa'     => $c->empresa->nombre ?? '-',
                    'tipo_examen' => $c->tipoExamen->nombre ?? '-',
                    'estado'      => $c->estado,
                    'hora'        => substr($c->hora, 0, 5),
                    'url_detalle' => route('citas.show', $c->id),
                ],
            ]);

        return response()->json($citas);
    }

    // ── AJAX: verificar doble asignación ────────────────────

    public function verificarDisponibilidad(Request $request)
    {
        $fecha    = $request->input('fecha');
        $hora     = $request->input('hora');
        $exceptId = (int) $request->input('id', 0);

        if (!$fecha || !$hora) {
            return response()->json(['disponible' => true]);
        }

        $conflicto = Cita::existeConflicto($fecha, $hora, $exceptId);

        return response()->json([
            'disponible' => !$conflicto,
            'mensaje'    => $conflicto
                ? "Ya existe una cita agendada el {$fecha} a las {$hora}. Seleccione otro horario."
                : 'Horario disponible.',
        ]);
    }

    // ── CREATE ───────────────────────────────────────────────

    public function create(Request $request)
    {
        $data = $this->catalogs();
        // Prellenar paciente si viene desde la vista de pacientes
        $data['paciente_preseleccionado'] = $request->input('paciente_id');
        return view('citas.create', $data);
    }

    // ── STORE ────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        // Verificar doble asignación server-side
        if (Cita::existeConflicto($validated['fecha'], $validated['hora'])) {
            return back()
                ->withInput()
                ->withErrors(['hora' => 'Ya existe una cita en esa fecha y hora. Seleccione otro horario.']);
        }

        Cita::create($validated);

        return redirect()->route('citas.index')
                         ->with('success', 'Cita agendada exitosamente.');
    }

    // ── SHOW ─────────────────────────────────────────────────

    public function show(Cita $cita)
    {
        $cita->load(['paciente', 'empresa', 'tipoExamen']);
        return view('citas.show', compact('cita'));
    }

    // ── EDIT ─────────────────────────────────────────────────

    public function edit(Cita $cita)
    {
        return view('citas.edit', array_merge(compact('cita'), $this->catalogs()));
    }

    // ── UPDATE ───────────────────────────────────────────────

    public function update(Request $request, Cita $cita)
    {
        // En edición permitir fecha pasada si la cita ya existía
        $rules = $this->rules($cita->id);
        $rules['fecha'] = 'required|date';

        $validated = $request->validate($rules, $this->messages());

        // Verificar doble asignación (excluyendo la cita actual)
        if (Cita::existeConflicto($validated['fecha'], $validated['hora'], $cita->id)) {
            return back()
                ->withInput()
                ->withErrors(['hora' => 'Ya existe una cita en esa fecha y hora. Seleccione otro horario.']);
        }

        $cita->update($validated);

        return redirect()->route('citas.index')
                         ->with('success', '✅ Cita actualizada correctamente.');
    }

    // ── DESTROY ──────────────────────────────────────────────

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')
                         ->with('success', '🗑️ Cita eliminada del sistema.');
    }

    // ── Cambio rápido de estado (AJAX) ───────────────────────

    public function cambiarEstado(Request $request, Cita $cita)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        $cita->update(['estado' => $request->estado]);

        return response()->json([
            'success'      => true,
            'estado'       => $cita->estado,
            'badge_class'  => $cita->badge_class,
        ]);
    }
}
