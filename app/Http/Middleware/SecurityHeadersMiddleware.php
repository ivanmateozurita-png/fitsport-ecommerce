<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para agregar cabeceras de seguridad HTTP
 * Esto mejora la protección contra ataques XSS, clickjacking, etc.
 */
class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Evitar que la pagina se cargue en un iframe (clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Evitar que el navegador adivine el tipo de archivo
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Activar filtro XSS del navegador
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Forzar HTTPS en produccion
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Controlar que info se envia en el header Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Desactivar APIs del navegador que no usamos
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
