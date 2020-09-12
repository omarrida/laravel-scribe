<?php

namespace Omarrida\Scribe;

class DocumentedRoute
{
    protected string $method;

    protected string $uri;

    protected array $rules;

    protected $successResponse;

    public function __construct(array $route)
    {
        $this->method = $route['method'];
        $this->uri = $route['uri'];
        $this->rules = $route['rules'];
//        $this->successResponse = $route['success_response'];
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

    public function successResponse(): ?string
    {
        if (null === $this->successResponse) {
            return null;
        }

        return json_encode($this->successResponse, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512);
    }
}
