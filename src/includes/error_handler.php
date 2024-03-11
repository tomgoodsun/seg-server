<?php

// @see https://www.php.net/manual/en/class.errorexception.php
function exception_error_handler(int $errno, string $errstr, string $errfile = null, int $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler('exception_error_handler');

function renderError(\Throwable $e): string
{
    return sprintf(
        '<h1>%s</h1><p>%s</p><pre>%s</pre>',
        'Error',
        $e->getMessage(),
        $e->getTraceAsString()
    );
}
