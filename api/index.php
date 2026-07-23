<?php

use Illuminate\Http\Request;

// Force HTTPS for Vercel Reverse Proxy
$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
$_SERVER['HTTP_X_FORWARDED_PORT'] = '443';

// Ensure storage directories exist in /tmp (Vercel's only writable directory)
$tmpStorage = '/tmp/storage';
if (!is_dir($tmpStorage)) {
    mkdir($tmpStorage, 0777, true);
    mkdir($tmpStorage . '/framework/views', 0777, true);
    mkdir($tmpStorage . '/framework/sessions', 0777, true);
    mkdir($tmpStorage . '/framework/cache', 0777, true);
    mkdir($tmpStorage . '/logs', 0777, true);
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

// Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel Application
$app = require __DIR__ . '/../bootstrap/app.php';

// Auto run migration & seeding on Vercel if needed
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        (new \Database\Seeders\DatabaseSeeder())->run();
    } catch (\Throwable $e) {
        error_log('Vercel Migration Error: ' . $e->getMessage());
    }
}

// Handle HTTP Request
$app->handleRequest(Request::capture());
