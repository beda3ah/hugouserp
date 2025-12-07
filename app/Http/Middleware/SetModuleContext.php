<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetModuleContext
 *
 * - Picks a module key from route param {moduleKey} or header 'X-Module-Key'.
 * - Stores it into request + container (string key), to be used by controllers/services.
 *
 * Usage alias: 'module.ctx'
 */
class SetModuleContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $moduleKey = (string) ($request->route('moduleKey') ?? $request->headers->get('X-Module-Key', ''));

        if ($moduleKey === '') {
            // Not all routes require module context, so pass through silently.
            return $next($request);
        }

        $request->attributes->set('module.key', $moduleKey);
        app()->instance('req.module_key', $moduleKey);

        return $next($request);
    }
}
