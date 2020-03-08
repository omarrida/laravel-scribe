<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;

class BasicGetStrategy
{
    public static function attempt($route)
    {
        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('app.url') . '/' . $route->uri())
            ->json();
    }
}