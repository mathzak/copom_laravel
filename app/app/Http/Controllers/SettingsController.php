<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function setCookie(string $name, $value = null, $minutes = 60)
    {
        $cookie = cookie($name, is_array($value) ? serialize($value) : $value, $minutes)
            // ->domain('.example.com')->path('/')->secure()
        ;

        return back()->withCookie($cookie);
    }

    public function unsetCookie(string $name)
    {
        $cookie = cookie($name, null, -1);

        return back()->withCookie($cookie);
    }
}
