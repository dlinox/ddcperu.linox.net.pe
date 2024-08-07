<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    protected $course;

    public function __construct()
    {
        $this->course = new Course();
    }

    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->course->query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/courses/index',
            [
                'title' => 'Cursos',
                'subtitle' => 'Gestión de Cursos',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->course->headers,
                'permissions' => config('app.permissions'),
            ]

        );
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $request->all();
            Course::create($data);
            return redirect()->back()->with('success', 'Curso creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el curso',
            ]);
        }
    }

    public function update(CourseRequest $request, $id)
    {
        try {
            $data = $request->all();
            $course = Course::find($id);
            $course->update($data);
            return redirect()->back()->with('success', 'Curso actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el curso',
            ]);
        }
    }

    public function changeState($id)
    {
        try {
            $course = Course::find($id);
            $course->is_enabled = !$course->is_enabled;
            $course->save();
            return redirect()->back()->with('success', 'Estado del curso actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el estado del curso',
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::find($id);

            //validar que no este relacionado con un certificado
            if ($course->studentCertificates->count() > 0) {
                return redirect()->back()->with([
                    'alert' => 'No se puede eliminar el curso, tiene certificados relacionados',
                ]);
            }

            $course->delete();
            return redirect()->back()->with('success', 'Curso eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el curso',
                'exception' => $e->getMessage()
            ]);
        }
    }
}
