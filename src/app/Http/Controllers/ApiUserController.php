<?php

namespace App\Http\Controllers;

use App\Database\PdoSql;
use App\Http\AbstractApiController;
use App\Http\DefaultRequest;
use App\Http\DefaultResponse;
use App\Util\Str;
use App\Util\Sysdate;
use App\Util\UserUtil;
use Psr\Http\Message\ResponseInterface;

class ApiUserController extends AbstractApiController
{
    public function post(DefaultRequest $request, DefaultResponse $response, array $params = []): ResponseInterface
    {
        $userId = $request->getParam('userId');
        $nickname = $request->getParam('nickname');

        $accessToken = Str::random(32);

        $canInsert = true;
        $sql = 'SELECT * FROM user WHERE id = :user_id';
        $users = PdoSql::getInstance()->query($sql, [':user_id' => $userId]);
        if (count($users) > 0) {
            $canInsert = false;
        }

        if ($canInsert) {
            $nickname = UserUtil::generateNicknema();
            $sql = 'INSERT INTO user (nickname, access_token, deleted_flag, created_date, updated_date)';
            $sql .= ' VALUES (:nickname, :access_token, :deleted_flag, :created_date, :updated_date)';
            PdoSql::getInstance()->execute($sql, [
                ':nickname' => $nickname,
                ':access_token' => $accessToken,
                ':deleted_flag' => 0,
                ':created_date' => Sysdate::now()->format('Y-m-d H:i:s'),
                ':updated_date' => Sysdate::now()->format('Y-m-d H:i:s'),
            ]);
            $userId = PdoSql::getInstance()->lastInsertId();
        }

        return $response->withJson([
            'status' => 'ok',
            'userId' => $userId,
            'nickname' => $nickname,
            'accessToken' => $accessToken,
        ]);
    }

    public function put(DefaultRequest $request, DefaultResponse $response, array $params = []): ResponseInterface
    {
        return $response->withJson([
            'status' => 'ok',
            'message' => 'Welcome to the API'
        ]);
    }
}
