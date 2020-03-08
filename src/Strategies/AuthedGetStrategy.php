<?php


namespace Omarrida\Scribe\Strategies;


use Zttp\Zttp;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthedGetStrategy implements GetStrategy
{
    public static function attempt($route)
    {
        $token = JWTAuth::fromUser(factory(\App\Auth\User::class)->create());

        return Zttp::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->get(config('app.url') . '/' . $route->uri());
    }
}
