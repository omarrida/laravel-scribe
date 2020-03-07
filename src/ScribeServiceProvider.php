<?php


namespace Omarrida\Scribe;


use Illuminate\Support\ServiceProvider;

class ScribeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindCommand();
    }

    private function bindCommand(): void
    {
        $this->app->bind('command.scribe:generate', GenerateDocsCommand::class);

        $this->commands([
            'command.scribe:generate'
        ]);
    }
}