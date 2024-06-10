<?php

namespace App\Http\Controllers\Apps;

use App\Helpers\Menu;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redirect;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $roles = Role::where('name', 'ilike', "%$request->search%")
            ->withCount([
                'users' => function ($query) use ($request) {
                    $query->when($request->user()->cannot('isSuperAdmin', User::class), function ($query) use ($request) {
                        $query->join('unit_user', function (JoinClause $join) use ($request, $query) {
                            $join->on('unit_user.user_id', '=', 'role_user.user_id')->where('unit_user.primary', true);

                            if ($request->user()->cannot('hasFullAccess', [User::class, 'apps.roles.index'])) {
                                $query->where('unit_user.user_id', $request->user()->id);
                            }

                            $query->whereIn('unit_user.unit_id', $request->user()->unitsIds('apps.roles.index'));
                        });
                    });
                }
            ])
            ->orderBy('name')
            ->withTrashed()
            // ->onlyTrashed()
            ->paginate(30)
            ->onEachSide(1)
            ->withQueryString();

        return view('index', [
            'index' => 'apps.roles.index',
            'edit' => 'apps.roles.edit',
            'label' => 'Roles',
            'name' => [
                [
                    'field' => 'name',
                    'class' => '',
                ],
                [
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
            'menu' => [
                [
                    'icon' => "gmdi-add-circle-outline",
                    'label' => __("Add"),
                    'dataDeleted' => null,
                    'url' => route("apps.roles.create"),
                    'method' => "get",
                ],
                [
                    'icon' => "gmdi-remove-circle-outline",
                    'label' => __("Remove"),
                    'dataDeleted' => false,
                    'url' => route("apps.roles.destroy"),
                    'method' => "delete",
                ],
                [
                    'icon' => "gmdi-delete-forever-o",
                    'label' => __("Restore"),
                    'dataDeleted' => true,
                    'url' => route("apps.roles.restore"),
                    'method' => "post",
                ],
                [
                    'icon' => "gmdi-delete-forever-o",
                    'label' => __("Erase"),
                    'dataDeleted' => true,
                    'url' => route("apps.roles.forceDestroy"),
                    'method' => "delete",
                ],
            ],
            'items' => $roles,
            'columns' => [
                [
                    "name" => __("Active"),
                    "field" => "active",
                    "boolean" => true,
                ],
                [
                    "name" => __("Users"),
                    "field" => "users_count",
                ]
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $routes = collect(Menu::make('app', 'false'))->map(function ($item) {
            return [
                'id' => $item['route'],
                'label' => $item['route']
            ];
        });

        return view('form', [
            'index' => 'apps.roles.index',
            'label' => 'Roles',
            'descriptionLabel' => 'Profile Information',
            'descriptionText' => "Update your account's profile information and email address.",
            'formAction' => route('apps.roles.store'),
            'formMethod' => 'post',
            'formFields' => [
                [
                    [
                        'name' => 'name',
                        'label' => 'Name',
                        'type' => 'input',
                        'class' => 'w-full',
                    ]
                ],
                [
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'input',
                        'class' => 'w-3/4',
                    ],
                    [
                        'name' => 'active',
                        'label' => 'Active',
                        'type' => 'toggle',
                        'class' => 'w-1/4 ml-4',
                    ]
                ],
                [
                    [
                        'name' => 'routes',
                        'label' => 'Routes',
                        'type' => 'multiselect',
                        'class' => 'w-full',
                        'options' => $routes,
                    ]
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $role = new Role();

            $role->name = $request->name;
            $role->description = $request->description;
            $role->active = $request->active;
            $role->abilities = collect(json_decode($request->all()['routes']))->pluck('id');

            $role->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.roles.edit', $role->id)->with('status', trans_choice('Error on add selected item.|Error on add selected items.', 1));
        }

        return Redirect::route('apps.roles.index')->with('status', trans_choice('{0} Nothing to add.|[1] Item added successfully.|[2,*] :total items successfully added.', 1));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        $routes = collect(Menu::make('app', 'false'))->map(function ($item) {
            return [
                'id' => $item['route'],
                'label' => $item['route']
            ];
        });

        return view('form', [
            'index' => 'apps.roles.index',
            'label' => 'Roles',
            'descriptionLabel' => 'Profile Information',
            'descriptionText' => "Update your account's profile information and email address.",
            'data' => $role,
            'formAction' => route('apps.roles.update', $role->id),
            'formMethod' => 'patch',
            'formFields' => [
                [
                    [
                        'name' => 'name',
                        'label' => 'Name',
                        'type' => 'input',
                        'class' => 'w-full',
                    ]
                ],
                [
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'input',
                        'class' => 'w-3/4',
                    ],
                    [
                        'name' => 'active',
                        'label' => 'Active',
                        'type' => 'toggle',
                        'class' => 'w-1/4 ml-4',
                    ]
                ],
                [
                    [
                        'name' => 'abilities',
                        'label' => 'Abilities',
                        'type' => 'multiselect',
                        'class' => 'w-full',
                        'options' => $routes,
                    ]
                ],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        try {
            $role->name = $request->name;
            $role->description = $request->description;
            $role->active = $request->active;
            $role->abilities = collect(json_decode($request->all()['routes']))->pluck('id');

            $role->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.roles.edit', $role->id)->with('status', trans_choice('Error on edit selected item.|Error on edit selected items.', 1));
        }

        return Redirect::route('apps.roles.index')->with('status', trans_choice('{0} Nothing to edit.|[1] Item edited successfully.|[2,*] :total items successfully edited.', 1));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Role::whereIn('id', $request->values)->delete();

        return Redirect::route('apps.roles.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Request $request): RedirectResponse
    {
        Role::whereIn('id', $request->values)->restore();

        return Redirect::route('apps.roles.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forceDestroy(Request $request): RedirectResponse
    {
        Role::whereIn('id', $request->values)->forceDelete();

        return Redirect::route('apps.roles.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }
}
