<?php


namespace Omarrida\Scribe\Strategies;


interface PostStrategy
{
    public static function attempt($route, $body);
}