<?php


namespace Omarrida\Scribe;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ScribeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindCommand();

        $this->registerViews();

        $this->registerRoutes();
    }

    private function bindCommand(): void
    {
        $this->app->bind('command.scribe:generate', GenerateDocsCommand::class);

        $this->commands([
            'command.scribe:generate'
        ]);
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/Views/');
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'prefix' => 'docs'
        ];
    }
}