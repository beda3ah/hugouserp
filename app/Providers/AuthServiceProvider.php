<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Models
use App\Models\{Branch, Product, Purchase, Sale, Vehicle, Notification,
    RentalContract, RentalInvoice, Property, RentalUnit, Tenant};

// Policies
use App\Policies\{BranchPolicy, ProductPolicy, PurchasePolicy, SalePolicy, VehiclePolicy, NotificationPolicy, RentalPolicy};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Branch::class        => BranchPolicy::class,
        Product::class       => ProductPolicy::class,
        Purchase::class      => PurchasePolicy::class,
        Sale::class          => SalePolicy::class,
        Vehicle::class       => VehiclePolicy::class,
        Notification::class  => NotificationPolicy::class,

        // Rental domain mapped to a generic policy handling multiple models
        RentalContract::class => RentalPolicy::class,
        RentalInvoice::class  => RentalPolicy::class,
        Property::class       => RentalPolicy::class,
        RentalUnit::class     => RentalPolicy::class,
        Tenant::class         => RentalPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Super Admin shortcut (works with spatie/permission)
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
                return true;
            }
            return null;
        });

        // Ability for impersonation if you use it
        Gate::define('impersonate', function ($user) {
            return (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('impersonate.users'))
                || (method_exists($user, 'hasRole') && $user->hasRole('Super Admin'));
        });
    }
}
