<?php
/**
 * @see https://github.com/slimphp/Slim/blob/4.x/Slim/ResponseEmitter.php
 */
namespace App\Core;

use Psr\Http\Message\ResponseInterface;

class ResponseEmitter
{
    public function emit(ResponseInterface $response)
    {
        $this->emitHeader($response);
        $this->emitStatusLine($response);
        $this->emitBody($response);
    }

    /**
     * Emit headers
     *
     * @param ResponseInterface $response
     * @return void
     */
    private function emitHeader(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $values) {
            $first = strtolower($name) !== 'set-cookie';
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), $first);
                $first = false;
            }
        }
    }

    /**
     * Emit status line
     *
     * @param ResponseInterface $response
     * @return void
     */
    private function emitStatusLine(ResponseInterface $response): void
    {
        $statusLine = sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );
        header($statusLine, true, $response->getStatusCode());
    }

    /**
     * Emit body
     *
     * @param ResponseInterface $response
     * @return void
     */
    private function emitBody(ResponseInterface $response): void
    {
        $body = $response->getBody();
        $amountToRead = (int) $response->getHeaderLine('Content-Length');
        if ($amountToRead === 0) {
            $amountToRead = $body->getSize();
        }
        if ($amountToRead > 0) {
            echo $body->read($amountToRead);
            return;
        }
        echo $response;
    }
}
