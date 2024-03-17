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

class AdminController extends Controller
{

    public function index()
    {
       
        $users = User::count();
        $subagencies = Agency::count();
        $courses = Course::count();
        $instructors = Instructor::count();
        $certificates = CertificateDetail::count();




        return inertia(
            'admin/index',
            [
                'title' => 'Admin',
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
