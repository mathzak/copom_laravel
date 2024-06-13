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
            ->when($cookie = $request->cookie('showItems') ?? null, function ($query, $cookie) {
                if ($cookie == 'both') {
                    $query->withTrashed();
                } elseif ($cookie == 'trashed') {
                    $query->onlyTrashed();
                }
            })
            ->paginate(30)
            ->onEachSide(1)
            ->withQueryString();

        return view('bood4ll', [
            'index' => 'apps.roles.index',
            'label' => 'Roles',
            'components' => [
                [
                    'type' => 'index',
                    'label' => 'Roles',
                    'description' => "Manage roles registered in the system.",
                    'data' => $roles,
                    'action' => 'apps.roles.edit',
                    'nameColumn' => [
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
                    'menu' => [
                        [
                            'icon' => "gmdi-add-circle-outline",
                            'label' => __("Add"),
                            'dataDeleted' => null,
                            'url' => route("apps.roles.create"),
                            'method' => "get",
                            'visible' => true,
                        ],
                        [
                            'icon' => "gmdi-remove-circle-outline",
                            'label' => __("Remove"),
                            'dataDeleted' => false,
                            'url' => route("apps.roles.destroy"),
                            'method' => "delete",
                            'visible' => true,
                            'visible' => ($request->cookie('showItems') == null || $request->cookie('showItems') == 'both') ? true : false,
                        ],
                        [
                            'icon' => "gmdi-delete-forever-o",
                            'label' => __("Restore"),
                            'dataDeleted' => true,
                            'url' => route("apps.roles.restore"),
                            'method' => "post",
                            'visible' => ($request->cookie('showItems') == 'both' || $request->cookie('showItems') == 'trashed') ? true : false,
                        ],
                        [
                            'icon' => "gmdi-delete-forever-o",
                            'label' => __("Erase"),
                            'dataDeleted' => true,
                            'url' => route("apps.roles.forceDestroy"),
                            'method' => "delete",
                            'visible' => ($request->cookie('showItems') == 'both' || $request->cookie('showItems') == 'trashed') ? true : false,
                        ],
                        [
                            'icon' => "gmdi-folder-o",
                            'label' => __("Only active"),
                            'dataDeleted' => null,
                            'url' => route("unsetCookie", [
                                'name' => 'showItems',
                            ]),
                            'method' => "get",
                            'visible' => ($request->cookie('showItems') == 'both' || $request->cookie('showItems') == 'trashed') ? true : false,
                        ],
                        [
                            'icon' => "gmdi-folder-delete-o",
                            'label' => __("Only removed"),
                            'dataDeleted' => null,
                            'url' => route("setCookie", [
                                'name' => 'showItems',
                                'value' => 'trashed',
                            ]),
                            'method' => "get",
                            'visible' => ($request->cookie('showItems') != 'trashed') ? true : false,
                        ],
                        [
                            'icon' => "gmdi-rule-folder-o",
                            'label' => __("Show all"),
                            'dataDeleted' => null,
                            'url' => route("setCookie", [
                                'name' => 'showItems',
                                'value' => 'both',
                            ]),
                            'method' => "get",
                            'visible' => ($request->cookie('showItems') != 'both') ? true : false,
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
        $routes = collect(Menu::make('app', 'false'))->map(function ($item) {
            return [
                'id' => $item['route'],
                'label' => $item['route']
            ];
        });

        return view('bood4ll', [
            'index' => 'apps.roles.index',
            'label' => 'Roles',
            'subLabel' => 'Add',
            'components' => [
                [
                    'type' => 'form',
                    'label' => 'Main data',
                    'description' => "Add a role with the necessary abilities to run system resources.",
                    'action' => route('apps.roles.store'),
                    'method' => 'post',
                    'fields' => [
                        [
                            [
                                'name' => 'name',
                                'label' => 'Name',
                                'type' => 'input',
                                'class' => 'w-full',
                            ],
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
                            ],
                        ],
                        [
                            [
                                'name' => 'abilities',
                                'label' => 'Abilities',
                                'type' => 'select',
                                'multiple' => true,
                                'class' => 'w-full',
                                'options' => $routes,
                            ],
                        ],
                        [
                            [
                                'name' => 'full_access',
                                'label' => 'Full access',
                                'type' => 'toggle',
                                'class' => 'w-1/2',
                            ],
                            [
                                'name' => 'manage_nested',
                                'label' => 'Manage nested',
                                'type' => 'toggle',
                                'class' => 'w-1/2 ml-4',
                            ],
                        ],
                    ],
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
            $role->full_access = $request->full_access;
            $role->manage_nested = $request->manage_nested;
            $role->abilities = collect($request->abilities)->toJson();

            $role->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.roles.create', $role->id)->with('error', trans_choice('Error on add this item.|Error on add the items.', 1));
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

        return view('bood4ll', [
            'index' => 'apps.roles.index',
            'label' => 'Roles',
            'subLabel' => 'Edit',
            'components' => [
                [
                    'type' => 'form',
                    'label' => 'Main data',
                    'description' => "Edit the role with the necessary abilities to run system resources.",
                    'data' => $role,
                    'action' => route('apps.roles.update', $role->id),
                    'method' => 'patch',
                    'fields' => [
                        [
                            [
                                'name' => 'name',
                                'label' => 'Name',
                                'type' => 'input',
                                'class' => 'w-full',
                            ],
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
                            ],
                        ],
                        [
                            [
                                'name' => 'abilities',
                                'label' => 'Abilities',
                                'type' => 'select',
                                'multiple' => true,
                                'class' => 'w-full',
                                'options' => $routes,
                            ],
                        ],
                        [
                            [
                                'name' => 'full_access',
                                'label' => 'Full access',
                                'type' => 'toggle',
                                'class' => 'w-1/2',
                            ],
                            [
                                'name' => 'manage_nested',
                                'label' => 'Manage nested',
                                'type' => 'toggle',
                                'class' => 'w-1/2 ml-4',
                            ],
                        ],
                    ],
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
            $role->full_access = $request->full_access;
            $role->manage_nested = $request->manage_nested;
            $role->abilities = collect($request->abilities)->toJson();

            $role->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.roles.edit', $role->id)->with('error', trans_choice('Error on edit selected item.|Error on edit selected items.', 1));
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
