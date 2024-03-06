<?php

namespace App\Http\Controllers;

use App\Models\StudentCertificate;
use Illuminate\Http\Request;

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
        ->select('student_certificates.*', 'courses.name as course');

        $query->with('student:id,name', 'certificate:id,number');

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
        }
        else {
            //actualizar el estado del certificado
            $studentCertificate->certificate->updateStatus('000');
        }



        return redirect()->back()->with('success', 'Estado actualizado');
    }
}
