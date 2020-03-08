<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthedGetStrategy
{
    public static function attempt($route)
    {
        $token = JWTAuth::fromUser(factory(User::class)->create());

        return Zttp::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->get(config('app.url') . '/' . $route->uri())
            ->json();
    }
}