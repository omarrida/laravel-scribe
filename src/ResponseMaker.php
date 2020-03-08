<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;
use Illuminate\Routing\Route;

class ResponseMaker
{
    public static function success(Route $route, $rules)
    {
        $body = self::guessValidParams($rules);

        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('app.url') . '/' . $route->uri(), $body)
            ->json();
    }

    private static function guessValidParams($rules): array
    {
        return collect($rules)->map(function ($rules, $field) {
            return (new ParamGuesser())->pass($rules, $field);
        })->toArray();
    }
}