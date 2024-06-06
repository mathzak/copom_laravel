<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use stdClass;

class AppsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appsRoutes = collect(Route::getRoutes()->get())
            ->filter(fn ($item) => strstr($item->action['as'] ?? null, 'apps') && strstr($item->action['as'] ?? null, 'index'));

        // dd($appsRoutes);

        foreach ($appsRoutes as $route) {
            $items[] = [
                'label' => $route->defaults['label'],
                'description' => $route->defaults['description'],
                'url' => route($route->action['as']),
            ];
        };

        return view('apps.index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
