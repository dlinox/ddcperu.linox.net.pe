<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    protected $certificate;

    public function __construct()
    {
        $this->certificate = new Certificate();
    }


    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->certificate->query();
        $query->with('agency:id,name', 'course:id,name', 'user:id,username');

        if ($request->has('search')) {
            $query->where('courses.name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/certificates/index',
            [
                'title' => 'Certificados',
                'subtitle' => 'Gestión de Certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->certificate->headers,
                'agencies' => Agency::all(['id', 'name']),
                'courses' => Course::all(['id', 'name']),
            ]

        );
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            $data['user_id'] = auth()->id();
            $certificate = $this->certificate->create($data);

            foreach ($request->ranges as $range) {
                for ($i = $range['range_start']; $i <= $range['range_end']; $i++) {
                    $certificate->certificateDetails()->create([
                        'number' => $i,
                        'course_id' => $request->course_id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Curso creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el curso',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $certificate = Certificate::findOrFail($id);
            $data = $request->all();
            $certificate->update($data);
            DB::commit();
            return redirect()->back()->with('success', 'Certificado actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el certificado',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $certificate = Certificate::findOrFail($id);
            //eliminar los detalles del certificado
            $certificate->certificateDetails()->delete();
            $certificate->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Certificado eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el certificado',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function changeState(string $id)
    {
        $certificate = Certificate::find($id);
        $certificate->is_enabled = !$certificate->is_enabled;
        $certificate->save();
        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
