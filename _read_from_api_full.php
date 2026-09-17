<?php
/*
import from link url:
{
  "products": [
    {
      "id": 30858,
      "name": "گوشت...",
      "category": [34,15],
      "attributes": {
        "pa_color": [20,22]
      }
    }
  ]
}
export from this script:

id = 30858
name = گوشت...
category = 34,15
attributes_pa_color = 20,22
*/
function readWordPressAPI($url){

    // 1. گرفتن JSON
    $json = file_get_contents($url);

    if(!$json){
        die("API not reachable");
    }

    $data = json_decode($json, true);

    if(!is_array($data)){
        die("Invalid JSON");
    }


	// 1. اگر مستقیم لیست بود (حالت تو)
    if(isset($data[0]) && is_array($data[0])){
        $rows = $data;
    }

    // 2. اگر object بود (wrapper دار)
    else {
        $rows = findFirstArray($data);
    }

    // 3. flatten
    $flat = [];

    foreach($rows as $r){
        $flat[] = flattenArray($r);
    }

    $headers = !empty($flat) ? array_keys($flat[0]) : [];

    return [$headers, $flat];
}
//-------------------------------------
function findFirstArray($data){

    // اگر خودش array لیستی بود
    if(isset($data[0]) && is_array($data[0])){
        return $data;
    }

    foreach($data as $key => $value){

        if(is_array($value)){

            // اگر این آرایه لیستی بود
            if(isset($value[0]) && is_array($value[0])){
                return $value;
            }

            // recursion برای nested
            $found = findFirstArray($value);
            if($found){
                return $found;
            }
        }
    }

    return [];
}
//-------------------------------------

function flattenArray($array, $prefix = ''){

    $out = [];

    foreach($array as $k => $v){

        $key = $prefix ? $prefix . "_" . $k : $k;

        if(is_array($v)){

            // array list → string
            if(isset($v[0]) && !is_array($v[0])){
                $out[$key] = implode(",", $v);
            } else {
                $out += flattenArray($v, $key);
            }

        } else {
            $out[$key] = $v;
        }
    }

    return $out;
}