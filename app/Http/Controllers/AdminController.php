<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Publication;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {

        $users = Auth::user();

        if ($users->agency_id != null) {
            $subagencies = 1;
            $courses = Course::join('certificates', 'courses.id', '=', 'certificates.course_id')
                ->where('certificates.agency_id', $users->agency_id)->count();

            $instructors = Instructor::where('agency_id', $users->agency_id)->count();
            $certificates = CertificateDetail::join('certificates', 'certificate_details.certificate_id', '=', 'certificates.id')
                ->where('certificates.agency_id', $users->agency_id)->count();
        } else {

            $subagencies = Agency::count();
            $courses = Course::count();
            $instructors = Instructor::count();
            $certificates = CertificateDetail::count();
        }


        return inertia(
            'admin/index',
            [
                'title' =>  $users->role === 'Instructor' ? 'Instructor'  : ($users->role == 'Administrador' && $users->agency_id != null  ?   'Sub Agencia' : 'Administrador'),
                'subtitle' => 'Gestión General',
                'users' => $users,
                'subagencies' => $subagencies,
                'courses' => $courses,
                'instructors' => $instructors,
                'certificates' => $certificates,


            ]
        );
    }
}
