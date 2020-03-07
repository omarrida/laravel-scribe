<?php

namespace Omarrida\Scribe;

class DocumentedRoute
{
    protected string $method;

    protected string $uri;

    protected array $rules;

    public function __construct(array $route)
    {
        $this->method = $route['method'];
        $this->uri = $route['uri'];
        $this->rules = $route['rules'];
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function rules(): array
    {
        return $this->rules;
    }
}
