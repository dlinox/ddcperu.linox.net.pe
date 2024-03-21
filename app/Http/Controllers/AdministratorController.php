<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdministratorRequest;
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
            //anadir la agencia
            ->leftJoin('agencies', 'users.agency_id', '=', 'agencies.id')
            ->select(
                'administrators.*',
                'users.id as user_id',
                'users.username',
                'users.email',
                'users.role',
                'users.agency_id',
                'agencies.name as agency',
                'users.is_enabled as user_is_enabled',
            )->whereIn('users.role', ['001', '002']);

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());
        //mapear los permisos
        $items->map(function ($item) {
            //agrergar permisos al item no es is_sub_admin
            if ($item->is_sub_admin) {
                $item->permissions = [];
            } else {
                $item->permissions = User::find($item->user_id)->permissions->pluck('name');
            }

            return $item;
        });

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
                //del array de permisos solo traer los que son de tipo 001 [[name=> '', menu=>'']...]

                'permissions' => collect(config('app.permissions'))->where('type', '001')->map(function ($permission) {
                    return collect($permission)->except('type');
                })
            ]

        );
    }

    public function store(AdministratorRequest $request)
    {


        DB::beginTransaction();
        try {
            //añadir el rol de administrador
            $request->merge(['role' => '001']);
            $administator = $this->administator->create($request->only($this->administator->getFillable()));
            $user = User::createAccount($request, $administator);
            //asignar permisos del request al usuario

            if ($request->is_sub_admin) {
                $user->givePermissionTo(collect(config('app.permissions'))->where('type', '002')->pluck('name'));
            } else {
                $user->givePermissionTo($request->permissions);
            }


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

    public function update(AdministratorRequest $request)
    {
        DB::beginTransaction();
        try {
            $administator = $this->administator->find($request->id);
            $administator->update($request->only($this->administator->getFillable()));

            $user = User::updateAccount($request, $administator);

            if ($request->is_sub_admin) {
                $user->givePermissionTo(collect(config('app.permissions'))->where('type', '002')->pluck('name'));
            } else {
                //borrar y reasignar todos los permisos
                $user->revokePermissionTo($user->permissions);
                $user->givePermissionTo($request->permissions);
                
            }
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

    public function changeState($id)
    {
        $administator = $this->administator->find($id);
        $administator->is_enabled = !$administator->is_enabled;
        $administator->save();
        return redirect()->back()->with('success', 'Estado del administrador actualizado correctamente');
    }
}
