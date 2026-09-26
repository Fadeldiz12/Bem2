<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menandai halaman privat (login, admin, modul RAB, API) agar tidak
 * dimasukkan ke hasil pencarian Google/Bing lewat header X-Robots-Tag.
 */
class NoIndex
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
