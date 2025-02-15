<?php

function json_to_array(string $json_object): array
{
  $json_object    = mb_convert_encoding($json_object, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
  return json_decode($json_object, true);
}
  
function json_get_element(string $json_file): array
{  
  $json_object    = file_get_contents($json_file, true);
  return json_to_array($json_object); 
}

function json_get_element_raw(string $json_file): string
{  
  return $json_object    = file_get_contents($json_file, true);
}