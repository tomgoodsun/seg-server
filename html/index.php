dev01
<?php
require_once __DIR__ . '/../src/vendor/autoload.php';

use App\Kernel\App;

$app = new App([
    'key' => 'value'
]);
$app->run();

//phpinfo();
