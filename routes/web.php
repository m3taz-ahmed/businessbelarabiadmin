<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/file/{path}', function ($path) {
    // dd("ASdf");
    $disk = config('filesystems.default');
    if ($disk !== 's3') {
        abort(404);
    }
    $file = Storage::disk('s3')->get($path);
    $tmpFile = tempnam(sys_get_temp_dir(), 's3file');
    file_put_contents($tmpFile, $file);
    $mime = mime_content_type($tmpFile);
    unlink($tmpFile);
    return response($file, 200)->header('Content-Type', $mime);
})->where('path', '.*');
