<?php

namespace App\Http\Controllers;

use App\Models\StudentCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentCertificateController extends Controller
{

    protected $studentCertificate;

    public function __construct()
    {
        $this->studentCertificate = new StudentCertificate();
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->studentCertificate->query();

        //obtener los cursos del certificado

        $query->join('certificate_details', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->join('courses', 'courses.id', '=', 'certificate_details.course_id')
            ->join('students', 'students.id', '=', 'student_certificates.student_id')
            //agregar el nombre de la agencia
            ->join('agencies', 'agencies.id', '=', 'students.agency_id')
            ->select(
                'student_certificates.*',
                'courses.name as course',

                DB::raw("CONCAT(students.name , ' ' ,students.paternal_surname , ' ', students.maternal_surname ) as student"),
                'agencies.name as agency'
            );


        $query->with('certificate:id,number');

        // if ($request->has('search')) {
        //     $query->where('courses.name', 'LIKE', "%{$request->search}%");
        // }
        //el instructor_id es el mismo que el user_id profile_id
        $query->where('instructor_id', auth()->user()->profile_id);

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'instructors/certificates/index',
            [
                'title' => 'Certificados de Estudiantes',
                'subtitle' => 'Gestión de Certificados de Estudiantes',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->studentCertificate->headers,
            ]

        );
    }

    //changeState
    public function changeState(Request $request, $id)
    {
        $request->validate([
            'is_approved' => 'required|in:0,1,2'
        ]);

        $studentCertificate = $this->studentCertificate->find($id);
        $studentCertificate->is_approved = $request->is_approved;
        $studentCertificate->save();

        if ($request->is_approved == 1) {
            //actualizar el estado del certificado
            $studentCertificate->certificate->updateStatus('002');
        } else {
            //actualizar el estado del certificado
            $studentCertificate->certificate->updateStatus('000');
        }



        return redirect()->back()->with('success', 'Estado actualizado');
    }

    public function searchCertificateStudent(Request $request)
    {
        $searchBy = $request->by; // nombre, documento, numero de certificado
        $search = $request->search;


        $query = $this->studentCertificate->query();

        //obtener los cursos del certificado
        $query->join('certificate_details', 'certificate_details.id', '=', 'student_certificates.certificate_id')
            ->join('courses', 'courses.id', '=', 'certificate_details.course_id')
            ->join('students', 'students.id', '=', 'student_certificates.student_id')
            ->join('instructors', 'instructors.id', '=', 'student_certificates.instructor_id')
            //agregar el nombre de la agencia
            ->join('agencies', 'agencies.id', '=', 'students.agency_id')
            ->select(
                //nombre del estudiante
                DB::raw("CONCAT(students.name , ' ' ,students.paternal_surname , ' ', students.maternal_surname ) as student"),
                //nombre del curso
                'courses.name as course',
                //nombre del instructor
                DB::raw("CONCAT(instructors.instructor_id, ' - ', instructors.name , ' ' ,instructors.last_name ) as instructor"),
                //numero del certificado
                'certificate_details.number as certificate_number',
                //nombre de la agencia
                'agencies.name as agency',
                //codigo del instructor

                //fecha de emision y fecha de vencimiento del certificado formato dd/mm/yyyy


                DB::raw("DATE_FORMAT(certificate_details.start_date, '%d/%m/%Y') as start_date"),
                DB::raw("DATE_FORMAT(certificate_details.end_date, '%d/%m/%Y') as end_date"),

            );

        //el student certificate debe estar aprobado
        $query->where('student_certificates.is_approved', 1);
        //el status del certificado debe ser 2
        $query->where('certificate_details.status', '002');

        if ($searchBy == 'Nombre') {
            //por el nombre completo del estudiante
            $query->where(DB::raw("CONCAT(students.name , ' ' ,students.paternal_surname , ' ', students.maternal_surname )"), 'LIKE', "%{$search}%");
        } else if ($searchBy == 'Documento') {
            $query->where('students.document_number', 'LIKE', "{$search}");
        } else if ($searchBy == 'Código') {
            $query->where('certificate_details.number', 'LIKE', "{$search}");
        }
        //sin paginacion
        $items = $query->get();

        return response()->json($items);
    }
}
