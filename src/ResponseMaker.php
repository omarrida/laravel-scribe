<?php


namespace Omarrida\Scribe;


use App\Auth\User;
use Illuminate\Routing\Route;
use Tymon\JWTAuth\Facades\JWTAuth;
use Zttp\Zttp;

class ResponseMaker
{
    public static function success(Route $route, $rules)
    {
        switch (self::getMethod($route)) {
            case 'GET':
                return self::tryGetRequest($route);
            case 'POST':
                return self::tryPostRequest($route, self::guessValidParams($rules));
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

    private static function tryGetRequest(Route $route)
    {
        $strategies = [
            BasicGetStrategy::class,
            AuthedGetStrategy::class
        ];

        $response = null;

        collect($strategies)->each(function ($strategy) use ($route, &$response) {
            $try = $strategy::attempt($route);

            if ($try->isOk()) {
                $response = $try->json();
            }
        });

        return $response;
    }

    private static function tryPostRequest(Route $route, $body)
    {
        $strategies = [
            BasicPostStrategy::class,
            AuthedPostStrategy::class
        ];

        $response = null;

        collect($strategies)->each(function ($strategy) use ($route, $body, &$response) {
            $try = $strategy::attempt($route, $body);

            if ($try->isOk()) {
                $response = $try->json();
            }
        });

        return $response;
    }
}
