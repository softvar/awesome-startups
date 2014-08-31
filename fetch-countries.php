<?php
/*
 * Function to fetch a page and return its content
 */
function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

$make  = 'http://www.startupranking.com/countries';
$url   = curl($make);
$regex = '/<td><a href="\/top\/([^"]*)">/';
preg_match_all($regex, $url, $countryNames);

$countryObject = new stdClass();
$arr = array();
foreach ($countryNames[1] as $key => $value) {
    array_push($arr, strip_tags($value));
}

$countryObject->countries = $arr;
file_put_contents('supported-countries.json', json_encode($countryObject));

?>