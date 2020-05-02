<?php


namespace Omarrida\Scribe;


use ReflectionClass;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class ApiDoc
{
    protected $routes;

    protected $output;

    public function __construct($documentedRouteCollection)
    {
        $this->routes = $documentedRouteCollection;
    }

    public static function generate(Router $router)
    {
        $routes = self::getApiRoutes($router);

        return self::fromRoutes($routes);
    }

    private static function fromRoutes(array $routes): self
    {
        $routes = array_map(function ($route) {
            return new DocumentedRoute($route);
        }, $routes);

        return new self($routes);
    }

    private static function getApiRoutes(Router $router)
    {
        $routes = collect($router->getRoutes())
            ->reject(function ($route) {
                return !str_contains(self::getMiddleware($route), 'api')
                    || str_contains($route->getName(), 'nova');
            })
            ->map(function ($route) {
                return self::getRouteInformation($route);
            })->filter()->all();

        // Rejecting routes results in a non-sequentially keyed array
        // so we re-key it before returning the function output.
        return array_values($routes);
    }

    private static function getRouteInformation($route)
    {
        $formRequestTypeHint = $route->signatureParameters();

        $rules = [];

        if (!empty($formRequestTypeHint)) {

            $class = $route->signatureParameters()[0]->getType();

            if (null !== $class) {
                $formRequestClassName = $class->getName();
            }

            try {
                $reflectionFormRequest = new ReflectionClass($formRequestClassName);

                if ($reflectionFormRequest->hasMethod('rules')) {
                    $rules = (new $formRequestClassName)->rules();
                }
            } catch (ReflectionException $exception) {
                //
            } catch (\ErrorException $exception) {
                //
            }
        }

        return [
            'domain' => $route->domain(),
            'method' => implode('|', array_diff($route->methods(), ['HEAD'])),
            'uri' => $uri = $route->uri(),
            'action' => ltrim($route->getActionName(), '\\'),
            'middleware' => self::getMiddleware($route),
            'rules' => $rules,
            'success_response' => ResponseMaker::success($route, $rules),
        ];
    }

    private static function getMiddleware(Route $route): string
    {
        return collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->implode(',');
    }

    public function toMarkdown(): string
    {
        return (new MarkdownFormatter($this))->format();
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function autogeneratedResponseCount()
    {
        return collect($this->routes())->reject(function (DocumentedRoute $route) {
            return null === $route->successResponse();
        })->count();
    }
}
