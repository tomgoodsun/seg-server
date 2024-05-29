<?php
require_once __DIR__ . '/../src/includes/bootstrap.php';

use App\Core\App;
use App\Http\ErrorHandler;

try {
    // $_ENV includes .env variables
    $app = new App($_ENV);
    $app->run();
} catch (\Throwable $e) {
    ErrorHandler::getInstance()
        ->handle($e, sgv()->server('REQUEST_URI'));
    //renderError($e);
}
