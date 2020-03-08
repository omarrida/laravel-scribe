<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;
use Illuminate\Routing\Route;

class ResponseMaker
{
    public static function success(Route $route, $rules)
    {
        $body = self::guessValidParams($rules);

        switch (self::getMethod($route)) {
            case 'GET':
                return Zttp::withOptions(['verify' => false])
                    ->withHeaders(['Accept' => 'application/json'])
                    ->get(config('app.url') . '/' . $route->uri())
                    ->json();
            case 'POST':
                return Zttp::withOptions(['verify' => false])
                    ->withHeaders(['Accept' => 'application/json'])
                    ->post(config('app.url') . '/' . $route->uri(), $body)
                    ->json();
            default:
                $response = null;
                break;
        }
    }

    private static function guessValidParams($rules): array
    {
        return collect($rules)->map(function ($rules, $field) {
            return (new ParamGuesser())->pass($rules, $field);
        })->toArray();
    }

    private static function getMethod(Route $route)
    {
        return array_diff($route->methods(), ['HEAD'])[0];
    }
}