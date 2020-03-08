<?php


namespace Omarrida\Scribe\Strategies;

use Zttp\Zttp;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthedPostStrategy implements PostStrategy
{
    public static function attempt($route, $body)
    {
        $token = JWTAuth::fromUser(factory(\App\Auth\User::class)->create());

        return Zttp::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->post(config('app.url') . '/' . $route->uri(), $body);
    }
}