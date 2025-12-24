<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * @deprecated This trait is deprecated. Use page-based forms instead of modals.
 * For backward compatibility, the trait remains but is not recommended for new development.
 */
trait HasDualModeCrud
{
    /**
     * @deprecated Use page-based navigation instead
     */
    public function goToCreatePage()
    {
        $currentRoute = request()->route()->getName();
        $createRoute = str_replace('.index', '.create', $currentRoute);

        if (\Route::has($createRoute)) {
            return redirect()->route($createRoute);
        }

        return redirect()->back()->with('error', __('Create page not available'));
    }

    /**
     * @deprecated Use page-based navigation instead
     */
    public function goToEditPage(int $id)
    {
        $currentRoute = request()->route()->getName();
        $editRoute = str_replace('.index', '.edit', $currentRoute);

        if (\Route::has($editRoute)) {
            return redirect()->route($editRoute, ['id' => $id]);
        }

        return redirect()->back()->with('error', __('Edit page not available'));
    }

    /**
     * @deprecated Use page-based navigation instead
     */
    public function goToIndex()
    {
        $currentRoute = request()->route()->getName();

        $patterns = ['.create', '.edit', '.form'];
        foreach ($patterns as $pattern) {
            if (str_contains($currentRoute, $pattern)) {
                $indexRoute = str_replace($pattern, '.index', $currentRoute);
                if (\Route::has($indexRoute)) {
                    return redirect()->route($indexRoute);
                }
            }
        }

        return redirect()->back();
    }

    /**
     * Check if we're in create mode
     */
    public function isCreating(): bool
    {
        return empty($this->editingId ?? null);
    }

    /**
     * Check if we're in edit mode
     */
    public function isEditing(): bool
    {
        return ! empty($this->editingId ?? null);
    }

    /**
     * Get page title based on mode
     */
    public function getPageTitle(string $entityName): string
    {
        if ($this->isCreating()) {
            return __('Create :entity', ['entity' => $entityName]);
        }

        return __('Edit :entity', ['entity' => $entityName]);
    }

    /**
     * Get save button text based on mode
     */
    public function getSaveButtonText(): string
    {
        if ($this->isCreating()) {
            return __('Create');
        }

        return __('Update');
    }
}
