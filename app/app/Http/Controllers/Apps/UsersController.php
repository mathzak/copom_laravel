<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::where('name', 'ilike', "%$request->search%")
            ->orWhere('email', 'ilike', "%$request->search%")
            ->orderBy('name')
            ->paginate(20)
            ->onEachSide(1)
            ->withQueryString();

        return view('apps.users.index', [
            'items' => $users,
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
        return view('apps.users.form', [
            'parent_route' => 'apps.users.index',
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
    public function edit(User $id)
    {
        return view('apps.users.form', [
            'parent_route' => 'apps.users.index',
            'data' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $id)
    {
        $request->validateWithBag('userDeletion', [
            'action' => ['required', 'current_password'],
        ]);
    }
}
