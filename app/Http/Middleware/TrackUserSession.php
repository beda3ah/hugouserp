<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\SessionManagementService;
use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserSession
{
    public function __construct(
        protected SessionManagementService $sessionService,
        protected SettingsService $settingsService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $sessionId = $request->session()->getId();

            $this->sessionService->trackSession(
                $user,
                $sessionId,
                $request->ip(),
                $request->userAgent()
            );

            $maxSessions = $user->max_sessions ?? $this->settingsService->get('security.max_sessions', 3);
            $this->sessionService->enforceSessionLimit($user, (int) $maxSessions);
        }

        return $next($request);
    }
}
