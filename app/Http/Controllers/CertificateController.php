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
    protected $agency;
    public function __construct()
    {
        $this->agency = new Agency();
        $this->certificate = new Certificate();
    }

    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->agency->query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('code_nsc', 'LIKE', "%{$request->search}%")
                ->orWhere('ruc', 'LIKE', "%{$request->search}%");
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

    public function agency(Request $request, $id)
    {

        $agency = Agency::findOrFail($id);

        $perPage = $request->input('perPage', 10);

        $query = $this->certificate->query();

        if ($request->has('search')) {
            $query->where('range_start', 'LIKE', "%{$request->search}%")
                ->orWhere('range_end', 'LIKE', "%{$request->search}%");
        }

        $query->where('agency_id', $id);

        $items = $query->paginate($perPage)->appends($request->query());


        return inertia(
            'admin/certificates/agency',
            [
                'title' => 'Certificados - ' . $agency->name,
                'subtitle' => 'Gestión de Certificados',
                'agency' => $agency,
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->certificate->headersCertificates,
            ]
        );
    }

    public function certificateDetails(Request $request, $id, $certificate_id)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->certificate->query();

        $query->where('certificates.id', $certificate_id);

        $query->select(
            'certificate_details.id',
            'certificates.created_at',
            'certificate_details.number',
            // 'certificate_details.updated_at',
            'certificate_details.status',
            'student_certificates.start_date',
            'student_certificates.end_date',
            'student_certificates.is_approved',
            'courses.id as course_id',
            'courses.name as course',
            'students.id as student_id',
            DB::raw('concat_ws(" ", students.document_number, students.name, students.paternal_surname, students.maternal_surname) as student'),
            'instructors.id as instructor_id',
            DB::raw('concat_ws(" ", instructors.instructor_id, instructors.name, instructors.last_name) as instructor'),
        )
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->leftJoin('courses', 'student_certificates.course_id', '=', 'courses.id')
            ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')
            ->leftjoin('instructors', 'student_certificates.instructor_id', '=', 'instructors.id')
            // ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')

        ;

        if ($request->has('search')) {
            $query->where('courses.name', 'LIKE', "%{$request->search}%")
                ->orWhere('students.name', 'LIKE', "%{$request->search}%")
                ->orWhere('students.document_number', 'LIKE', "%{$request->search}%")
                ->orWhere('instructors.name', 'LIKE', "%{$request->search}%")
                ->orWhere('instructors.instructor_id', 'LIKE', "%{$request->search}%")
                ->orWhere('certificate_details.number', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/certificates/details',
            [
                'title' => 'Certificados',
                'subtitle' => 'Gestión de Certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->certificate->headersAgency
            ]

        );
    }

    public function store(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $request->validate(
                [
                    'range_start' => 'required|numeric',
                    'range_end' => 'required|numeric|gte:range_start',
                ],
                [
                    'range_end.gte' => 'El rango final debe ser mayor o igual al rango inicial',
                ]
            );


            $rangeStart = $request->range_start;
            $rangeEnd = $request->range_end;
            $overlap = $this->certificate
                // ->where('agency_id', $id)
                ->where(function ($query) use ($rangeStart, $rangeEnd) {
                    $query->whereBetween('range_start', [$rangeStart, $rangeEnd])
                        ->orWhereBetween('range_end', [$rangeStart, $rangeEnd])
                        ->orWhere(function ($query) use ($rangeStart, $rangeEnd) {
                            $query->where('range_start', '<=', $rangeStart)
                                ->where('range_end', '>=', $rangeEnd);
                        });
                })
                ->exists();

            if ($overlap) {
                return back()->withErrors([
                    'error' => 'Los rangos de certificados se superponen.',
                    'exception' => 'Los rangos de certificados se superponen, esto quiere decir que ya existe un rango de certificados que se superpone con el rango que intenta crear.'
                ]);
            }

            $certificate = $this->certificate->create(
                [
                    'agency_id' => $id,
                    'range_start' => $request->range_start,
                    'range_end' => $request->range_end,
                    'quantity' => $request->range_end - $request->range_start + 1,
                    'user_id' => auth()->id(),
                ]
            );

            $certificateNumber = $request->range_start;
            while ($certificateNumber <= $request->range_end) {
                CertificateDetail::create([
                    'certificate_id' => $certificate->id,
                    'number' => $certificateNumber,
                    'status' => '000',
                ]);
                $certificateNumber++;
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

    public function update(Request $request, string $id, string $certificate_id)
    {
        DB::beginTransaction();
        try {

            $request->validate(
                [
                    'range_start' => 'required|numeric',
                    //validar que el rango final sea mayor o igual al rango inicial
                    'range_end' => 'required|numeric|gte:range_start',
                ],
                [
                    'range_end.gte' => 'El rango final debe ser mayor o igual al rango inicial',
                ]
            );



            $rangeStart = $request->range_start;
            $rangeEnd = $request->range_end;
            $overlap = $this->certificate
                ->where('agency_id', $id)
                ->where('id', '!=', $certificate_id)
                ->where(function ($query) use ($rangeStart, $rangeEnd) {
                    $query->whereBetween('range_start', [$rangeStart, $rangeEnd])
                        ->orWhereBetween('range_end', [$rangeStart, $rangeEnd])
                        ->orWhere(function ($query) use ($rangeStart, $rangeEnd) {
                            $query->where('range_start', '<=', $rangeStart)
                                ->where('range_end', '>=', $rangeEnd);
                        });
                })
                ->exists();

            if ($overlap) {
                return back()->withErrors([
                    'error' => 'Los rangos de certificados se superponen.',
                    'exception' => 'Los rangos de certificados se superponen, esto quiere decir que ya existe un rango de certificados que se superpone con el rango que intenta crear.'
                ]);
            }

            $certificate = Certificate::findOrFail($certificate_id);

            $certificate->update([
                'range_start' => $request->range_start,
                'range_end' => $request->range_end,
                'quantity' => $request->range_end - $request->range_start + 1,
            ]);

            //validar si unos de los detalles del certificado ya fue asignado a un estudiante que sea diferente a 000
            $certificateDetails = CertificateDetail::where('certificate_id', $certificate_id)->where('status', '!=', '000')->exists();

            if ($certificateDetails) {
                return back()->withErrors([
                    'error' => 'No se puede modificar el rango de certificados',
                    'exception' => 'No se puede modificar el rango de certificados ya que uno de los certificados ya fue asignado a un estudiante'
                ]);
            }

            //eliminar los detalles del certificado
            CertificateDetail::where('certificate_id', $certificate_id)->delete();

            $certificateNumber = $request->range_start;
            while ($certificateNumber <= $request->range_end) {
                CertificateDetail::create([
                    'certificate_id' => $certificate->id,
                    'number' => $certificateNumber,
                    'status' => '000',
                ]);
                $certificateNumber++;
            }


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

    public function destroy(string $id, string $certificate_id)
    {
        DB::beginTransaction();
        try {

            $certificateDetails = CertificateDetail::where('certificate_id', $certificate_id)->where('status', '!=', '000')->exists();

            if ($certificateDetails) {
                return back()->withErrors([
                    'error' => 'No se puede modificar el rango de certificados',
                    'exception' => 'No se puede modificar el rango de certificados ya que uno de los certificados ya fue asignado a un estudiante'
                ]);
            }

            $certificate = Certificate::findOrFail($certificate_id);
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

        $query->where('certificates.agency_id', auth()->user()->agency_id);

        $agency = auth()->user()->agency_id;

        $query->select(
            'certificate_details.id',
            'certificates.created_at',
            'certificate_details.number',
            'certificate_details.status',
            'student_certificates.start_date',
            'student_certificates.end_date',
            'student_certificates.is_approved',
            'courses.id as course_id',
            'courses.name as course',
            'students.id as student_id',
            DB::raw('concat_ws(" ", students.document_number, students.name, students.paternal_surname, students.maternal_surname) as student'),
            'instructors.id as instructor_id',
            DB::raw('concat_ws(" ", instructors.instructor_id, instructors.name, instructors.last_name) as instructor'),
        )
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->leftJoin('courses', 'student_certificates.course_id', '=', 'courses.id')
            ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')
            ->leftjoin('instructors', 'student_certificates.instructor_id', '=', 'instructors.id');


        if ($request->has('search')) {
            $query->where('courses.name', 'LIKE', "%{$request->search}%")
                ->orWhere('students.name', 'LIKE', "%{$request->search}%")
                ->orWhere('students.document_number', 'LIKE', "%{$request->search}%")
                ->orWhere('instructors.name', 'LIKE', "%{$request->search}%")
                ->orWhere('instructors.instructor_id', 'LIKE', "%{$request->search}%")
                ->orWhere('certificate_details.number', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

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
                'courses' => Course::select('id', 'name')->where('is_enabled', true)->get(),
                'instructorsActive' => Instructor::instructorActiveCourses($agency),
            ]

        );
    }

    public function storeAgency(Request $request)
    {
        DB::beginTransaction();


        try {
            $certificate = CertificateDetail::where('id', $request->id)->first();
            //validar si el certificado ya fue asignado a un estudiante para actualizar  o crear
            if ($certificate->status != '000') {
                //actualizar el estado del detalle del certificado si se creo correctamente
                $studentCertificate = StudentCertificate::where('certificate_id', $request->id)->first();

                //si el certificado ya fue aprobado no se puede modificar
                if ($studentCertificate->is_approved != 0) {
                    return redirect()->back()->withErrors([
                        'error' => 'El certificado ya fue aprobado o rechazado',
                        'exception' => 'El certificado ya fue aprobado o rechazado, no se puede modificar'
                    ]);
                }

                $studentCertificate->update([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'instructor_id' => $request->instructor_id,
                    'student_id' => $request->student_id,
                    'course_id' => $request->course_id,
                    'user_id' => auth()->id(),
                ]);
            } else {
                $studentCertificate = StudentCertificate::create([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'instructor_id' => $request->instructor_id,
                    'student_id' => $request->student_id,
                    'certificate_id' => $request->id,
                    'course_id' => $request->course_id,
                    'user_id' => auth()->id(),
                ]);
            }

            //actualizar el estado del detalle del certificado si se creo correctamente
            if ($studentCertificate) {
                $certificate = CertificateDetail::where('id', $certificate->id)->first();
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
