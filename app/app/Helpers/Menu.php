<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class Menu
{
    public static function make($origin)
    {
        $routes = collect(Route::getRoutes()->get())
            ->filter(fn ($item) => strstr($item->action['as'] ?? null, $origin) && strstr($item->action['as'] ?? null, 'index'));

        foreach ($routes as $route) {
            $items[] = [
                'label' => __($route->defaults['label']),
                'description' => __($route->defaults['description']),
                'icon' => $route->defaults['icon'],
                'url' => route($route->action['as']),
            ];
        };

        return $items;
    }
}
