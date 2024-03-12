<?php
namespace App\Http;

use App\Database\PdoSql;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApiController extends AbstractController
{
    protected function before(RequestInterface $request, ResponseInterface $response): void
    {
        //dump($request->getHeaders());

        $apiToken = $request->getHeader('API_TOKEN');
        if (empty($apiToken)) {
            throw new \Exception('API token is missing', 401);
        }

        $sql = 'SELECT * FROM game WHERE api_token = :token';
        $rows = PdoSql::getInstance()->query($sql, ['token' => $apiToken]);
        //dump($rows);

        parent::before($request, $response);
    }

}
