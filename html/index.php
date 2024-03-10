<?php
require_once __DIR__ . '/../src/includes/bootstrap.php';

use App\Core\App;

$app = new App($_ENV);
$app->run();

//phpinfo();
