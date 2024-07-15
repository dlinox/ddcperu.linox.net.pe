<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\StudentCertificate;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        if ($user->agency_id != null) {
            $subagencies = 1;
            $courses = Course::count();

            $instructors = Instructor::where('agency_id', $user->agency_id)->count();
            $certificates = CertificateDetail::join('certificates', 'certificate_details.certificate_id', '=', 'certificates.id')
                ->where('certificates.agency_id', $user->agency_id)->count();

            //si es instructor role 003

            if ($user->role === 'Instructor') {
                $certificates = StudentCertificate::where('instructor_id', $user->profile_id)->count();

                //cursos distintos
                $courses = StudentCertificate::where('instructor_id', $user->profile_id)->distinct('course_id')->count('course_id');
            }
        } else {

            $subagencies = Agency::count();
            $courses = Course::count();
            $instructors = Instructor::count();
            $certificates = CertificateDetail::count();
        }




        return inertia(
            'admin/index',
            [
                'title' =>  $user->role === 'Instructor' ? 'Instructor'  : ($user->role == 'Administrador' && $user->agency_id != null  ?   'Sub Agencia' : 'Administrador'),
                'subtitle' => 'Gestión General',
                'subagencies' => $subagencies,
                'courses' => $courses,
                'instructors' => $instructors,
                'certificates' => $certificates,

            ]
        );
    }
}
