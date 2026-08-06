<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Serves files from the "public" disk (storage/app/public) over HTTP.
 *
 * Shared hosting (Hostinger included) disables PHP's symlink() function, so
 * `php artisan storage:link` can never create public/storage there — every
 * /storage/... image 404s. This controller answers those same URLs from PHP,
 * so Storage::url() / asset('storage/...') keep working untouched.
 *
 * Where the symlink does exist (local WAMP), Apache serves the file directly
 * and this controller is never reached.
 */
class StorageFileController extends Controller
{
    public function show(Request $request, string $path)
    {
        // Only ever read inside the public disk — reject traversal attempts
        if (str_contains($path, '..') || str_contains($path, "\0")) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);

        if (!is_file($fullPath)) {
            abort(404);
        }

        // Uploaded names are unique (uniqid), so the bytes never change — cache hard
        // to keep repeat page views off PHP entirely.
        $response = response()->file($fullPath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);

        $response->setAutoLastModified();
        $response->setAutoEtag();
        $response->isNotModified($request);

        return $response;
    }
}
