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

// Override storage path in Laravel
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// Tell Laravel to use /tmp/storage as the main storage path
$_ENV['APP_STORAGE'] = $tmpStorage;

// Forward Vercel requests to Laravel index.php
require __DIR__ . '/../public/index.php';
