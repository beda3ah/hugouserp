<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Align ticket_categories with the model expectations
        if (Schema::hasTable('ticket_categories')) {
            Schema::table('ticket_categories', function (Blueprint $table): void {
                if (! Schema::hasColumn('ticket_categories', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                }

                if (! Schema::hasColumn('ticket_categories', 'color')) {
                    $table->string('color', 7)->default('#3B82F6')->after('description');
                }

                if (! Schema::hasColumn('ticket_categories', 'icon')) {
                    $table->string('icon')->nullable()->after('color');
                }

                if (! Schema::hasColumn('ticket_categories', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('icon');
                }

                if (! Schema::hasColumn('ticket_categories', 'created_by')) {
                    $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->nullOnDelete();
                }

                if (! Schema::hasColumn('ticket_categories', 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                }

                if (! Schema::hasColumn('ticket_categories', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            // Preserve legacy ordering values from display_order
            if (Schema::hasColumn('ticket_categories', 'display_order')) {
                DB::table('ticket_categories')->update([
                    'sort_order' => DB::raw('COALESCE(sort_order, display_order)'),
                ]);

                Schema::table('ticket_categories', function (Blueprint $table): void {
                    $table->dropColumn('display_order');
                });
            }
        }

        // Add missing localized name column to ticket_priorities
        if (Schema::hasTable('ticket_priorities')) {
            Schema::table('ticket_priorities', function (Blueprint $table): void {
                if (! Schema::hasColumn('ticket_priorities', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ticket_categories')) {
            Schema::table('ticket_categories', function (Blueprint $table): void {
                foreach (['name_ar', 'color', 'icon', 'sort_order', 'created_by', 'updated_by'] as $column) {
                    if (Schema::hasColumn('ticket_categories', $column)) {
                        if (in_array($column, ['created_by', 'updated_by'], true)) {
                            $table->dropForeign([$column]);
                        }
                        $table->dropColumn($column);
                    }
                }

                if (Schema::hasColumn('ticket_categories', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }

                if (! Schema::hasColumn('ticket_categories', 'display_order')) {
                    $table->integer('display_order')->default(0)->after('sla_policy_id');
                }
            });
        }

        if (Schema::hasTable('ticket_priorities')) {
            Schema::table('ticket_priorities', function (Blueprint $table): void {
                if (Schema::hasColumn('ticket_priorities', 'name_ar')) {
                    $table->dropColumn('name_ar');
                }
            });
        }
    }
};
