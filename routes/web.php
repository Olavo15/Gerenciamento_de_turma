<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE DE APP_ENV == lOCAL
Route::get('/coverage/{any?}', function (string $any = 'index.html') {
    $path = base_path("coverage-report/{$any}");

    if (file_exists($path) && env('APP_ENV') === 'local') {
        /** @var false|string $mimeType */
        $mimeType = mime_content_type($path);

        if ($mimeType == 'text/plain' || $mimeType == 'application/x-empty') {
            $mimeType = 'text/css';
        }

        return response()->file($path, [
            'Content-Type' => $mimeType,
        ]);
    }

    abort(404, 'Não encontrado!');
})
    ->where('any', '.*')
    ->name('code.coverage');
