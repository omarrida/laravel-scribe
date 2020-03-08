<?php


namespace Omarrida\Scribe;


use Faker\Factory;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ParamGuesser
{
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function pass($rules, $field)
    {
        if ($fakeData = $this->tryFaker($field)) {
            return $fakeData;
        }

        return 'Omar';
    }

    private function tryFaker($field)
    {
        try {
            $str = str_replace('_', '', ucwords($field, ''));

            $str = lcfirst($str);

            return $this->faker->$str;
        } catch (InvalidArgumentException $exception) {
            //
        }
    }
}