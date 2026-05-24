<?php

return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),
    'release' => env('APP_VERSION'),
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 1.0),
    'profiles_sample_rate' => (float) env('SENTRY_PROFILES_SAMPLE_RATE', 1.0),

    'breadcrumbs' => [
        'logs' => true,
        'sql_queries' => true,
        'cache' => true,
        'queue' => true,
        'redis' => true,
        'http_client_requests' => true,
        'view' => true,
    ],

    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', false),

    'integrations' => [
        \Sentry\Laravel\Integration\ExceptionContextIntegration::class,
    ],
];
