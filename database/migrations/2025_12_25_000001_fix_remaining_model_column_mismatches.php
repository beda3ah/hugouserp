<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix remaining column mismatches between models and database schema.
 *
 * This migration addresses:
 * - leave_requests: Add extra_attributes column (model expects it but was missing)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Fix leave_requests - add extra_attributes column
        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table): void {
                if (!Schema::hasColumn('leave_requests', 'extra_attributes')) {
                    // Don't specify position to avoid dependency on other columns existing
                    $table->json('extra_attributes')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table): void {
                if (Schema::hasColumn('leave_requests', 'extra_attributes')) {
                    $table->dropColumn('extra_attributes');
                }
            });
        }
    }
};
