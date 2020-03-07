<?php


namespace Omarrida\Scribe;


class ApiDoc
{
    protected $routes;

    protected $output;

    public function __construct($documentedRouteCollection)
    {
        $this->routes = $documentedRouteCollection;
    }

    public static function fromRoutes(array $routes): ApiDoc
    {
        $routes = array_map(function ($route) {
            return new DocumentedRoute($route);
        }, $routes);

        return new self($routes);
    }


    public function toMarkdown(): string
    {
        return (new MarkdownFormatter($this))->format();
    }

    public function routes(): array
    {
        return $this->routes;
    }
}
