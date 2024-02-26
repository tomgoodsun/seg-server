# seg-server
Super Easy Game Server

---
English

## Specs

1. Issue game ID and API Key
    - API Key has expiration but able to ignore.
    - These mean access token and refresh token.
2. Issue user auth token and user ID
    - User auth token has expiration.
3. Can register nickname or automatically generate it.
4. Resister play result.
    - Play result includes user ID and score.
    - Score will be recorded on each API request and will be able to be added to summerized score on each API request.
5. User can delete its own information and register angain.
6. Score will be shown as ranking super simple and realtime sorted.
    - This will be show as web page.
    - Clicking each score shows the details, for example user ID, nickname, score, history and the latest access date.

## Database definitions

### `game`

|Name|Data Type|Options|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`api_token`|`VARCHAR`|NULLABLE|
|`expired_date`|`TIMESTAMP`|NULLABLE|
|`updated_date`|`TIMESTAMP`|ON UPDATE|
|`created_date`|`TIMESTAMP`||

### `user`

|Name|Data Type|Options|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`access_token`|`VARCHAR`|NULLABLE|
|`refresh_token`|`VARCHAR`|NULLABLE|
|`expired_date`|`TIMESTAMP`|NULLABLE|
|`nickname`|`VARCHAR`|NULLABLE|
|`deleted_flag`|`TINYINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

### `user_game`

|Name|Data Type|NULLABLE|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`user_id`|`BIGINT`||
|`game_id`|`BIGINT`||
|`deleted_flag`|`TINYINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

### `score`

|Name|Data Type|NULLABLE|
|:---|:---|:---|
|`user_game_id`|`BIGINT`||
|`score`|`BIGINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

### `score_history`

|Name|Data Type|NULLABLE|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`user_game_id`|`BIGINT`||
|`score`|`BIGINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

## Endpoints

### API

|API|Explanation|
|:---|:---|
|`GET /api/auth`|Returns authorized or not.|
|`POST /api/user`|Register user information. API token required.|
|`PUT /api/user`|Update user information. API token required.|
|`POST /api/result`|Register score. API token required.|

### WEB

|API|Explanation|
|:---|:---|
|`/admin/game`|List games.|
|`/admin/game/{game_id}`|Register and update game and generate tokens. When `{game_id}` is undefined, newly registration mode.|
|`/admin/user`|Lists users.|
|`/admin/user/{user_id}`|Shows user details. `{user_id}` is required.|
|`/page`|Portal page for end users.|
|`/page/{game_id}`|Game detail page.|
|`/page/{game_id}/ranking`|Ranking for game.|
|`/page/{user_id}`|User page.|
|`/page/{user_id}/history/{game_id}`|User game page.|


---
日本語

## 仕様

1. ゲームIDとAPIキーの発行。
    - APIキーは有効期限を持っているが、これを無視する子もできる。
    - これらはアクセストークン、リフレッシュトークンのことである。
2. ユーザー認証トークンとユーザーIDを発行する。
    - ユーザー認証トークンは有効期限を持っている。
3. ニックネームが登録可能。また自動生成も可能。
4. プレイ結果を登録可能。
    - プレイ結果にはユーザーIDとスコアが含まれる。
    - スコアは各APIリクエストで記録され、合計スコアに加算することもできる。
5. ユーザーはその情報を削除することができ、再登録することができる。
6. スコアは超シンプルなランキングとして表示され、リアルタイムにソートされる。
    - ウェブページとして表示される。
    - 各スコアをクリックすると詳細を表示する。ユーザーID、ニックネーム、スコア、履歴、最終アクセス日。

## Database definitions

### `game`

|Name|Data Type|Options|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`api_token`|`VARCHAR`|NULLABLE|
|`expired_date`|`TIMESTAMP`|NULLABLE|
|`updated_date`|`TIMESTAMP`|ON UPDATE|
|`created_date`|`TIMESTAMP`||

### `user`

|Name|Data Type|Options|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`access_token`|`VARCHAR`|NULLABLE|
|`refresh_token`|`VARCHAR`|NULLABLE|
|`expired_date`|`TIMESTAMP`|NULLABLE|
|`nickname`|`VARCHAR`|NULLABLE|
|`deleted_flag`|`TINYINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

### `user_game`

|Name|Data Type|NULLABLE|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`user_id`|`BIGINT`||
|`game_id`|`BIGINT`||
|`deleted_flag`|`TINYINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

### `score`

|Name|Data Type|NULLABLE|
|:---|:---|:---|
|`user_game_id`|`BIGINT`||
|`score`|`BIGINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

### `score_history`

|Name|Data Type|NULLABLE|
|:---|:---|:---|
|`id`|`BIGINT`|AUTO INCREMENT|
|`user_game_id`|`BIGINT`||
|`score`|`BIGINT`||
|`updated_date`|`TIMESTAMP`||
|`created_date`|`TIMESTAMP`||

## Endpoints

### API

|API|Explanation|
|:---|:---|
|`GET /api/auth`|認証されたかどうか。|
|`POST /api/user`|ユーザー情報の登録。APIトークンは必須。|
|`PUT /api/user`|ユーザー情報の更新。APIトークンは必須。|
|`POST /api/result`|スコア登録。APIトークンは必須。|

### WEB

|API|Explanation|
|:---|:---|
|`/admin/game`|【管理】ゲーム一覧。|
|`/admin/game/{game_id}`|【管理】ゲームを登録、更新し、トークンを生成する。`{game_id}`が未定義の場合、新規登録モード。|
|`/admin/user`|【管理】ユーザー一覧。|
|`/admin/user/{user_id?}`|【管理】詳細を表示する。`{user_id}`は必須。|
|`/page`|エンドユーザー向けのポータルページ。|
|`/page/{game_id}`|ゲーム詳細ページ。|
|`/page/{game_id}/ranking`|ゲームのランキング。|
|`/page/{user_id}`|ユーザーページ。|
|`/page/{user_id}/history/{game_id}`|ユーザーゲームページ。|


