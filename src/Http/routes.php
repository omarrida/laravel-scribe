<?php


use Omarrida\Scribe\Http\ShowDocs;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowDocs::class);