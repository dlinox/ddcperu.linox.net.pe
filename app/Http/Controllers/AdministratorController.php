<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministratorController extends Controller
{

    protected $administator;

    public function __construct()
    {
        $this->administator = new Administrator();
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->administator->query();

        //join con la tabla de usuarios
        $query->join('users', 'users.profile_id', '=', 'administrators.id')
            ->select('administrators.*', 'users.id as user_id', 'users.username', 'users.email', 'users.role', 'users.is_enabled as user_is_enabled')->where('role', '001');

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/administrators/index',
            [
                'title' => 'Administradores',
                'subtitle' => 'Gestión de administradores',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->administator->headers,
                'agencies' => Agency::select('id', 'name')->where('is_enabled', true)->get(),
                'permissions' => config('app.permissions'),
            ]

        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_number' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',

            'email' => 'required|email',
            'password' => 'required',
            'username' => 'required',

        ]);

        DB::beginTransaction();
        try {
            //añadir el rol de administrador
            $request->merge(['role' => '001']);
            $administator = $this->administator->create($request->only($this->administator->getFillable()));

            User::createAccount($request, $administator);
            DB::commit();
            return redirect()->back()->with('success', 'Administrador creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear el administrador',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'document_number' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'username' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $administator = $this->administator->find($request->id);
            $administator->update($request->only($this->administator->getFillable()));


            User::updateAccount($request, $administator);
            DB::commit();
            return redirect()->back()->with('success', 'Administrador actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar el administrador',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $administator = $this->administator->find($id);
            $administator->delete();
            User::deleteAccount($id);
            DB::commit();
            return redirect()->back()->with('success', 'Administrador eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar el administrador',
                'message' => $e->getMessage()
            ]);
        }
    }
}
