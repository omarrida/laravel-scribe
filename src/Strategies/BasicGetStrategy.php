<?php


namespace Omarrida\Scribe\Strategies;

use Zttp\Zttp;

class BasicGetStrategy implements GetStrategy
{
    public static function attempt($route)
    {
        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->get(config('app.url') . '/' . $route->uri());
    }
}
