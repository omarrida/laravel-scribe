<?php


namespace Omarrida\Scribe\Strategies;

use Zttp\Zttp;

class BasicPostStrategy implements PostStrategy
{
    public static function attempt($route, $body)
    {
        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('app.url') . '/' . $route->uri(), $body);
    }
}