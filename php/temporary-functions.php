<?php

function authorization_data( ): void
{
    global $host, $version;
    global $database, $username, $password;
    global $layout, $bearer_session_token;
    // FileMaker Server 情報
    $host       = 'remixworks.cloud';
    $version    = 'vLatest';
    // FileMaker ソリューション情報
    $database   = 'BaseballSavant';
    $username   = 'baseballsavant';
    $password   = 'baseballsavant';
    // リクエスト情報
    $layout     = 'standings';
    $bearer_session_token   = null;
}

function auth_login( ): void
{
    global $fmda, $response, $bearer_session_token, $database, $username, $password;

    // ログイン
    $response = $fmda->login($database, $username, $password);

    // ログイン成功？
    if ($fmda->getMessagesCode($response) === '0') {
        // ログイン成功なら、Bearer Session Token を取得する
        $bearer_session_token   = $fmda->getBearerSessionToken($response);
    } else {
        // ログイン失敗の処理を書く
        echo "<div class=\"alert alert-danger\" role=\"alert\">FileMaker Data API へのログインに失敗しました。</div>\n";
    }
}

function auth_validate_session( ): void
{
    global $fmda, $response, $bearer_session_token;

    // セッションの検証
    $response   = $fmda->validateSession($bearer_session_token);
}

function records_find_records( ): void
{

    global $fmda, $response, $bearer_session_token, $database, $layout, $request_body;
    global $result, $data_info, $team_data, $response_field_data;
    global $info_item_content;
    if ($fmda->getMessagesCode($response) === '0') {
        // セッション有効なら、行いたいリクエスト実行
    
        // レコードの検索
        $response   = $fmda->findRecords($database, $layout, $bearer_session_token, $request_body);
    
        // 結果セット処理
        $result = $fmda->json2array($response);
        
        if ($fmda->getMessagesCode($response) === '0') {
            $data_info  = $result['response']['dataInfo'];
            $team_data  = $result['response']['data'];

            // 追加部分
            array_push($info_item_content, $data_info['database']);
            array_push($info_item_content, $data_info['layout']);
            array_push($info_item_content, $data_info['table']);
            array_push($info_item_content, $team_data[0]['recordId']);
            array_push($info_item_content, $team_data[0]['modId']);
            array_push($info_item_content, $data_info['totalRecordCount']);
            array_push($info_item_content, $data_info['foundCount']);
            array_push($info_item_content, $data_info['returnedCount']);
            $response_field_data    = [
                $team_data[0]['fieldData']['mlb_site_id'],
                $team_data[0]['fieldData']['year'],
                $team_data[0]['fieldData']['league'],
                $team_data[0]['fieldData']['division'],
                $team_data[0]['fieldData']['team'],
                $team_data[0]['fieldData']['wins'],
                $team_data[0]['fieldData']['losses'],
                $team_data[0]['fieldData']['winning_percentage'],
                $team_data[0]['fieldData']['runs_scored'],
                $team_data[0]['fieldData']['runs_allowed'],
                $team_data[0]['fieldData']['pythagorean_expectation'],
                $team_data[0]['fieldData']['expected_win_loss'],
                $team_data[0]['fieldData']['winning_percentage_diﬀerence']
            ];
        }
    } else {
        // セッション無効なら、必要な Web データを保持して、ユーザに再ログインを促す
        echo "<div class=\"alert alert-warning\" role=\"alert\">セッションが切れました。再ログインしてください。</div>\n";
    }
}

function auth_log_out( ): void
{
    global $fmda, $response, $bearer_session_token, $database;
    // ログアウト
    $response = $fmda->logOut($database, $bearer_session_token);
}
