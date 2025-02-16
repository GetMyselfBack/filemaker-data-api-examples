<?php

require_once(__DIR__ . '/php/bs-frame-class.php');
require_once(__DIR__ . '/php/bs-builder-class.php');
require_once(__DIR__ . '/php/php-functions.php');
require_once(__DIR__ . '/php/filemaker-data-api-class.php');

@$page_identity    = $_POST['page-identity'];
$page_identity     = $page_identity === null ? 1 : $page_identity;

require_once(__DIR__ . '/php/preferences.php');

// FileMaker Server 情報
$host       = '';
$version    = 'vLatest';
// FileMaker ソリューション情報
$database   = '';
$username   = '';
$password   = '';
// リクエスト情報
$layout     = 'standings';
$request_body_json_file = __DIR__ . '/json/standings-al-east-2024.json';
$request_body           = json_get_element_raw($request_body_json_file);
$bearer_session_token   = null;

$fmda   = new FileMakerDataApi($host, $version);

// ログイン
$response = $fmda->login($database, $username, $password);

// ログイン成功？
if ($fmda->getMessagesCode($response) === '0') {
    // ログイン成功なら、Bearer Session Token を取得する
    $bearer_session_token   = $fmda->getBearerSessionToken($response);
} else {
    // ログイン失敗の処理を書く
    echo "FileMaker Data API へのログインに失敗しました。\n";
}

// （何らかの処理があり、リンクでページ遷移した想定）


// セッションの検証
$response   = $fmda->validateSession($bearer_session_token);
if ($fmda->getMessagesCode($response) === '0') {
    // セッション有効なら、行いたいリクエスト実行

    // レコードの検索
    $response   = $fmda->findRecords($database, $layout, $bearer_session_token, $request_body);

    // 結果セット処理
    $result = $fmda->json2array($response);
    
    if ($fmda->getMessagesCode($response) === '0') {
        $data_info  = $result['response']['dataInfo'];
        $team_data  = $result['response']['data'];

        echo "ソリューション: {$data_info['database']}\n";
        echo "レイアウト: {$data_info['layout']}\n";
        echo "テーブル: {$data_info['table']}\n\n";
        foreach ($team_data as $key => $value) {
            echo $value['fieldData']['team'],PHP_EOL;
            echo "\tレコード ID: {$value['recordId']}\n";
            echo "\t修正 ID: {$value['modId']}\n\n";
        }
        echo "総レコード数: {$result['response']['dataInfo']['totalRecordCount']}\n";
        echo "現在レコード数: {$result['response']['dataInfo']['foundCount']}\n";
        echo "取得レコード数: {$result['response']['dataInfo']['returnedCount']}\n";
    }
} else {
    // セッション無効なら、必要な Web データを保持して、ユーザに再ログインを促す
    echo "再ログインしてください。\n";
}

// （何らかの処理）

// ログアウト
$response = $fmda->logOut($database, $bearer_session_token);
