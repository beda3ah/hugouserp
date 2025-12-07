<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ContractDueSoon::class => [
            \App\Listeners\SendDueReminder::class,
        ],
        \App\Events\ContractOverdue::class => [
            \App\Listeners\ApplyLateFee::class,
            \App\Listeners\WriteAuditTrail::class,
        ],
        \App\Events\PurchaseReceived::class => [
            \App\Listeners\UpdateStockOnPurchase::class,
        ],
        \App\Events\SaleCompleted::class => [
            \App\Listeners\UpdateStockOnSale::class,
        ],
        \App\Events\StockBelowThreshold::class => [
            \App\Listeners\ProposePurchaseOrder::class,
        ],
        \App\Events\UserDisabled::class => [
            \App\Listeners\InvalidateUserSessions::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\LogSuccessfulLogin::class,
        ],
        \Illuminate\Auth\Events\Failed::class => [
            \App\Listeners\LogFailedLogin::class,
        ],
        \Illuminate\Auth\Events\Logout::class => [
            \App\Listeners\LogSuccessfulLogout::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return true;
    }

    protected function discoverEventsWithin(): array
    {
        return [
            app_path('Listeners'),
            app_path('Domain'),
        ];
    }

    public function boot(): void
    {
        //
    }
}
