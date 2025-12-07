<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Store orders export') }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 12px; color: #111827; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #e5e7eb; padding: 4px 6px; text-align: left; font-size: 11px; }
        th { background-color: #f3f4f6; font-weight: 600; }
        h1 { font-size: 16px; margin-bottom: 10px; }
    </style>
</head>
<body>
<h1>{{ __('Store orders export') }}</h1>

<table>
    <thead>
    <tr>
        @foreach($columns as $col)
            <th>{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @forelse($rows as $row)
        <tr>
            @foreach($columns as $col)
                <td>{{ $row[$col] ?? '' }}</td>
            @endforeach
        </tr>
    @empty
        <tr>
            <td colspan="{{ count($columns) }}" style="text-align:center; color:#6b7280;">
                {{ __('No data.') }}
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
