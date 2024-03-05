<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    protected $instructor;

    public function __construct()
    {
        $this->instructor = new Instructor();
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->instructor->query();


        //join con la tabla de usuarios
        $query->join('users', 'users.profile_id', '=', 'instructors.id')
            ->select('instructors.*', 'users.id as user_id', 'users.username', 'users.email', 'users.role', 'users.is_enabled as user_is_enabled')->where('role', '003');

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/instructors/index',
            [
                'title' => 'Instructores',
                'subtitle' => 'Gestión de instructores',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->instructor->headers,
                'permissions' => config('app.permissions'),
                'agencies' => Agency::all()
            ]

        );
    }

    public function store(Request $request)
    {

        $request->validate([

            'instructor_id' => 'required',
            'document_number' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'license_start' => 'required',
            'license_end' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'agency_id' => 'required',
            'password' => 'required',
        ]);

        DB::beginTransaction();
        try {
            //añadir el rol de instructor
            $request->merge(['role' => '003']);
            $instructor = $this->instructor->create($request->only($this->instructor->getFillable()));

            User::createAccount($request, $instructor);
            DB::commit();
            return redirect()->back()->with('success', 'Instructor creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el instructor',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'instructor_id' => 'required',
            'document_number' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'license_start' => 'required',
            'license_end' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'agency_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $instructor = $this->instructor->find($request->id);
            $instructor->update($request->only($this->instructor->getFillable()));


            User::updateAccount($request, $instructor);
            DB::commit();
            return redirect()->back()->with('success', 'Instructor actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el instructor',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $instructor = $this->instructor->find($id);
            $instructor->delete();
            User::deleteAccount($id);
            DB::commit();
            return redirect()->back()->with('success', 'Instructor eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el instructor',
                'message' => $e->getMessage()
            ]);
        }
    }
}
