<?php

// @see https://www.php.net/manual/en/class.errorexception.php

use App\Core\ResponseEmitter;
use App\Http\ErrorHandler;
use App\Http\Factory;

function exception_error_handler(int $errno, string $errstr, string $errfile = null, int $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler('exception_error_handler');

function renderError(\Throwable $e): void
{
    $code = $e->getCode();
    if ($code < 100 || $code > 599) {
        $code = 500;
    }
    header("HTTP/1.1 $code Service Unavailable");
    $html = '';
    $html .= '<html>';
    $html .= '<head>';
    $html .= '<title>Error: ' . $e->getMessage() . '</title>';
    $html .= '</head>';
    $html .= '<body>';
    $html .= '<h1>Error</h1>';

    $html .= '<dl>';
    $html .= '<dt><strong>Message</strong></dt>';
    $html .= '<dd>' . $e->getMessage() . '</dd>';
    $html .= '</dl>';

    $html .= '<dl>';
    $html .= '<dt><strong>Stacktrace</strong></dt>';
    $html .= '<dd><pre>' . $e->getTraceAsString() . '</pre></dd>';
    $html .= '</dl>';
    $html .= '</body>';
    $html .= '</html>';

    echo $html;
}

// Register final error handler
ErrorHandler::getInstance()
    ->setHandlerByPathPattern('/api/', function (\Throwable $e) {
        $code = $e->getCode();
        if ($code < 100 || $code > 599) {
            $code = 500;
        }
        $body = [
            'errorCode' => $code,
            'errorMessage' => $e->getMessage(),
        ];
        /** @var DefaultResponse $response */
        $response = Factory::createDefaultResponse();
        $response = $response->withJson($body, $code);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    })
    ->setHandlerByPathPattern('/admin/', 'renderError')
    ->setHandlerByPathPattern('/page/', 'renderError');
