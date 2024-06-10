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
            ->paginate(30)
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
    public function create(): View
    {
        return view('apps.units.form', [
            'parent_route' => 'apps.units.index',
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
    public function edit(Unit $id): View
    {
        return view('apps.units.form', [
            'parent_route' => 'apps.units.index',
            'data' => $id,
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
