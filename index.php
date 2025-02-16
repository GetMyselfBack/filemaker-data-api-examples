<?php

require_once(__DIR__ . '/php/bs-frame-class.php');
require_once(__DIR__ . '/php/bs-builder-class.php');
require_once(__DIR__ . '/php/php-functions.php');
require_once(__DIR__ . '/php/filemaker-data-api-class.php');

@$page_identity        = $_POST['page-identity'];
$page_identity         = $page_identity === null ? 1 : $page_identity;

require_once(__DIR__ . '/php/preferences.php');

require_once(__DIR__ . '/php/temporary-functions.php');

authorization_data();

$fmda           = new FileMakerDataApi($host, $version);

if (!isset($result)) {
    // 最初のアクセス
    auth_login();
    auth_validate_session();

    $request_body_json_file = __DIR__ . '/json/standings-all-2024.json';
    $request_body           = json_get_element_raw($request_body_json_file);
    records_find_records();

    auth_log_out();
}

BsFrame::HtmlBegin($color_modes_js, $description, $author, $title, $bootstrap_css, $bs_css_integrity, $bs_frame_css, $custom_css);
BsFrame::LoadColorModesSvg();
BsFrame::ColorModes();

BsBuilder::MainBegin($logo_svg, $logo_svg_width, $page_heading[$page_identity], $page_lead[$page_identity]);

BsBuilder::InfoItem($info_heading, $info_number_of_items, $info_item_heading, $info_item_content);
BsBuilder::GetInfo();

BsBuilder::CreateForm($form_preferences, $form_controls, $response_field_data);
BsBuilder::CreatePagination($page_identity);
BsBuilder::MainEnd();
BsBuilder::CreateFooter($copyright_year, $copyright_holder, $link_items);

BsFrame::HtmlEnd($bootstrap_js, $bs_js_integrity, $custom_js);
