<?php

namespace App\Http\Controllers\Apps;

use App\Helpers\Menu;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
            ->paginate(30)
            ->onEachSide(1)
            ->withQueryString();

        return view('apps.roles.index', [
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
    public function create()
    {
        $routes = collect(Menu::make('app', 'false'))->map(function ($item) {
            return [
                'id' => $item['route'],
                'label' => $item['route']
            ];
        });

        return view('apps.roles.form', [
            'formAction' => 'apps.roles.store',
            'routes' => $routes,
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
        $routes = collect(Menu::make('app', 'false'))->map(function ($item) {
            return [
                'id' => $item['route'],
                'label' => $item['route']
            ];
        });

        return view('apps.roles.form', [
            'formAction' => route('apps.roles.update', $id->id),
            'routes' => $routes,
            'data' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        dd(collect($request->all()), $id);
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
