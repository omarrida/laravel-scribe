<?php


namespace Omarrida\Scribe;


class ApiDoc
{
    /**
     * @var
     */
    protected $routes;

    /**
     * @var
     */
    protected $output;

    /**
     * ApiDoc constructor.
     *
     * @param $documentedRouteCollection
     */
    public function __construct($documentedRouteCollection)
    {
        $this->routes = $documentedRouteCollection;
    }

    /**
     * @param array $routes
     * @return \App\ApiDoc
     */
    public static function fromRoutes(array $routes): ApiDoc
    {
        $routes = array_map(function ($route) {
            return new DocumentedRoute($route);
        }, $routes);

        return new self($routes);
    }


    /**
     * @return string
     */
    public function toMarkdown(): string
    {
        return (new MarkdownFormatter($this))->format();
    }

    /**
     * @return array
     */
    public function routes(): array
    {
        return $this->routes;
    }
}