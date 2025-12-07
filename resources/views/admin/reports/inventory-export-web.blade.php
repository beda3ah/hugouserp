@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-6 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-xl font-semibold">
                {{ $title ?? 'Inventory Report' }}
            </h1>

            <form method="GET" class="flex items-center gap-2 text-xs">
                @foreach (request()->except('page', 'format') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <span class="text-gray-500 mr-1">{{ __('Format') }}:</span>
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="submit" name="format" value="web"
                            class="px-3 py-1 border border-gray-300 rounded-l-md {{ (request('format') === 'web' || ! request('format')) ? 'bg-gray-100' : 'bg-white' }}">
                        {{ __('Web') }}
                    </button>
                    <button type="submit" name="format" value="excel"
                            class="px-3 py-1 border-t border-b border-gray-300 bg-white">
                        {{ __('Excel') }}
                    </button>
                    <button type="submit" name="format" value="pdf"
                            class="px-3 py-1 border border-gray-300 rounded-r-md bg-white">
                        {{ __('PDF') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach ($columns as $key => $label)
                            <th class="px-4 py-2 text-left font-medium text-gray-700 uppercase tracking-wider">
                                {{ $label }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($rows as $row)
                        <tr>
                            @foreach ($columns as $key => $label)
                                <td class="px-4 py-2 whitespace-nowrap text-gray-800">
                                    {{ $row[$key] ?? '' }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="px-4 py-4 text-center text-gray-500">
                                {{ __('No data found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
