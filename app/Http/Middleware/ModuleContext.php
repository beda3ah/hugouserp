<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleContext
{
    /**
     * Handle an incoming request and ensure module context is set.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure module_context exists in session
        if (! session()->has('module_context')) {
            session(['module_context' => 'all']);
        }

        // Allow changing context via query parameter
        if ($request->has('module_context')) {
            $context = $request->get('module_context');
            $validContexts = [
                'all',
                'inventory',
                'pos',
                'sales',
                'purchases',
                'accounting',
                'warehouse',
                'manufacturing',
                'hrm',
                'rental',
                'fixed_assets',
                'banking',
                'projects',
                'documents',
                'helpdesk',
            ];

            if (in_array($context, $validContexts, true)) {
                session(['module_context' => $context]);
            }
        }

        return $next($request);
    }
}
