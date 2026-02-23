<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetBasePath
{
    /**
     * When running from a subdirectory (e.g. XAMPP: /nilamfyp/), strip the base path
     * from the request URI so Laravel routes match, and set the root URL for links.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $basePath = config('app.base_path');

        if ($basePath !== '' && $basePath !== null) {
            $uri = $request->getRequestUri();
            $prefix = '/' . ltrim($basePath, '/');

            if (str_starts_with($uri, $prefix)) {
                $newUri = substr($uri, strlen($prefix)) ?: '/';
                $request->server->set('REQUEST_URI', $newUri);
                URL::forceRootUrl(config('app.url'));
            }
        }

        return $next($request);
    }
}
