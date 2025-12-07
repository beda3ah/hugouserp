<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if ($event->user) {
            LoginActivity::logLogout(
                $event->user,
                request()->ip() ?? 'unknown'
            );
        }
    }
}
