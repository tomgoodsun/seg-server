<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/error_handler.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/routes.php';

use Symfony\Component\Dotenv\Dotenv;

// Resolve initial paths
$_SERVER['APP_SRC_DIR'] = realpath(__DIR__ . '/../../src');
$_SERVER['APP_APP_DIR'] = realpath(__DIR__ . '/../app');
$_SERVER['APP_STORAGE_DIR'] = realpath(__DIR__ . '/../storage');
$_SERVER['APP_TEMPLATE_DIR'] = realpath(__DIR__ . '/../storage/templates');
$_SERVER['APP_TEMPLATE_CACHE_DIR'] = realpath(__DIR__ . '/../storage/template_cache');

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
