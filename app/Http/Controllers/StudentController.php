<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
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

    public function store(StudentStoreRequest $request)
    {
        try {
            if (!$request->has('agency_id')) {
                $request->merge(['agency_id' => auth()->user()->agency_id]);
            }

            $this->student->create($request->all());
            return redirect()->back()->with('success', 'Estudiante creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el instructor',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function update(StudentUpdateRequest $request)
    {

        try {
            $agency_id = $request->agency_id ?? auth()->user()->agency_id;
            $request->merge(['agency_id' => $agency_id]);
            $this->student->find($request->id)->update($request->all());
            return redirect()->back()->with('success', 'Estudiante actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el estudiante',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            if (StudentCertificate::where('student_id', $id)->exists()) {
                return redirect()->back()->with([
                    'alert' => 'No se puede eliminar el estudiante, tiene certificados emitidos'
                ]);
            }
            $this->student->find($id)->delete();
            return redirect()->back()->with('success', 'Estudiante eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el estudiante',
                'exception' => $e->getMessage()
            ]);
        }
    }
}
