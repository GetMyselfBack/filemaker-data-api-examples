<?php

class BsBuilder {

  static function MainBegin (
    string $logo_svg,
    string $logo_svg_width,
    string $heading,
    string $lead
  ): void
  {
    echo <<<_HTML_
<div class="container">
  <main>
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="{$logo_svg}" alt="" width="{$logo_svg_width}">
      <h2>{$heading}</h2>
      <p class="lead">{$lead}</p>
    </div> <!-- / .py-5 .text-center -->

    <div class="row g-5">

_HTML_;

  }

  static function InfoItem (
    string $info_heading,
    int $info_number_of_items,
    array &$info_item_heading,
    array &$info_item_content
  ): void
  {
    echo <<<_HTML_
<div class="col-md-5 col-lg-4 order-md-last">
  <h4 class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-primary">{$info_heading}</span>
    <span class="badge bg-primary rounded-pill">{$info_number_of_items}</span>
  </h4>

  <ul class="list-group mb-3">

_HTML_; // begin

    for ($i = 0 ; $i < count($info_item_heading) ; $i++) {

      echo <<<_HTML_
<li class="list-group-item d-flex justify-content-between lh-sm">
  <div>
    <h6 class="my-0">{$info_item_heading[$i]}</h6>

_HTML_;

      if (is_numeric($info_item_content[$i])) {
        echo <<<_HTML_
  </div>
  <span class="text-success">$info_item_content[$i]</span>
</li>

_HTML_;

      } else {
        echo <<<_HTML_
    <small class="text-body-secondary">$info_item_content[$i]</small>
  </div>
</li>

_HTML_;

      } // if (is_numeric($info_item_content[$i]))
    } // for ($i = 0 ; $i < count($info_item_heading) ; $i++)

    echo "</ul>\n"; // end
  }

  static function GetInfo ( ): void {
    echo <<<_HTML_
  <form action="{$_SERVER['PHP_SELF']}" method="POST" class="card p-2">
    <div class="input-group">
      <input type="text" class="form-control" name="recordId" placeholder="recordId">
      <input type="hidden" class="visually-hidden" name="getInfo" value="true">
      <button type="submit" class="btn btn-secondary">取得する</button>
    </div>
  </form>
</div>

_HTML_;

  }

  static function CreateForm (
    array &$form_prefernces,
    array &$form_controls,
    array &$field_data,
  ): void {

    echo <<<_HTML_
<div class="col-md-7 col-lg-8">
  <h4 class="mb-3">{$form_prefernces[0]}</h4>
    <form action="{$form_prefernces[1]}" method="POST" class="needs-validation" novalidate>
      <div class="row g-3">
    
_HTML_; // begin

    $i = 0;
    foreach ($form_controls as $key => $value) {
      $required         = '';
      $secondary_label  = '';
      if ($value['required'] === '1')
        $required         = 'required';
      else
        $secondary_label  = '<span class="text-body-secondary">（任意）</span>';

      if ($value['type'] === 'select') {
        echo <<<_HTML_
<div class="col-sm-6">
  <label for="{$value['id']}" class="form-label">{$value['label']}{$secondary_label}</label>
  <select class="form-select" id="{$value['id']}" name="{$value['name']}" {$required} {$value['disabled']}>
    <option value="">選択...</option>

_HTML_;

        foreach ($value['option'] as $option_key => $option_value) {
          $selected       = $field_data[$i] === $option_value['value'] ? 'selected' : '';
          $current_value  = $selected === 'selected' ? $field_data[$i] : $option_value['value'];
          echo "<option value=\"{$current_value}\" {$selected} >{$option_value['value']}</option>\n";
        }
        echo "</select>\n";

      } else {
        echo <<<_HTML_
<div class="col-sm-6">
  <label for="{$value['id']}" class="form-label">{$value['label']}{$secondary_label}</label>
  <input type="{$value['type']}" class="form-control" id="{$value['id']}" name="{$value['name']}"  value="{$field_data[$i]}" placeholder="{$value['placeholder']}" {$required} {$value['disabled']}>

_HTML_;

      } // if ($value['type'] === 'select')

      if (!empty($required)) {
        echo <<<_HTML_
<div class="invalid-feedback">
  {$value['feedback']}
</div>

_HTML_;

      } // if (!empty($required))

      echo "</div>\n";

      $i += 1;
    } // foreach ($form_controls as $key => $value)

  echo <<<_HTML_
  </div> <!-- / .row -->

  <hr class="my-4">

  <button class="w-100 btn {$form_prefernces[2]} btn-lg" type="submit">{$form_prefernces[3]}</button>
</form> <!-- / .needs-validation -->

_HTML_;

  }

  static function CreatePagination (
    int $page_identity
  ): void
  {

    if ($page_identity !== 0) {
      echo <<<_HTML_
<hr class="my-4">

<nav class="container text-center my-4" aria-label="Search results pages">
  <div class="row justify-content-md-center">
    <div class="col-auto text-center">
      <ul class="pagination">
        <li class="page-item disabled">
          <a class="page-link">前</a>
        </li>

        <li class="page-item active" aria-current="page">
          <a class="page-link" href="#">1</a>
        </li>

        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">4</a></li>
        <li class="page-item"><a class="page-link" href="#">5</a></li>
        <li class="page-item"><a class="page-link" href="#">6</a></li>
        <li class="page-item"><a class="page-link" href="#">7</a></li>
        <li class="page-item"><a class="page-link" href="#">8</a></li>
        <li class="page-item"><a class="page-link" href="#">9</a></li>
        <li class="page-item"><a class="page-link" href="#">10</a></li>

        <li class="page-item">
          <a class="page-link" href="#">次</a>
        </li>
      </ul>
    </div>
  </div> <!-- / .row -->
</nav>

_HTML_;

    } // if ($page_identity !== 0)

  }

  static function MainEnd ( ): void
  {
    echo <<<_HTML_
    </div>
  </div> <!-- / .row -->
</main>

_HTML_;

  }

  static function CreateFooter (
    string $copyright_year,
    string $copyright_holder,
    array &$link_items
  ): void
  {
    echo <<<_HTML_
    <footer class="my-5 pt-5 text-body-secondary text-center text-small">
      <p class="mb-1">Copyright &copy; {$copyright_year} {$copyright_holder}. All rights reserved.</p>
      <ul class="list-inline">

_HTML_;

    foreach ($link_items as $key => $value) {
      echo <<<_HTML_
<li class="list-inline-item"> 
<form id="{$value['formId']}" method="POST">
  <input type="hidden" name="page-identity" value="{$value['pageIdentity']}">
</form>
<a href="#" id="{$value['anchorId']}">{$value['textContent']}</a>
</li>

_HTML_;

    }

    echo <<<_HTML_
  </ul>
</footer>
</div> <!-- / .container -->

_HTML_;

  }
}
