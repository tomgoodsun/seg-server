<?php
namespace App\Http;

use App\Database\PdoSql;

abstract class AbstractApiController extends AbstractController
{
    protected function before(DefaultRequest $request, DefaultResponse $response): DefaultResponse
    {
        // TODO: Define 401 Exception class

        // Check authorization bearer token
        $bearerToken = $request->getHeader('Authorization');
        if (empty($bearerToken)) {
            throw new \Exception('Bearer token is missing', 401);
        }
        preg_match('/Bearer\s+(.*)/', $bearerToken[0], $matches);
        $request->bearerToken = $matches[1] ?? '';

        $sql = 'SELECT * FROM game WHERE api_token = :token';
        $rows = PdoSql::getInstance()->query($sql, [':token' => $request->bearerToken]);

        if (empty($rows)) {
            throw new \Exception('Invalid API token', 401);
        }

        return parent::before($request, $response);
    }

    protected function after(DefaultRequest $request, DefaultResponse $response): DefaultResponse
    {
        $json = json_decode($response->getContent(), true);
        $json['serverDate'] = (new \DateTime('now'))->format('Y-m-d H:i:s');
        return $response->withJson($json);
    }
}
