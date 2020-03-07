<?php

namespace Omarrida\Scribe;

class DocumentedRoute
{
    /**
     * @var string
     */
    protected string $method;

    /**
     * @var string
     */
    protected string $uri;

    /**
     * @var array
     */
    protected array $rules;

    /**
     * DocumentedRoute constructor.
     *
     * @param array $route
     */
    public function __construct(array $route)
    {
        $this->method = $route['method'];
        $this->uri = $route['uri'];
        $this->rules = $route['rules'];
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return $this->rules;
    }
}
