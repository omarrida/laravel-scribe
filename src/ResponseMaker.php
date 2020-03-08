<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;

class ResponseMaker
{
    public static function success($uri, $rules)
    {
        $body = self::guessValidParams($rules);

        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('app.url') . '/' . $uri, $body)
            ->json();
    }

    private function guessValidParams($rules): array
    {
        return collect($rules)->map(function ($rules, $field) {
            return (new ParamGuesser())->pass($rules, $field);
        })->toArray();
    }
}