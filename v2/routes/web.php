<?php

use Illuminate\Support\Facades\Route;

/**
 * --------------------------------------------------------------------------
 * Local Storage Virtual Route (Windows Compatibility)
 * --------------------------------------------------------------------------
 * 
 * This route is a WORKAROUND for local Windows development environments where
 * the standard `public/storage` symbolic link results in 403 Forbidden errors
 * due to web server permission restrictions.
 * 
 * It intercepts requests to `/assets/storage/{path}` and manually serves the
 * file from `storage_path('app/public/{path}')`.
 * 
 * TODO: Remove this route when deploying to a Production environment with
 * proper Nginx/Apache symlink support, or when switching to S3.
 */
Route::get('/assets/storage/{path}', function ($path) {
    $filePath = storage_path("app/public/{$path}");
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    abort(404);
})->where('path', '.*');

Route::get('/{any}', function () {
    return view('spa');
})->where('any', '.*');
