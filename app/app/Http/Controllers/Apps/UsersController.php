<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $users = User::where('name', 'ilike', "%$request->search%")
            ->orWhere('email', 'ilike', "%$request->search%")
            ->orderBy('name')
            ->paginate(20)
            ->onEachSide(1)
            ->withQueryString();

        return view('bood4ll', [
            'index' => 'apps.users.index',
            'label' => 'Users',
            'components' => [
                [
                    'type' => 'index',
                    'label' => 'Users',
                    'description' => "Manage users registered in the system.",
                    'data' => $users,
                    'action' => 'apps.users.edit',
                    'nameColumn' => [
                        [
                            'field' => 'name',
                            'class' => '',
                        ],
                        [
                            'field' => 'email',
                            'class' => 'text-sm',
                        ],                [
                            'field' => 'created_at',
                            'class' => 'text-xs',
                        ],
                        [
                            'field' => 'updated_at',
                            'class' => 'text-xs',
                        ],
                        [
                            'field' => 'deleted_at',
                            'class' => 'text-xs',
                        ],
                    ],
                    'columns' => [
                        [
                            "name" => __("Created at"),
                            "field" => "created_at",
                        ],
                    ],
                    'menu' => [
                        [
                            'icon' => "gmdi-add-circle-outline",
                            'label' => __("Add"),
                            'dataDeleted' => null,
                            'url' => route("apps.users.create"),
                            'method' => "get",
                        ],
                        [
                            'icon' => "gmdi-remove-circle-outline",
                            'label' => __("Remove"),
                            'dataDeleted' => false,
                            'url' => route("apps.users.destroy"),
                            'method' => "delete",
                        ],
                        [
                            'icon' => "gmdi-delete-forever-o",
                            'label' => __("Restore"),
                            'dataDeleted' => true,
                            'url' => route("apps.users.restore"),
                            'method' => "post",
                        ],
                        [
                            'icon' => "gmdi-delete-forever-o",
                            'label' => __("Erase"),
                            'dataDeleted' => true,
                            'url' => route("apps.users.forceDestroy"),
                            'method' => "delete",
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('apps.users.form', [
            'parent_route' => 'apps.users.index',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $user = new User();

            $user->name = $request->name;
            $user->description = $request->description;
            $user->active = $request->active;
            $user->abilities = collect(json_decode($request->all()['routes']))->pluck('id');

            $user->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.users.edit', $user->id)->with('status', trans_choice('Error on add selected item.|Error on add selected items.', 1));
        }

        return Redirect::route('apps.users.index')->with('status', trans_choice('{0} Nothing to add.|[1] Item added successfully.|[2,*] :total items successfully added.', 1));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $id): View
    {
        return view('apps.users.form', [
            'parent_route' => 'apps.users.index',
            'data' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        try {
            $user->name = $request->name;
            $user->description = $request->description;
            $user->active = $request->active;
            $user->abilities = collect(json_decode($request->all()['routes']))->pluck('id');

            $user->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.users.edit', $user->id)->with('status', trans_choice('Error on edit selected item.|Error on edit selected items.', 1));
        }

        return Redirect::route('apps.users.index')->with('status', trans_choice('{0} Nothing to edit.|[1] Item edited successfully.|[2,*] :total items successfully edited.', 1));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        User::whereIn('id', $request->values)->delete();

        return Redirect::route('apps.users.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Request $request): RedirectResponse
    {
        User::whereIn('id', $request->values)->restore();

        return Redirect::route('apps.users.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forceDestroy(Request $request): RedirectResponse
    {
        User::whereIn('id', $request->values)->forceDelete();

        return Redirect::route('apps.users.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }
}
