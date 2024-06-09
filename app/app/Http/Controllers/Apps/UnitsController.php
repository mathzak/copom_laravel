<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
            ->paginate(30)
            ->onEachSide(1)
            ->withQueryString();

        return view('apps.units.index', [
            'menu' => [
                [
                    'icon' => "gmdi-add-circle-outline",
                    'label' => __("Add"),
                    'dataDeleted' => null,
                    'url' => route("apps.units.create"),
                    'method' => "get",
                ],
                [
                    'icon' => "gmdi-remove-circle-outline",
                    'label' => __("Remove"),
                    'dataDeleted' => false,
                    'url' => route("apps.units.destroy"),
                    'method' => "delete",
                ],
                [
                    'icon' => "gmdi-delete-forever-o",
                    'label' => __("Restore"),
                    'dataDeleted' => true,
                    'url' => route("apps.units.restore"),
                    'method' => "post",
                ],
                [
                    'icon' => "gmdi-delete-forever-o",
                    'label' => __("Erase"),
                    'dataDeleted' => true,
                    'url' => route("apps.units.forceDestroy"),
                    'method' => "delete",
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
    public function create()
    {
        return view('apps.units.form', [
            'parent_route' => 'apps.units.index',
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
    public function edit(Unit $id)
    {
        return view('apps.units.form', [
            'parent_route' => 'apps.units.index',
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
    public function destroy(Request $request, Unit $id)
    {
        $request->validateWithBag('userDeletion', [
            'action' => ['required', 'current_password'],
        ]);
    }
}
