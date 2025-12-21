<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix final column mismatches between models and database schema.
 * 
 * This migration addresses missing columns in:
 * - customers: city, country, company, external_id
 */
return new class extends Migration
{
    public function up(): void
    {
        // Fix customers table - add city, country, company, external_id columns
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table): void {
                // Add address-related columns if they don't exist
                if (!Schema::hasColumn('customers', 'city')) {
                    $table->string('city', 100)->nullable()->after('address')->comment('Customer city');
                }
                if (!Schema::hasColumn('customers', 'country')) {
                    $table->string('country', 100)->nullable()->after('city')->comment('Customer country');
                }
                if (!Schema::hasColumn('customers', 'company')) {
                    $table->string('company')->nullable()->after('country')->comment('Company name');
                }
                if (!Schema::hasColumn('customers', 'external_id')) {
                    $table->string('external_id')->nullable()->after('company')->comment('External system ID');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table): void {
                $columnsToDrop = ['city', 'country', 'company', 'external_id'];
                foreach ($columnsToDrop as $column) {
                    if (Schema::hasColumn('customers', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
