<?php
require_once __DIR__ . '/../src/includes/bootstrap.php';

use App\Core\App;

try {
    // $_ENV includes .env variables
    $app = new App($_ENV);
    $app->run();
} catch (\Throwable $e) {
    renderError($e);
}
