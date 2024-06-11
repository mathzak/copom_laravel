<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $units = Unit::where('shortpath', 'ilike', "%$request->search%")
            ->withCount([
                'children', 'users',
                'users as users_all_count' => function ($query) {
                    $query->orWhere(function ($query) {
                        $query->whereRaw('unit_id IN (
                    SELECT (json_array_elements(u.children_id::json)::text)::bigint FROM units u WHERE u.id = units.id
                    )');
                        $query->where('unit_user.primary', true);
                    });
                },
            ])
            ->orderBy('shortpath')
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

        return view('index', [
            'index' => 'apps.units.index',
            'edit' => 'apps.units.edit',
            'label' => 'Units',
            'name' => [
                [
                    'field' => 'shortpath',
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
                    'url' => route("apps.units.create"),
                    'method' => "get",
                    'visible' => true,
                ],
                [
                    'icon' => "gmdi-remove-circle-outline",
                    'label' => __("Remove"),
                    'dataDeleted' => false,
                    'url' => route("apps.units.destroy"),
                    'method' => "delete",
                    'visible' => ($request->cookie('showItems') == null || $request->cookie('showItems') == 'both') ? true : false,
                ],
                [
                    'icon' => "gmdi-delete-forever-o",
                    'label' => __("Restore"),
                    'dataDeleted' => true,
                    'url' => route("apps.units.restore"),
                    'method' => "post",
                    'visible' => ($request->cookie('showItems') == 'both' || $request->cookie('showItems') == 'trashed') ? true : false,
                ],
                [
                    'icon' => "gmdi-delete-forever-o",
                    'label' => __("Erase"),
                    'dataDeleted' => true,
                    'url' => route("apps.units.forceDestroy"),
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
            'items' => $units,
            'columns' => [
                [
                    "name" => __("Subunits"),
                    "field" => "children_count",
                ],
                [
                    "name" => __("Local"),
                    "field" => "users_count",
                ],
                [
                    "name" => __("Total"),
                    "field" => "users_all_count",
                ],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $routes = [];

        return view('form', [
            'index' => 'apps.units.index',
            'label' => 'Units',
            'subLabel' => 'Add',
            'components' => [
                [
                    'label' => 'Role info',
                    'description' => "Edit the role with the necessary abilities to run system resources.",
                    'action' => route('apps.units.store'),
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
                                'class' => 'w-full',
                                'options' => $routes,
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
            $unit = new Unit();

            $unit->name = $request->name;
            $unit->description = $request->description;
            $unit->active = $request->active;
            $unit->abilities = collect(json_decode($request->all()['routes']))->pluck('id');

            $unit->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.units.edit', $unit->id)->with('status', trans_choice('Error on add selected item.|Error on add selected items.', 1));
        }

        return Redirect::route('apps.units.index')->with('status', trans_choice('{0} Nothing to add.|[1] Item added successfully.|[2,*] :total items successfully added.', 1));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        $units = Unit::orderBy('shortpath')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => $item->shortpath,
                ];
            });

        return view('form', [
            'index' => 'apps.units.index',
            'label' => 'Units',
            'subLabel' => 'Edit',
            'components' => [
                [
                    'label' => 'Role info',
                    'description' => "Edit the role with the necessary abilities to run system resources.",
                    'data' => $unit,
                    'action' => route('apps.roles.update', $unit->id),
                    'method' => 'patch',
                    'fields' => [
                        [
                            [
                                'name' => 'name',
                                'label' => 'Name',
                                'type' => 'input',
                                'class' => 'w-1/2',
                            ],
                            [
                                'name' => 'nickname',
                                'label' => 'Nickname',
                                'type' => 'input',
                                'class' => 'w-1/2 ml-4',
                            ],
                        ],
                        [
                            [
                                'name' => 'parent_id',
                                'label' => 'Parent unit',
                                'type' => 'select',
                                'class' => 'w-full',
                                'options' => $units,
                            ],
                        ],
                        [
                            [
                                'name' => 'founded',
                                'label' => 'Founded',
                                'type' => 'calendar',
                                'class' => 'w-1/3',
                            ],
                            [
                                'name' => 'active',
                                'label' => 'Active',
                                'type' => 'toggle',
                                'class' => 'w-1/3 ml-4',
                            ],
                            [
                                'name' => 'City_id',
                                'label' => 'Expires at',
                                'type' => 'calendar',
                                'class' => 'w-1/3 ml-4',
                            ],
                        ],
                        [
                            [
                                'name' => 'email',
                                'label' => 'Email',
                                'type' => 'input',
                                'class' => 'w-1/3',
                            ],
                            [
                                'name' => 'cellphone',
                                'label' => 'Cellphone',
                                'mask' => '(99) 9 9999-9999',
                                'type' => 'input',
                                'class' => 'w-1/2 ml-4',
                            ],
                            [
                                'name' => 'landline',
                                'label' => 'Landline',
                                'type' => 'input',
                                'class' => 'w-1/2 ml-4',
                            ],
                        ],
                        [
                            [
                                'name' => 'country_id',
                                'label' => 'Country',
                                'type' => 'input',
                                'class' => 'w-1/3',
                            ],
                            [
                                'name' => 'state_id',
                                'label' => 'State',
                                'type' => 'input',
                                'class' => 'w-1/3 ml-4',
                            ],
                            [
                                'name' => 'city_id',
                                'label' => 'City',
                                'type' => 'input',
                                'class' => 'w-1/3 ml-4',
                            ],
                        ],
                        [
                            [
                                'name' => 'postcode',
                                'label' => 'Postcode',
                                'type' => 'input',
                                'class' => 'w-1/4',
                            ],
                            [
                                'name' => 'address',
                                'label' => 'Address',
                                'type' => 'input',
                                'class' => 'w-2/4 ml-4',
                            ],
                            [
                                'name' => 'complement',
                                'label' => 'Complement',
                                'type' => 'input',
                                'class' => 'w-1/4 ml-4',
                            ],
                        ],
                        [
                            [
                                'name' => 'latitude',
                                'label' => 'Latitude',
                                'type' => 'input',
                                'class' => 'w-1/2',
                            ],
                            [
                                'name' => 'longitude',
                                'label' => 'Longitude',
                                'type' => 'input',
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
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        try {
            $unit->name = $request->name;
            $unit->description = $request->description;
            $unit->active = $request->active;
            $unit->abilities = collect(json_decode($request->all()['routes']))->pluck('id');

            $unit->save();
        } catch (\Exception $e) {
            report($e);

            return Redirect::route('apps.units.edit', $unit->id)->with('status', trans_choice('Error on edit selected item.|Error on edit selected items.', 1));
        }

        return Redirect::route('apps.units.index')->with('status', trans_choice('{0} Nothing to edit.|[1] Item edited successfully.|[2,*] :total items successfully edited.', 1));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Unit::whereIn('id', $request->values)->delete();

        return Redirect::route('apps.units.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Request $request)
    {
        Unit::whereIn('id', $request->values)->restore();

        return Redirect::route('apps.units.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forceDestroy(Request $request)
    {
        Unit::whereIn('id', $request->values)->forceDelete();

        return Redirect::route('apps.units.index')->with('status', trans_choice('{0} Nothing to remove.|[1] Item removed successfully.|[2,*] :total items successfully removed.', count($request->values), ['total' => count($request->values)]));
    }
}
