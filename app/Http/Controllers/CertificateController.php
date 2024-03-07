<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Certificate;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\StudentCertificate;
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

        $items->map(function ($item) {
            $item->certificateStudent  = CertificateDetail::leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
                ->leftjoin('students', 'students.id', '=', 'student_certificates.student_id')
                ->leftjoin('instructors', 'instructors.id', '=', 'student_certificates.instructor_id')
                ->where('certificate_details.certificate_id', $item->id)
                // ->where('student_certificates.student_id', auth()->user()->student_id)
                ->get([
                    'certificate_details.id',
                    'certificate_details.number',
                    'certificate_details.status',
                    'student_certificates.is_approved',
                    DB::raw("CONCAT(students.name , ' ' ,students.paternal_surname , ' ', students.maternal_surname ) as student"),
                    DB::raw("CONCAT(instructors.name , ' ' ,instructors.last_name) as instructor")
                ]);
            return $item;
        });

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

    public function indexAgency(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->certificate->query();
        $query->with('course:id,name');
        $query->where('agency_id', auth()->user()->agency_id);

        if ($request->has('search')) {
            $query->where('courses.name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        $items->map(function ($item) {
            //agrergar los detalles del certificado solo el numero y el estado
            $item->certificateDetails = CertificateDetail::where('certificate_id', $item->id)
                ->where('status', '000')
                ->get(['id', 'number', 'status']);
            $item->certificateStudent  = CertificateDetail::leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
                ->leftjoin('students', 'students.id', '=', 'student_certificates.student_id')
                ->leftjoin('instructors', 'instructors.id', '=', 'student_certificates.instructor_id')
                ->where('certificate_details.certificate_id', $item->id)
                // ->where('student_certificates.student_id', auth()->user()->student_id)
                ->get([
                    'certificate_details.id',
                    'certificate_details.number',
                    'certificate_details.status',
                    'student_certificates.is_approved',
                    DB::raw("CONCAT(students.name , ' ' ,students.paternal_surname , ' ', students.maternal_surname ) as student"),
                    DB::raw("CONCAT(instructors.name , ' ' ,instructors.last_name) as instructor")
                ]);
            return $item;
        });

        return inertia(
            'agency/certificates/index',
            [
                'title' => 'Certificados',
                'subtitle' => 'Gestión de Certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->certificate->headersAgency,
                'students' => Student::select('id', DB::raw("CONCAT(document_number,' - ', name, ' ', paternal_surname, ' ', maternal_surname) as name"))->where('agency_id', auth()->user()->agency_id)->get(),
                'instructors' => Instructor::select('id', DB::raw("CONCAT(instructor_id,' - ', name, ' ', last_name) as name"))->where('agency_id', auth()->user()->agency_id)->get(),
            ]

        );
    }

    public function storeAgency(Request $request)
    {
        DB::beginTransaction();
        try {
            $studentCertificate = StudentCertificate::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'instructor_id' => $request->instructor_id,
                'student_id' => $request->student_id,
                'certificate_id' => $request->certificate_id,
                'user_id' => auth()->id(),
            ]);
            //actualizar el estado del detalle del certificado si se creo correctamente
            if ($studentCertificate) {
                $certificate = CertificateDetail::where('id', $request->certificate_id)->first();
                $certificate->status = '001';
                $certificate->save();
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
}
