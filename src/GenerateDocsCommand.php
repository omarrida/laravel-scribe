<?php


namespace Omarrida\Scribe;


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
        $documentation = ApiDoc::generate($this->router);

        $outputFile = fopen('scribe.md', 'w');
        fwrite($outputFile, $documentation->toMarkdown());
        fclose($outputFile);

        $this->info('Docs updated successfully.');
    }
}
