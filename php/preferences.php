<?php

$html_contents_json_file  = __DIR__ . '/../json/html-contents.json';
$temp_array               = json_get_element($html_contents_json_file);

// for HtmlBegin(), LoadColorModesSvg(), ColorModes()
$color_modes_js   = $temp_array['contents']['colorModesJs'];
$description      = $temp_array['contents']['description'];
$author           = $temp_array['contents']['author'];
$title            = $temp_array['contents']['title'];
$bootstrap_css    = $temp_array['contents']['bootstrapCss'];
$bs_css_integrity = $temp_array['contents']['bsCssIntegrity'];
$bs_frame_css     = $temp_array['contents']['bsFrameCss'];
$custom_css       = [];
foreach ($temp_array['contents']['customCss'] as $key => $value) {
  array_push($custom_css, $value['location']);
}

// for MainBegin()
$logo_svg       = $temp_array['contents']['logoSvg'];
$logo_svg_width = $temp_array['contents']['logoSvgWidth'];
$page_heading   = [];
$page_lead      = [];
foreach ($temp_array['pageContents'] as $key => $value) {
    array_push($page_heading, $value['heading']);
    array_push($page_lead, $value['lead']);
}

$form_heading       = [];
$form_action        = [];
$form_button_color  = [];
$form_button_text   = [];
foreach ($temp_array['formContents'] as $key => $value) {
    array_push($form_heading, $value['heading']);
    if ($value['action'] === 'PHP_SELF')
        $value['action'] = "$_SERVER['PHP_SELF']";
    array_push($form_action, $value['action']);
    array_push($form_button_color, $value['buttonColor']);
    array_push($form_button_text, $value['buttonText']);
}

// for InfoItem(), GetInfo()
$info_heading         = $temp_array['contents']['infoHeading'];
$info_number_of_items = $temp_array['contents']['infoNumberOfItems'];

$info_item_heading  = [];
$info_item_content  = [];
foreach ($temp_array['infoItems'] as $key => $value) {
  array_push($info_item_heading, $value['heading']);
}

// for CreateForm(), CreatePagination(), MainEnd()
$form_controls           = $temp_array['formControls'];

for ($i = 0; $i < count($form_controls); $i++) {
  $form_controls[$i]['disabled']  = $page_indentity >= 2 ? 'disabled' : '';
}

$form_preferences = [
  $form_heading[$page_indentity],
  $form_action[$page_indentity],
  $form_button_color[$page_indentity],
  $form_button_text[$page_indentity]
];

$response_field_data  = [];

// for CreateFooter()
$copyright_year   = $temp_array['contents']['copyrightYear'];
$copyright_holder = $temp_array['contents']['copyrightHolder'];

$link_items       = $temp_array['linkItems'];

// for HtmlEnd()
$bootstrap_js     = $temp_array['contents']['bootstrapJs'];
$bs_js_integrity  = $temp_array['contents']['bsJsIntegrity'];
$custom_js        = [];
foreach ($temp_array['contents']['customJs'] as $key => $value) {
  array_push($custom_js, $value['location']);
}
