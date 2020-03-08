<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;

class BasicPostStrategy
{
    public static function attempt($route, $body)
    {
        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('app.url') . '/' . $route->uri(), $body);
    }
}