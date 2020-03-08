<?php


namespace Omarrida\Scribe;


use Faker\Factory;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Validation\ValidationRuleParser;

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

        $parsedRule = ValidationRuleParser::parse($rules);

        if ($this->wantsEnum($parsedRule) && isset($parsedRule[1][0])) {
            // Get the first permitted enum value from the rule array.
            return $parsedRule[1][0];
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

    private function wantsEnum($parsedRule)
    {
        if (! is_array($parsedRule)) {
            return false;
        }

        return str_contains(strtolower($parsedRule[0]), 'in');
    }
}