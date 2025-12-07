<div class="space-y-6" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-lg md:text-xl font-semibold text-slate-800">
                {{ __('Inventory charts dashboard') }}
            </h1>
            <p class="text-sm text-slate-500">
                {{ __('Visualize inventory levels and low stock items.') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] text-slate-500 mb-1">{{ __('Total products') }}</p>
            <p class="text-xl font-semibold text-slate-800">{{ number_format($totalProducts) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] text-slate-500 mb-1">{{ __('Total stock') }}</p>
            <p class="text-xl font-semibold text-emerald-600">{{ number_format($totalStock, 2) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] text-slate-500 mb-1">{{ __('Branch filter') }}</p>
            <input type="number" wire:model="branchId"
                   placeholder="{{ __('Branch ID (optional)') }}"
                   class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs">
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-[11px] font-medium text-slate-600 mb-2">
            {{ __('Top low stock products') }}
        </p>
        <canvas id="inventoryLowStockChart" class="w-full h-48"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            let lowStockChart = null;

            const ctx = document.getElementById('inventoryLowStockChart')?.getContext('2d');

            if (! ctx) {
                return;
            }

            Livewire.on('inventory-charts-update', (payload) => {
                const data = payload.chartData || payload || {};
                const low = data.lowStock || {labels: [], values: []};

                if (lowStockChart) lowStockChart.destroy();

                lowStockChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: low.labels,
                        datasets: [{
                            label: '{{ __('Stock') }}',
                            data: low.values,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { font: { size: 10 } } },
                            y: { ticks: { font: { size: 10 } } },
                        },
                    },
                });
            });
        });
    </script>
</div>
