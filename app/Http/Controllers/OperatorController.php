<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    protected $operator;

    public function __construct()
    {
        $this->operator = new Operator();
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $query = $this->operator->query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        return inertia(
            'admin/administrators/index',
            [
                'title' => 'Operadores',
                'subtitle' => 'Gestión de operadores',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->operator->headers,
                'permissions' => config('app.permissions'),
            ]

        );
    }
}
