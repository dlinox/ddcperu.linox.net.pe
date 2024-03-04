<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
       
        $users = User::count();


        return inertia(
            'admin/index',
            [
                'title' => 'Admin',
                'subtitle' => 'Gestión General',
                'users' => $users,
                'posts' => 0,
                'doctors' => 0,

            ]
        );
    }
}
