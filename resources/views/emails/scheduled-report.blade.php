@php
    $locale = app()->getLocale();
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Scheduled report') }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background-color: #f3f4f6; padding: 16px;">
<div style="max-width: 640px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 16px 20px;">
    <h1 style="font-size: 18px; margin-bottom: 12px; color: #111827;">
        {{ __('Your scheduled report is ready') }}
    </h1>

    <p style="font-size: 13px; color: #4b5563;">
        {{ __('You can open or export the report using the link below:') }}
    </p>

    <p style="margin: 16px 0;">
        <a href="{{ $url }}"
           style="display: inline-block; padding: 8px 14px; border-radius: 9999px; background-color: #4f46e5; color: #ffffff; text-decoration: none; font-size: 13px;">
            {{ __('Open report') }}
        </a>
    </p>

    @if($outputType !== 'web')
        <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">
            {{ __('Preferred output type: :type', ['type' => strtoupper($outputType)]) }}
        </p>
    @endif

    <p style="font-size: 12px; color: #9ca3af; margin-top: 24px;">
        {{ __('This email was generated automatically by the reports scheduler.') }}
    </p>
</div>
</body>
</html>
