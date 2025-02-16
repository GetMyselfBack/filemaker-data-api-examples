<?php

require_once(__DIR__ . '/php/bs-frame-class.php');
require_once(__DIR__ . '/php/bs-builder-class.php');
require_once(__DIR__ . '/php/php-functions.php');
require_once(__DIR__ . '/php/filemaker-data-api-class.php');

@$page_identity    = $_POST['page-identity'];
$page_identity     = $page_identity === null ? 1 : $page_identity;

require_once(__DIR__ . '/php/preferences.php');

require_once(__DIR__ . '/php/temporary-functions.php');

authorization_data();

$fmda           = new FileMakerDataApi($host, $version);

auth_login();
auth_validate_session();

$request_body_json_file = __DIR__ . '/json/standings-all-2024.json';
$request_body           = json_get_element_raw($request_body_json_file);
records_find_records();

auth_log_out();
