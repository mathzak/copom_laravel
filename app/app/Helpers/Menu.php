<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class Menu
{
    public static function make($origin, $onlyIndexes = true)
    {
        $items = [];

        $routes = collect(Route::getRoutes()->get())
            ->filter(
                fn ($item) => $onlyIndexes === true
                    ? strstr($item->action['as'] ?? null, $origin) && strstr($item->action['as'] ?? null, 'index')
                    : strstr($item->action['as'] ?? null, $origin)
            );

        foreach ($routes as $route) {
            $items[] = [
                'label' => __($route->defaults['label'] ?? null),
                'description' => __($route->defaults['description'] ?? null),
                'icon' => $route->defaults['icon'] ?? null,
                'route' => $route->action['as'],
            ];
        };

        return $items;
    }
}
