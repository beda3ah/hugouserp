<?php

declare(strict_types=1);

/**
 * Media Library Configuration
 * 
 * Centralized configuration for allowed file types, MIME types,
 * and other media-related settings.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Allowed Image Extensions
    |--------------------------------------------------------------------------
    */
    'image_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ico'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Document Extensions
    |--------------------------------------------------------------------------
    */
    'document_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'txt'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Image MIME Types
    |--------------------------------------------------------------------------
    */
    'image_mimes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/x-icon',
        'image/vnd.microsoft.icon',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Document MIME Types
    |--------------------------------------------------------------------------
    */
    'document_mimes' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/csv',
        'text/plain',
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum Upload Size (in KB)
    |--------------------------------------------------------------------------
    */
    'max_upload_size' => 10240, // 10MB

    /*
    |--------------------------------------------------------------------------
    | Default Storage Disk
    |--------------------------------------------------------------------------
    */
    'default_disk' => env('MEDIA_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Settings
    |--------------------------------------------------------------------------
    */
    'thumbnail' => [
        'width' => 300,
        'height' => 300,
        'quality' => 85,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Optimization Settings
    |--------------------------------------------------------------------------
    */
    'optimization' => [
        'enabled' => true,
        'quality' => 85,
        'max_width' => 2000,
        'max_height' => 2000,
    ],
];
