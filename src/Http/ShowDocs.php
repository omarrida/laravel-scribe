<?php


namespace Omarrida\Scribe\Http;


use Parsedown;
use Omarrida\Scribe\ApiDoc;
use Illuminate\Support\Facades\Cache;

class ShowDocs
{
    public function __invoke()
    {
        $docs = $this->docs();

        return view('scribe::index', ['docs' => $docs]);
    }

    private function docs()
    {
        $markdown = $this->markdown();

        return (new Parsedown)->text($markdown);
    }

    private function markdown()
    {
        if (Cache::has('docs')) {
            $docs = Cache::get('docs');
        } else {
            $docs = ApiDoc::generate(app('router'))->toMarkdown();
            Cache::put('docs', $docs, 5000);
        }

        return $docs;
    }
}