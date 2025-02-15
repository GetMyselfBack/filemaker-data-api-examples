<?php

class FileMakerDataApi
{
    public $host, $version, $database, $username, $password, $layout;
    public $bearer_session_token;

    function __construct (string $host, string $version)
    {
        $this->host     = $host;
        $this->version  = $version;
    }

    /**
     * リクエスト
     **/

    // ログイン
    public function login (string $database, string $username, string $password): string
    {
		$this->database	= $database;
		$this->username = $username;
		$this->password = $password;

		$auth_value         = $username . ':' . $password;
		$authorization      = 'Authorization: Basic ' . base64_encode($auth_value);
		$content_type       = 'Content-Type: application/json';
		$curlopt_httpheader = array($authorization, $content_type);

		$endpoint        	= "https://{$this->host}/fmi/data/{$this->version}/databases/{$database}/sessions";
		
		return self::executeCurl($endpoint, 'POST', $curlopt_httpheader);
	}

    // ログアウト
    public function logOut(string $database, string $bearer_session_token): string
    {
		$endpoint    = "https://{$this->host}/fmi/data/{$this->version}/databases/{$database}/sessions/{$bearer_session_token}";

		return self::executeCurl($endpoint, 'DELETE');
	}

    // データベースセッションの検証
	public function validateSession (string $session_token): string
    {
		$authorization      = 'Authorization: Bearer ' . $session_token;
		$curlopt_httpheader = array($authorization);
		$endpoint        	= "https://{$this->host}/fmi/data/{$this->version}/validateSession";

		return self::executeCurl($endpoint, 'GET', $curlopt_httpheader);
	}

	// レコード範囲の取得
	public function getRecords(
		string $database,
		string $layout,
		string $bearer_session_token,
		array &$script_name_array,
		array &$script_param_array,
		int $_offset = 1,
		int $_limit = 100,
		string $_sort = ''
		): string
    {
		$this->layout		= $layout;

		$authorization      = 'Authorization: Bearer ' . $bearer_session_token;
		$curlopt_httpheader = array($authorization);

		// ソートの設定
		$_sort_string		= !empty($_sort) ? '&_sort=' . urlencode($_sort) : '';

        $script_prerequest			= '';
    	$script_prerequest_param	= '';
    	$script_presort				= '';
    	$script_presort_param		= '';
    	$script						= '';
    	$script_param				= '';
		// リクエスト処理前のスクリプト
		if ($script_name_array[0] !== '') {
			$script_prerequest	= '&script.prerequest=' . $script_name_array[0];
			$script_prerequest_param = '&script.prerequest.param=' . urlencode(json_encode($script_param_array[0]));
		}
		
		// ソート前のスクリプト
		if ($script_name_array[1] !== '') {
			$script_presort	= '&script.presort=' . $script_name_array[1];
			$script_presort_param = '&script.presort.param=' . urlencode(json_encode($script_param_array[1]));
		}

		// リクエスト処理後のスクリプト
		if ($script_name_array[2] !== '') {
			$script	= '&script=' . $script_name_array[2];
			$script_param = '&script.param=' . urlencode(json_encode($script_param_array[2]));
		}
		
		$endpoint        	= "https://{$this->host}/fmi/data/{$this->version}/databases/{$database}/layouts/{$layout}/records?_offset={$_offset}&_limit={$_limit}{$_sort_string}{$script_prerequest}{$script_prerequest_param}{$script_presort}{$script_presort_param}{$script}{$script_param}";
		return self::executeCurl($endpoint, 'GET', $curlopt_httpheader);
	}

	// 検索の実行
	public function findRecords(
		string $database,
		string $layout,
		string $bearer_session_token,
		string $request_body
		): string
	{
		$authorization      = 'Authorization: Bearer ' . $bearer_session_token;
		$content_type       = 'Content-Type: application/json';
		$curlopt_httpheader = array($authorization, $content_type);
		$endpoint        	= "https://{$this->host}/fmi/data/{$this->version}/databases/{$database}/layouts/{$layout}/_find";

		return self::executeCurl($endpoint, 'POST', $curlopt_httpheader, $request_body);
	}

	// スクリプトの実行
	public function executeScript(
		string $database,
		string $layout,
		string $bearer_session_token,
		string $script_name,
		string $script_parameters = ''
		): string
	{
		$authorization      = 'Authorization: Bearer ' . $bearer_session_token;
		$curlopt_httpheader = array($authorization);
		$script_parameters	= !empty($script_parameters) ? '?script.param=' . json_encode(json_decode($script_parameters)) : '';
		$endpoint        	= "https://{$this->host}/fmi/data/{$this->version}/databases/{$database}/layouts/{$layout}/script/{$script_name}{$script_parameters}";
		
		return self::executeCurl($endpoint, 'GET', $curlopt_httpheader);
	}

    /**
     * ユーティリティ
     **/

    // レスポンスコードの取得
	public function getMessagesCode (string $response): string
    {
		$response_array = self::json2array($response);
		return $response_array['messages'][0]['code'];
	}

    // Bearer Session Token 取得
    public function getBearerSessionToken (string $response): string
    {
		$response_array = self::json2array($response);
		$this->bearer_session_token = $response_array['response']['token'];

		return $this->bearer_session_token;
	}

    // JSON を 配列へ変換
	public function json2array (string $json): array
    {
		$json   = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		return json_decode($json, true);
	}

    // cURL 実行
    static function executeCurl(string $curlopt_url, string $curlopt_customrequest, array $curlopt_httpheader = [], string $current_postfields = ''): string
    {
		$current_postfields	= !empty($current_postfields) ? json_encode(json_decode($current_postfields)) : '{}';

		$curl   = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlopt_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $curlopt_customrequest,
			CURLOPT_POSTFIELDS => $current_postfields,
			CURLOPT_HTTPHEADER => $curlopt_httpheader,
		));
	
		$response   = curl_exec($curl);
	
		curl_close($curl);
		return $response;
	}
}