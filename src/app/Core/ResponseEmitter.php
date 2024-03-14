<?php
/**
 * @see https://github.com/slimphp/Slim/blob/4.x/Slim/ResponseEmitter.php
 */
namespace App\Core;

use App\Http\DefaultResponse;

class ResponseEmitter
{
    public function emit(DefaultResponse $response)
    {
        $this->emitHeader($response);
        $this->emitStatusLine($response);
        $this->emitBody($response);
    }

    /**
     * Emit headers
     *
     * @param DefaultResponse $response
     * @return void
     */
    private function emitHeader(DefaultResponse $response): void
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
     * @param DefaultResponse $response
     * @return void
     */
    private function emitStatusLine(DefaultResponse $response): void
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
     * @param DefaultResponse $response
     * @return void
     */
    private function emitBody(DefaultResponse $response): void
    {
        $content = $response->getContent();
        if (empty($content)) {
            echo $response;
            return;
        }
        echo $content;
    }
}
