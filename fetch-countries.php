<?php
/*
 * Function to fetch a page and return it content
 */
function curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $return = curl_exec($ch);
    curl_close ($ch);
    return $return;
}

    //echo $factor;
    $make= 'http://www.startupranking.com/countries';
    // get page contents
    $url = curl($make);
    //echo $url;
    $regex = "/<div class=\"name\"?>.*<a.*?>([^`]*?)<\/a><\/div>/";

    /*$regex = "/<td>.*<a.*?>([^`]*?)<\/a><\/td>/";
    */$regex = '/<td><a href="\/top\/([^"]*)">/';
    preg_match_all($regex, $url, $restoNames);
    $obj = new stdClass();
    $arr = array();
    //echo $restoNames[0][0];
    foreach($restoNames[1] as $key => $value) {
      //echo strip_tags($value);
      array_push($arr, strip_tags($value));
    }
    $obj->countries = $arr;
    file_put_contents('supported-countries.json', json_encode($obj));
    //print_r($restoNames[0]); //View the array to see if it worked

?>