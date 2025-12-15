<?php

declare(strict_types=1);

namespace App\Services;

class ModuleContextService
{
    /**
     * Get the current module context.
     */
    public static function current(): string
    {
        return session('module_context', 'all');
    }

    /**
     * Set the module context.
     */
    public static function set(string $context): void
    {
        session(['module_context' => $context]);
    }

    /**
     * Check if a specific context is active.
     */
    public static function is(string $context): bool
    {
        return self::current() === $context;
    }

    /**
     * Check if "All Modules" context is active.
     */
    public static function isAll(): bool
    {
        return self::current() === 'all';
    }

    /**
     * Get available modules with their labels.
     */
    public static function getAvailableModules(): array
    {
        return [
            'all' => __('All Modules'),
            'inventory' => __('Inventory'),
            'pos' => __('POS'),
            'sales' => __('Sales'),
            'purchases' => __('Purchases'),
            'accounting' => __('Accounting'),
            'warehouse' => __('Warehouse'),
            'manufacturing' => __('Manufacturing'),
            'hrm' => __('Human Resources'),
            'rental' => __('Rental'),
            'fixed_assets' => __('Fixed Assets'),
            'banking' => __('Banking'),
            'projects' => __('Projects'),
            'documents' => __('Documents'),
            'helpdesk' => __('Helpdesk'),
        ];
    }

    /**
     * Get the label for the current context.
     */
    public static function currentLabel(): string
    {
        $context = self::current();
        $modules = self::getAvailableModules();

        return $modules[$context] ?? __('Unknown');
    }
}
