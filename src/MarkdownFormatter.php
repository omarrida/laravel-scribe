<?php

namespace Omarrida\Scribe;


class MarkdownFormatter
{
    private ApiDoc $apiDocs;

    private string $markdown;

    public function __construct(ApiDoc $docs)
    {
        $this->apiDocs = $docs;
    }

    public function format(): string
    {
        $this->markdown = '';

        $routesToFormat = $this->apiDocs->routes();

        array_walk($routesToFormat, function (DocumentedRoute $route) {
            $this->appendHeading($route)
                ->appendUri($route)
                ->appendMethod($route)
                ->appendRules($route)
                ->appendSeparator();
        });

        return $this->markdown;
    }

    private function appendHeading(DocumentedRoute $route): self
    {
        $this->markdown .= "\n";
        $this->markdown .= "### `{$route->uri()}`";

        return $this;
    }

    private function appendUri(DocumentedRoute $route): self
    {
        $this->markdown .= "\n";
        $this->markdown .= "uri: {$route->uri()}";

        return $this;
    }

    private function appendRules(DocumentedRoute $route): self
    {
        $routeParams = $route->rules();

        if (0 === count($routeParams)) {
            return $this;
        }

        $this->markdown .= "\n";
        $this->markdown .= "\n";
        $this->markdown .= "| Param | Rules |
| ---- | ---- |
";

        foreach ($routeParams as $param => $validationRules) {

            if (is_array($validationRules)) {
                array_map(function ($rule) {
                    return str_replace('|', ',', $rule);
                }, $validationRules);

                $validationRules = implode(',', $validationRules);
            }

            $validationRules = str_replace('|', ',', $validationRules);

            $this->markdown .= "|{$param}|{$validationRules}|";
            $this->markdown .= "\n";
        }

        return $this;
    }

    private function appendMethod(DocumentedRoute $route): MarkdownFormatter
    {
        $this->markdown .= "\n";
        $this->markdown .= "\n";
        $this->markdown .= "method: {$route->method()}";

        return $this;
    }

    private function appendSeparator()
    {
        $this->markdown .= "\n";
        $this->markdown .= '---';
        $this->markdown .= "\n";
    }
}