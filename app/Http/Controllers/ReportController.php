<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Certificate;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{




    protected $certificate;
    protected $agency;
    protected $instructor;
    public function __construct()
    {

        $this->agency = new Agency();
        $this->certificate = new Certificate();
        $this->instructor = new Instructor();
    }

    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->instructor->query();

        $query->join('users', 'users.profile_id', '=', 'instructors.id')
            ->join('agencies', 'instructors.agency_id', '=', 'agencies.id')
            ->select(
                DB::raw("CONCAT(instructors.name , ' ' ,instructors.last_name ) as full_name"),
                'instructors.*',
                'agencies.name as agency'
            )->where('role', '003');

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/reports/index',
            [
                'title' => 'Reportes',
                'subtitle' => 'Listado de instructores',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => [
                    ['text' => "Codigo", 'value' => "instructor_id"],
                    ['text' => "Número de documento", 'value' => "document_number"],
                    ['text' => "Nombre", 'value' => "full_name"],
                    ['text' => "Sub agencia", 'value' => "agency"],
                ]
            ]
        );
    }

    public function instructors(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->instructor->query();

        $query->join('users', 'users.profile_id', '=', 'instructors.id')
            ->join('agencies', 'instructors.agency_id', '=', 'agencies.id')
            ->select(
                DB::raw("CONCAT(instructors.name , ' ' ,instructors.last_name ) as full_name"),
                'instructors.*',
                'agencies.name as agency'
            )->where('role', '003');

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/reports/certificates/instructors/index',
            [
                'title' => 'Instructores',
                'subtitle' => 'Reporte de certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => [
                    ['text' => "Codigo", 'value' => "instructor_id"],
                    ['text' => "Número de documento", 'value' => "document_number"],
                    ['text' => "Nombre", 'value' => "full_name"],
                    ['text' => "Sub agencia", 'value' => "agency"],
                ]
            ]
        );
    }

    public function agencies(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->agency->query();

        $query->select(
            'id',
            'name',
        );

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/reports/certificates/agencies/index',
            [
                'title' => 'Sub agencias',
                'subtitle' => 'Reporte de certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => [
                    ['text' => "Nombre", 'value' => "name"],
                    
                ]
            ]
        );
    }



    public function certificatesInstructor(Request $request, $id)
    {

        $instructor = $this->instructor->find($id);

        $agencies = $this->certificate
            ->select('agencies.id as value', 'agencies.name as title')
            ->distinct()
            ->join('agencies', 'certificates.agency_id', '=', 'agencies.id')
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->join('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->where('student_certificates.instructor_id', $id)
            ->get();

        //add option all
        $agencies->push(['value' => 'all', 'title' => 'Todas']);

        $perPage = $request->input('perPage', 10);

        $query = $this->certificate->query();

        $query->where('instructors.id', $id);

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
            DB::raw('concat_ws(" ", agencies.name) as agency'),
        )
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->leftJoin('courses', 'student_certificates.course_id', '=', 'courses.id')
            ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')
            ->leftjoin('instructors', 'student_certificates.instructor_id', '=', 'instructors.id')
            ->leftJoin('agencies', 'certificates.agency_id', '=', 'agencies.id');

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
            'admin/reports/certificates/instructors/details',
            [
                'title' => $instructor->name . ' ' . $instructor->last_name,
                'subtitle' => 'Reporte de certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->certificate->headersInstructor,
                'agencies' => $agencies
            ]

        );
    }

    public function certificatesAgency(Request $request, $id)
    {

        $agency = $this->agency->find($id);

        $perPage = $request->input('perPage', 10);

        $query = $this->certificate->query();

        $query->where('agencies.id', $id);

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
            DB::raw('concat_ws(" ", instructors.name, instructors.last_name) as instructor'),
        )
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->leftJoin('courses', 'student_certificates.course_id', '=', 'courses.id')
            ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')
            ->leftjoin('instructors', 'student_certificates.instructor_id', '=', 'instructors.id')
            ->leftJoin('agencies', 'certificates.agency_id', '=', 'agencies.id')
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
            'admin/reports/certificates/agencies/details',
            [
                'title' => $agency->name,
                'subtitle' => 'Reporte de certificados',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->certificate->headersAgency
            ]

        );
    }

    //certificatesInstructorExport

    public function certificatesInstructorExport(Request $request, $id)
    {

        $query = $this->certificate->query();

        $query->where('instructors.id', $id);

        if ($request->has('agency') && $request->agency != 'all') {
            $query->where('agencies.id', $request->agency);
        }

        if ($request->has('status') && $request->is_approved != 'all') {
            $query->where('student_certificates.is_approved', $request->status);
        }

        $query->select(
            'certificate_details.id',
            'certificates.created_at',
            'certificate_details.number',
            'certificate_details.status',
            'student_certificates.start_date',
            'student_certificates.end_date',
            DB::raw('if(student_certificates.is_approved = 1, "Aprobado", "Pendiente") as is_approved'),
            'courses.id as course_id',
            'courses.name as course',
            'students.id as student_id',
            DB::raw('concat_ws(" ", students.document_number, students.name, students.paternal_surname, students.maternal_surname) as student'),
            'instructors.id as instructor_id',
            DB::raw('concat_ws(" ", agencies.name) as agency'),
        )
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->leftJoin('courses', 'student_certificates.course_id', '=', 'courses.id')
            ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')
            ->leftjoin('instructors', 'student_certificates.instructor_id', '=', 'instructors.id')
            ->leftJoin('agencies', 'certificates.agency_id', '=', 'agencies.id');

        $items = $query->get();

        return redirect()->back()->with([
            'data' => $items,
        ]);
    }
    //certificatesAgencyExport

    public function certificatesAgencyExport(Request $request, $id)
    {

        $query = $this->certificate->query();

        $query->where('agencies.id', $id);

        if ($request->has('status') && $request->is_approved != 'all') {
            $query->where('student_certificates.is_approved', $request->status);
        }

        $query->select(
            'certificate_details.id',
            'certificates.created_at',
            'certificate_details.number',
            'certificate_details.status',
            'student_certificates.start_date',
            'student_certificates.end_date',
            DB::raw('if(student_certificates.is_approved = 1, "Aprobado", if(student_certificates.is_approved = 0, "Pendiente", "Disponible")) as is_approved'),
            'courses.id as course_id',
            'courses.name as course',
            'students.id as student_id',
            DB::raw('concat_ws(" ", students.document_number, students.name, students.paternal_surname, students.maternal_surname) as student'),
            'instructors.id as instructor_id',
            DB::raw('concat_ws(" ", instructors.name, instructors.last_name) as instructor'),
        )
            ->join('certificate_details', 'certificates.id', '=', 'certificate_details.certificate_id')
            ->leftjoin('student_certificates', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->leftJoin('courses', 'student_certificates.course_id', '=', 'courses.id')
            ->leftjoin('students', 'student_certificates.student_id', '=', 'students.id')
            ->leftjoin('instructors', 'student_certificates.instructor_id', '=', 'instructors.id')
            ->leftJoin('agencies', 'certificates.agency_id', '=', 'agencies.id');

        $items = $query->get();

        return redirect()->back()->with([
            'data' => $items,
        ]);
    }
}
