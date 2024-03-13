<?php
namespace App\Http;

use App\Database\PdoSql;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApiController extends AbstractController
{
    protected function before(RequestInterface $request, ResponseInterface $response): void
    {
        // TODO: Define 401 Exception class

        // Check authorization bearer token
        $bearerToken = $request->getHeader('Authorization');
        if (empty($bearerToken)) {
            throw new \Exception('Bearer token is missing', 401);
        }
        preg_match('/Bearer\s+(.*)/', $bearerToken[0], $matches);
        $request->bearerToken = $matches[1] ?? '';
        //$request->bearerToken = 'test';

        $apiToken = $request->getHeader('API_TOKEN');
        if (empty($apiToken)) {
            throw new \Exception('API token is missing', 401);
        }

        $sql = 'SELECT * FROM game WHERE api_token = :token';
        $rows = PdoSql::getInstance()->query($sql, [':token' => $request->bearerToken]);

        if (empty($rows)) {
            throw new \Exception('Invalid API token', 401);
        }

        parent::before($request, $response);
    }
}
