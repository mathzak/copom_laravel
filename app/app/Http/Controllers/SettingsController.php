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
        $cookie = Cookie::make($name, $value, $minutes);

        return back()->withCookie($cookie);
    }

    public function unsetCookie(Request $request, string $name)
    {
        $cookie = Cookie::make($name, null, -1);

        return back()->withCookie($cookie);
    }

    // $cookie = cookie()->forever('user_name', encrypt('John Doe'));

    // $userData = ['name' => 'John Doe', 'email' => 'john@example.com'];
    // $cookie = cookie('user_data', serialize($userData));

    // $cookie = cookie('user_name', 'John Doe', 60)->domain('.example.com')->path('/')->secure();


}
