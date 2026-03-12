<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Empresa;
use App\Models\TipoExamen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    // ── Reglas de validación compartidas ────────────────────

    private function rules(int $exceptId = 0): array
    {
        return [
            'nombre_completo'     => 'required|string|max:150',
            'tipo_documento'      => 'required|in:CC,TI,CE,PA,RC',
            'numero_documento'    => ['required', 'string', 'max:30',
                                       Rule::unique('pacientes', 'numero_documento')->ignore($exceptId)],
            'fecha_nacimiento'    => 'required|date|before:today',
            'edad'                => 'required|integer|min:0|max:120',
            'direccion'           => 'required|string|max:200',
            'telefono'            => 'nullable|string|max:20',
            'celular'             => 'required|string|max:20',
            'eps'                 => 'required|string|max:100',
            'contacto_nombre'     => 'required|string|max:150',
            'contacto_parentesco' => 'required|string|max:60',
            'contacto_telefono'   => 'required|string|max:20',
            'empresa_id'          => 'required|exists:empresas,id',
            'tipo_examen_id'      => 'required|exists:tipos_examen,id',
            'fecha_examen'        => 'required|date|after_or_equal:today',
        ];
    }

    private function messages(): array
    {
        return [
            'nombre_completo.required'     => 'El nombre completo es obligatorio.',
            'tipo_documento.required'      => 'Seleccione el tipo de documento.',
            'numero_documento.required'    => 'El número de documento es obligatorio.',
            'numero_documento.unique'      => 'Este número de documento ya está registrado.',
            'fecha_nacimiento.required'    => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before'      => 'La fecha de nacimiento debe ser anterior a hoy.',
            'edad.required'                => 'La edad es obligatoria.',
            'celular.required'             => 'El celular es obligatorio.',
            'eps.required'                 => 'La EPS es obligatoria.',
            'contacto_nombre.required'     => 'El nombre del contacto es obligatorio.',
            'contacto_parentesco.required' => 'El parentesco es obligatorio.',
            'contacto_telefono.required'   => 'El teléfono de contacto es obligatorio.',
            'empresa_id.required'          => 'Seleccione la empresa.',
            'empresa_id.exists'            => 'La empresa seleccionada no es válida.',
            'tipo_examen_id.required'      => 'Seleccione el tipo de examen.',
            'tipo_examen_id.exists'        => 'El tipo de examen seleccionado no es válido.',
            'fecha_examen.required'        => 'La fecha de examen es obligatoria.',
            'fecha_examen.after_or_equal'  => 'La fecha de examen no puede ser anterior a hoy.',
        ];
    }

    private function catalogs(): array
    {
        return [
            'empresas'      => Empresa::orderBy('nombre')->get(),
            'tiposExamen'   => TipoExamen::orderBy('nombre')->get(),
            'tiposDocumento' => ['CC' => 'Cédula de Ciudadanía', 'TI' => 'Tarjeta de Identidad',
                                 'CE' => 'Cédula de Extranjería', 'PA' => 'Pasaporte', 'RC' => 'Registro Civil'],
        ];
    }

    // ── INDEX ────────────────────────────────────────────────

    public function index(Request $request)
    {
        $busqueda = $request->input('q');

        $pacientes = Paciente::with(['empresa', 'tipoExamen'])
            ->when($busqueda, fn($q) => $q->buscar($busqueda))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'busqueda'));
    }

    // ── CREATE ───────────────────────────────────────────────

    public function create()
    {
        return view('pacientes.create', $this->catalogs());
    }

    // ── STORE ────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        Paciente::create($validated);

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente registrado exitosamente.');
    }

    // ── SHOW ─────────────────────────────────────────────────

    public function show(Paciente $paciente)
    {
        $paciente->load(['empresa', 'tipoExamen']);
        return view('pacientes.show', compact('paciente'));
    }

    // ── EDIT ─────────────────────────────────────────────────

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', array_merge(compact('paciente'), $this->catalogs()));
    }

    // ── UPDATE ───────────────────────────────────────────────

    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate($this->rules($paciente->id), $this->messages());

        $paciente->update($validated);

        return redirect()->route('pacientes.index')
                         ->with('success', 'Paciente actualizado exitosamente.');
    }

    // ── DESTROY ──────────────────────────────────────────────

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')
                         ->with('success', '🗑️ Paciente eliminado del sistema.');
    }

    // ── AJAX: búsqueda en tiempo real ────────────────────────

    public function buscar(Request $request)
    {
        $termino = $request->input('q', '');

        $pacientes = Paciente::with(['empresa', 'tipoExamen'])
            ->buscar($termino)
            ->limit(8)
            ->get()
            ->map(fn($p) => [
                'id'               => $p->id,
                'nombre_completo'  => $p->nombre_completo,
                'numero_documento' => $p->numero_documento,
                'tipo_documento'   => $p->tipo_documento_label,
                'empresa'          => $p->empresa->nombre ?? '-',
                'tipo_examen'      => $p->tipoExamen->nombre ?? '-',
                'fecha_examen'     => $p->fecha_examen->format('d/m/Y'),
                'estado'           => $p->estado,
            ]);

        return response()->json($pacientes);
    }

    // ── AJAX: validar documento único ────────────────────────

    public function validarDocumento(Request $request)
    {
        $existe = Paciente::where('numero_documento', $request->input('documento'))
            ->when($request->input('id'), fn($q) => $q->where('id', '!=', $request->input('id')))
            ->exists();

        return response()->json(['disponible' => ! $existe]);
    }
}
