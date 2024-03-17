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

        $query->join('users', 'users.profile_id', '=', 'instructors.id')
            ->join('agencies', 'instructors.agency_id', '=', 'agencies.id')
            ->select(
                DB::raw("CONCAT(instructors.name , ' ' ,instructors.last_name ) as full_name"),

                //validity_period
                DB::raw("CONCAT(DATE_FORMAT(instructors.license_start, '%d/%m/%Y') , ' - ' ,DATE_FORMAT(instructors.license_end, '%d/%m/%Y')) as validity_period"),
                DB::raw("DATEDIFF(instructors.license_end, CURDATE()) as days_remaining"),
                'instructors.*',
                'users.id as user_id',
                'users.username',
                'users.email',
                'users.role',
                'users.is_enabled as user_is_enabled',
                'agencies.name as agency'
            )->where('role', '003');

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

        $request->validate(
            [

                'instructor_id' => 'required',
                'document_number' => 'required',
                'name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'license_start' => 'required',
                'license_end' => 'required',
                'email' => 'required|email',
                'username' => 'required',
                'agency_id' => 'required',
                'password' => 'required',
            ],
            [
                'instructor_id.required' => 'El campo código es obligatorio',
                'document_number.required' => 'El campo número de documento es obligatorio',
                'name.required' => 'El campo nombre es obligatorio',
                'last_name.required' => 'El campo apellido es obligatorio',
                'phone_number.required' => 'El campo teléfono es obligatorio',
                'license_start.required' => 'El campo fecha de inicio de licencia es obligatorio',
                'license_end.required' => 'El campo fecha de fin de licencia es obligatorio',
                'email.required' => 'El campo correo es obligatorio',
                'email.email' => 'El campo correo debe ser un correo válido',
                'username.required' => 'El campo usuario es obligatorio',
                'agency_id.required' => 'El campo agencia es obligatorio',
                'password.required' => 'El campo contraseña es obligatorio',
            ]
        );

        DB::beginTransaction();
        try {
            //añadir el rol de instructor
            $request->merge(['role' => '003']);
            $instructor = $this->instructor->create($request->only($this->instructor->getFillable()));

            //asinar permisos de type 003
            $permissions = collect(config('app.permissions'))->where('type', '003')->pluck('name');
            $user = User::createAccount($request, $instructor);
            $user->givePermissionTo($permissions);
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
        $request->validate(
            [
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
            ],
            [
                'instructor_id.required' => 'El campo código es obligatorio',
                'document_number.required' => 'El campo número de documento es obligatorio',
                'name.required' => 'El campo nombre es obligatorio',
                'last_name.required' => 'El campo apellido es obligatorio',
                'phone_number.required' => 'El campo teléfono es obligatorio',
                'license_start.required' => 'El campo fecha de inicio de licencia es obligatorio',
                'license_end.required' => 'El campo fecha de fin de licencia es obligatorio',
                'email.required' => 'El campo correo es obligatorio',
                'email.email' => 'El campo correo debe ser un correo válido',
                'username.required' => 'El campo usuario es obligatorio',
                'agency_id.required' => 'El campo agencia es obligatorio',
            ]
        );

        DB::beginTransaction();
        try {
            $instructor = $this->instructor->find($request->id);
            $instructor->update($request->only($this->instructor->getFillable()));


            $user = User::updateAccount($request, $instructor);
            //$permissions = collect(config('app.permissions'))->where('type', '003')->pluck('name');
            //$user->givePermissionTo($permissions);

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

    //cambiar estado
    public function changeState($id)
    {
        $instructor = Instructor::find($id);
        $instructor->is_enabled = !$instructor->is_enabled;
        $instructor->save();
        return redirect()->back()->with('success', 'Estado del instructor actualizado correctamente');
    }

    //indexAgency
    public function indexAgency(Request $request)
    {
        $currentUser = $request->user();

        $perPage = $request->input('perPage', 10);

        $query = $this->instructor->query();

        //join con la tabla de usuarios
        $query->join('users', 'users.profile_id', '=', 'instructors.id')
            ->join('agencies', 'instructors.agency_id', '=', 'agencies.id')
            ->select(
                DB::raw("CONCAT(instructors.name , ' ' ,instructors.last_name ) as full_name"),
                DB::raw("CONCAT(DATE_FORMAT(instructors.license_start, '%d/%m/%Y') , ' - ' ,DATE_FORMAT(instructors.license_end, '%d/%m/%Y')) as validity_period"),
                //dias restantes de la fecha de vencimiento
                DB::raw("DATEDIFF(instructors.license_end, CURDATE()) as days_remaining"),
                'instructors.*',
                'users.id as user_id',
                'users.username',
                'users.email',
                'users.role',
                'users.is_enabled as user_is_enabled',
                'agencies.name as agency'
            )->where('role', '003');

        //solo mostrar los instructores de la agencia del usuario
        $query->where('instructors.agency_id', $currentUser->agency_id);

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'agency/instructors/index',
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
                'agencies' => Agency::where('id', $currentUser->agency_id)->get(),
                'agency' => $currentUser->agency_id
            ]

        );
    }
}
