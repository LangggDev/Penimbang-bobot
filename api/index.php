<?php

// Ensure storage directories exist in /tmp (Vercel's only writable directory)
$tmpStorage = '/tmp/storage';
if (!is_dir($tmpStorage)) {
    mkdir($tmpStorage, 0777, true);
    mkdir($tmpStorage . '/framework/views', 0777, true);
    mkdir($tmpStorage . '/framework/sessions', 0777, true);
    mkdir($tmpStorage . '/framework/cache', 0777, true);
    mkdir($tmpStorage . '/logs', 0777, true);
}

// Create empty SQLite database if not exists in /tmp
$tmpDb = '/tmp/database.sqlite';
if (!file_exists($tmpDb)) {
    touch($tmpDb);
}

// Override storage path in Laravel
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// Tell Laravel to use /tmp/storage as the main storage path
$_ENV['APP_STORAGE'] = $tmpStorage;

// Auto run migration & seeding on Vercel if needed
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    try {
        if (!file_exists('/tmp/migrated.lock')) {
            touch('/tmp/migrated.lock');
            require __DIR__ . '/../vendor/autoload.php';
            $app = require __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        }
    } catch (\Throwable $e) {
        // Continue application execution
    }
}

// Forward Vercel requests to Laravel index.php
require __DIR__ . '/../public/index.php';
