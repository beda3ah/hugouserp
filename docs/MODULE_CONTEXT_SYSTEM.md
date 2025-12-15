# Module Context System

## Overview
The Module Context System allows users to filter their workspace by specific business modules (Inventory, POS, Sales, etc.) or view all modules at once.

## Components

### 1. ModuleContext Middleware
**Location:** `app/Http/Middleware/ModuleContext.php`

- Ensures `module_context` session variable exists (defaults to 'all')
- Allows context switching via `?module_context=<module>` query parameter
- Valid contexts: all, inventory, pos, sales, purchases, accounting, warehouse, manufacturing, hrm, rental, fixed_assets, banking, projects, documents, helpdesk

**Usage:**
```php
// In routes/web.php or bootstrap/app.php
Route::middleware(['auth', ModuleContext::class])->group(function () {
    // Your routes
});
```

### 2. ModuleContextService
**Location:** `app/Services/ModuleContextService.php`

Static service for accessing and managing module context.

**Methods:**
- `current()`: Get current context (string)
- `set(string $context)`: Set context
- `is(string $context)`: Check if specific context is active
- `isAll()`: Check if "All Modules" is active
- `getAvailableModules()`: Get all available modules with labels
- `currentLabel()`: Get label for current context

**Usage:**
```php
use App\Services\ModuleContextService;

// Get current context
$context = ModuleContextService::current();

// Check context
if (ModuleContextService::is('inventory')) {
    // Show inventory-specific content
}

// Get label
$label = ModuleContextService::currentLabel(); // e.g., "Inventory"
```

### 3. Module Context Selector Component
**Location:** `resources/views/components/module-context-selector.blade.php`

Blade component that displays a dropdown for selecting module context.

**Usage in Blade:**
```blade
<x-module-context-selector />
```

**Features:**
- Alpine.js dropdown with smooth transitions
- Displays current context with icon
- Shows checkmark next to active context
- Clicking a module switches context via URL parameter

### 4. Integration in Layouts

Add the selector to your layout header:

```blade
{{-- In layouts/app.blade.php or navigation component --}}
<div class="flex items-center gap-4">
    <x-module-context-selector />
    
    {{-- Other header items --}}
</div>
```

## Filtering Content by Context

### In Livewire Components
```php
use App\Services\ModuleContextService;

public function render()
{
    $context = ModuleContextService::current();
    
    $query = Report::query();
    
    if (!ModuleContextService::isAll()) {
        $query->where('module', $context);
    }
    
    return view('livewire.reports.index', [
        'reports' => $query->paginate(20),
    ]);
}
```

### In Blade Views
```blade
@php
    $context = \App\Services\ModuleContextService::current();
@endphp

@if($context === 'inventory' || $context === 'all')
    {{-- Show inventory-related content --}}
@endif
```

### In Sidebar Navigation
```blade
@if(\App\Services\ModuleContextService::isAll() || \App\Services\ModuleContextService::is('inventory'))
    <x-sidebar.link route="app.inventory.products.index" label="Products" />
@endif
```

## Context-Aware Reports

Reports should filter based on module context:

```php
// In ReportsController or Livewire component
public function getReports()
{
    $context = ModuleContextService::current();
    
    $reports = collect([
        ['name' => 'Sales Report', 'module' => 'sales'],
        ['name' => 'Inventory Report', 'module' => 'inventory'],
        ['name' => 'POS Report', 'module' => 'pos'],
    ]);
    
    if (!ModuleContextService::isAll()) {
        $reports = $reports->filter(fn($r) => $r['module'] === $context);
    }
    
    return $reports;
}
```

## Persisting Context

The context is stored in the session and persists across requests. To switch context programmatically:

```php
ModuleContextService::set('inventory');
```

Or via URL:
```
/dashboard?module_context=inventory
```

## Best Practices

1. **Always provide "All Modules" option**: Users should be able to view all content
2. **Filter intelligently**: Only filter when context is not "all"
3. **Show context in page titles**: Help users understand their current view
4. **Preserve context on navigation**: Links should maintain the current context
5. **Clear visual indication**: Use the selector component prominently

## Example: Context-Aware Dashboard

```php
// app/Livewire/Dashboard/Index.php
use App\Services\ModuleContextService;

class Index extends Component
{
    public function render()
    {
        $context = ModuleContextService::current();
        $widgets = $this->getWidgetsForContext($context);
        
        return view('livewire.dashboard.index', [
            'widgets' => $widgets,
            'contextLabel' => ModuleContextService::currentLabel(),
        ]);
    }
    
    private function getWidgetsForContext(string $context): array
    {
        $allWidgets = [
            'sales' => ['total_sales', 'pending_orders'],
            'inventory' => ['stock_alerts', 'low_stock_items'],
            'hrm' => ['attendance_today', 'pending_leaves'],
        ];
        
        if ($context === 'all') {
            return collect($allWidgets)->flatten(1)->toArray();
        }
        
        return $allWidgets[$context] ?? [];
    }
}
```

## Migration Guide

If you have existing code that assumes full module access:

1. Add context checks: `if (ModuleContextService::isAll() || ModuleContextService::is('your_module'))`
2. Filter queries based on context
3. Update navigation to show/hide based on context
4. Test with different contexts to ensure proper filtering

## Future Enhancements

Potential improvements:
- User preferences for default context
- Context-based permissions
- Module-specific themes/branding
- Context history/breadcrumbs
- Quick context switcher keyboard shortcuts
