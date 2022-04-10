<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use \Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

function get_languages()
{
    Language::selectActiveValue()->Selection();
}

function get_default_languages()
{
    return Config::get('laravellocalization.supportedLocales');
}

function get_current_local()
{
    return Config::get('app.locale');
}

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}

function getRole(Request $request)
{
    $roles = $request->roles;
    return LaratrustRole::middleware('role', $roles,);
}
