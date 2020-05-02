<?php


namespace Omarrida\Scribe\Http;


use Omarrida\Scribe\ApiDoc;

class ShowDocs
{
    public function __invoke()
    {
        return ApiDoc::generate(app('router'))->toMarkdown();
    }
}