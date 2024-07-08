<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Student;
use App\Models\StudentCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\select;

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

        $agencies = [];
        if ($user->agency_id) {
            $query->where('agency_id', $user->agency_id);
            $view = 'agency/students/index';
        } else {
            $view = 'admin/students/index';
            $agencies = Agency::select('id', 'name')->get();

            // al header se le agrega la agencia
            $this->student->headers[] = ['text' => 'Agencia', 'value' => 'agency_name'];
        }


        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            $view,
            [
                'title' => 'Estudiantes',
                'subtitle' => 'Gestión de estudiantes',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->student->headers,
                'agencies' => $agencies,
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
            //si no se tiene una asignada se toma la agencia del usuario logueado
            if (!$request->has('agency_id')) {
                $request->merge(['agency_id' => auth()->user()->agency_id]);
            }

            $this->student->create($request->all());
            return redirect()->back()->with('success', 'Estudiante creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el instructor',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $request->validate(
            [
                'document_type' => 'required',
                //el numero de documento debe ser unico por agencia
                'document_number' => 'required|unique:students,document_number,' . $request->id . ',id,agency_id,' . auth()->user()->agency_id,
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
            $this->student->find($request->id)->update($request->all());
            return redirect()->back()->with('success', 'Estudiante actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el estudiante',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {

            // no se ouede eliminar si tiene un certificado asociado (student_certificates)
            if (StudentCertificate::where('student_id', $id)->exists()) {
                return redirect()->back()->withErrors([
                    'error' => 'No se puede eliminar el estudiante, tiene certificados asociados',
                    'message' => 'El estudiante tiene certificados asociados'
                ]);
            }

            $this->student->find($id)->delete();


            return redirect()->back()->with('success', 'Estudiante eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el estudiante',
                'message' => $e->getMessage()
            ]);
        }
    }
}
