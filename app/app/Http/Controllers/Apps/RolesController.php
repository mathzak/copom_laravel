<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::where('name', 'ilike', "%$request->search%")
            ->orderBy('name')
            ->paginate(30)
            ->onEachSide(1)
            ->withQueryString();

        return view('apps.roles.index', [
            'items' => $roles,
            'columns' => [
                [
                    "name" => __("Created at"),
                    "field" => "created_at",
                ]
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apps.roles.form', [
            'parent_route' => 'apps.roles.index',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $id)
    {
        return view('apps.roles.form', [
            'parent_route' => 'apps.roles.index',
            'data' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $id)
    {
        $request->validateWithBag('userDeletion', [
            'action' => ['required', 'current_password'],
        ]);
    }
}
