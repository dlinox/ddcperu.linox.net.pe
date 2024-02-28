<?php

namespace App\Http\Controllers;

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
                'title' => 'Agenicas',
                'subtitle' => 'Gestión de Agencias',
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


    public function store(Request $request)
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

    public function update(Request $request, string $id)
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
            $user = Agency::findOrFail($id);
            $user->delete();
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
        $user->status = !$user->status;
        $user->save();
        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }
}
