<?php


namespace Omarrida\Scribe;


use Zttp\Zttp;
use ReflectionClass;
use ReflectionException;
use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Console\Command;

class GenerateDocsCommand extends Command
{
    protected $signature = 'scribe:generate';

    protected $description = 'Generate API documentation.';

    protected Router $router;

    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
    }

    public function handle(): void
    {
        $routes = $this->getApiRoutes();
        $this->info('Writing docs for ' . count($routes) . ' routes.');

        $documentation = ApiDoc::fromRoutes($routes)->toMarkdown();

        $outputFile = fopen('scribe.md', 'w');
        fwrite($outputFile, $documentation);
        fclose($outputFile);

        $this->info('Docs updated successfully.');
    }

    protected function getRouteInformation(Route $route)
    {
        $formRequestTypeHint = $route->signatureParameters();

        $rules = [];

        if (! empty($formRequestTypeHint)) {
            $formRequestClassName = $route->signatureParameters()[0]->getType()->getName();

            try {
                $reflectionFormRequest = new ReflectionClass($formRequestClassName);

                if ($reflectionFormRequest->hasMethod('rules')) {
                    $rules = (new $formRequestClassName)->rules();
                }
            } catch (ReflectionException $exception) {
                //
            }
        }

        return [
            'domain' => $route->domain(),
            'method' => implode('|', $route->methods()),
            'uri'    => $uri = $route->uri(),
            'action' => ltrim($route->getActionName(), '\\'),
            'middleware' => $this->getMiddleware($route),
            'rules' => $rules,
            'success_response' => $this->getSuccessResponse($uri, $rules),
        ];
    }

    protected function getMiddleware(Route $route): string
    {
        return collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->implode(',');
    }

    protected function getApiRoutes(): array
    {
        $routes = collect($this->router->getRoutes())
            ->reject(function ($route) {
                return !Str::contains($this->getMiddleware($route), 'api')
                    || Str::contains($route->getName(), 'nova');
            })
            ->map(function ($route) {
                return $this->getRouteInformation($route);
            })->filter()->all();

        // Rejecting routes results in a non-sequentially keyed array
        // so we re-key it before returning the function output.
        return array_values($routes);
    }

    private function getSuccessResponse($uri, $rules)
    {
        $body = $this->guessValidParams($rules);

        return Zttp::withOptions(['verify' => false])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(config('app.url') . '/' . $uri, $body)
            ->json();
    }

    private function guessValidParams($rules): array
    {
        return collect($rules)->map(function ($rules, $field) {
            $this->guessValidParamForRule($rules, $field);
        })->toArray();
    }

    private function guessValidParamForRule($rules, $field)
    {
        return (new ParamGuesser())->pass($rules, $field);
    }
}
