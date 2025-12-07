<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Store orders export') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111827; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d1d5db; padding: 3px 4px; text-align: left; font-size: 9px; }
        th { background-color: #e5e7eb; font-weight: 600; }
        h1 { font-size: 14px; margin-bottom: 6px; }
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
