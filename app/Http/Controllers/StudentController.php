<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    protected $student;

    public function __construct()
    {
        $this->student = new Student();
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->student->query();

        $user = Auth::user();

        //solo se muestran los estudiantes de la agencia del usuario logueado
        $query->where('agency_id', $user->agency_id);

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'agency/students/index',
            [
                'title' => 'Estudiantes',
                'subtitle' => 'Gestión de estudiantes',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->student->headers,
            ]

        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'document_type' => 'required',
                //el numero de documento debe ser unico por agencia
                'document_number' => 'required|unique:students,document_number,NULL,id,agency_id,' . auth()->user()->agency_id,
                'name' => 'required',
                'paternal_surname' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required',
            ],
            [
                'document_type.required' => 'El tipo de documento es requerido',
                'document_number.unique' => 'El número de documento ya se encuentra registrado',
                'name.required' => 'El nombre es requerido',
                'paternal_surname.required' => 'El apellido paterno es requerido',
                'email.required' => 'El correo es requerido',
                'email.email' => 'El correo no es válido',
                'phone_number.required' => 'El teléfono es requerido',

            ]
        );

        try {
            //la agencia se obtiene del usuario logueado
            $request->merge(['agency_id' => auth()->user()->agency_id]);
            $this->student->create($request->all());
            return redirect()->back()->with('success', 'Instructor creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el instructor',
                'message' => $e->getMessage()
            ]);
        }
    }
}
