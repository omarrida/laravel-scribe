<?php


namespace Omarrida\Scribe;


use Illuminate\Console\Command;

class GenerateDocsCommand extends Command
{
    protected $signature = 'scribe:generate';

    protected $description = 'Generate fresh API docs.';

    public function handle(): void
    {
        $this->info('generating some docs...');
    }
}