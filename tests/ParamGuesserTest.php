<?php


namespace Omarrida\Scribe\Tests;


use PHPUnit\Framework\TestCase;
use Omarrida\Scribe\ParamGuesser;

class ParamGuesserTest extends TestCase
{
    private ValidationMethods $rules;

    private ParamGuesser $guesser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = new ValidationMethods();
        $this->guesser = new ParamGuesser();
    }

    /** @test */
    public function it_handles_the_string_rule(): void
    {
        $rules = 'required|string';
        $field = 'first_name';

        $guess = $this->guesser->pass($rules, $field);

        $this->assertTrue($this->rules->validateString($field, $guess));
        $this->assertTrue($this->rules->validateRequired($field, $guess));
    }

//    /** @test */
//    public function it_handles_the_email_rule(): void
//    {
//        $rules = 'required,email:rfc,dns,unique:users,email,max:64';
//        $field = 'email';
//
//        $guess = $this->guesser->pass($rules, $field);
//
//        $this->assertTrue($this->rules->validateEmail($field, $guess, ['rfc', 'dns']));
//        $this->assertTrue($this->rules->validateRequired($field, $guess));
//    }

    /** @test */
    public function it_handles_the_in_rule(): void
    {
        $rules = 'nullable,string_short,in:personal,business';
        $field = 'account_type';

        $guess = $this->guesser->pass($rules, $field);

        $this->assertTrue($this->rules->validateIn($field, $guess, ['personal', 'business']));
        $this->assertTrue($this->rules->validateRequired($field, $guess));
    }
}