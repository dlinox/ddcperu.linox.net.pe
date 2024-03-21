<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgencyRequest;
use App\Http\Requests\AgencyUpdateRequest;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{

    protected $agency;

    public function __construct()
    {
        $this->agency = new Agency();
    }


    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->agency->query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());


        return inertia(
            'admin/agencies/index',
            [
                'title' => 'Sub Agencias',
                'subtitle' => 'Gestión de Sub agencias',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->agency->headers,
                'permissions' => config('app.permissions'),
            ]

        );
    }


    public function store(AgencyRequest $request)
    {
        try {
            $data = $request->all();
            Agency::create($data);
            return redirect()->back()->with('success', 'Agencia creada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al crear la agencia',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function update(AgencyUpdateRequest $request, string $id)
    {
        try {
            $user = Agency::findOrFail($id);
            $data = $request->all();
            $user->update($data);
            return redirect()->back()->with('success', 'Agencia actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al actualizar la agencia',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function destroy(string $id)
    {
        try {


            $agency = Agency::findOrFail($id);
            //si tiene alguna relacion no se puede eliminar
            if ($agency->users()->count() > 0) {
                return redirect()->back()->with([
                    'alert' => 'No se puede eliminar la agencia, tiene usuarios relacionados'
                ]);
            }

            $agency->delete();
            return redirect()->back()->with('success', 'Agencia eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al eliminar la agencia',
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function changeState(string $id)
    {
        $user = Agency::find($id);
        $user->is_enabled = !$user->is_enabled;
        $user->save();
        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
