<?php
$BASE_URL = 'http://www.startupranking.com/';

$readSupportedCountries   = file_get_contents('supported-countries.json');
$listOfSupportedCountries = json_decode($readSupportedCountries, true);
/*
 * Function to fetch a page and return it contents
 */
function curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

/*
 * Append Newline `$times` times to the specfied `$str` string
 */

function insertNewline($str, $times)
{
    for ($k = 0; $k < $times; $k++) {
        $str .= PHP_EOL;
    }
    return $str;
}

/*
 * Append text `$text` to the specified `$dest` destination string.
 */

function appendText($dest, $text)
{
    $dest .= $text;
    return $dest;
}

/*
 * Fetch page content
 */
function fetchPageContent($url) {
    $make         = $url;
    $content      = curl($make);
    $doc          = new DOMDocument();
    @$doc->loadHTML($content);
    $xml          = simplexml_import_dom($doc); // just to make xpath more simple
    return $xml;

}
/*
 *  Prepare README Content
 */
$readmeOutput = file_get_contents('pre-readme-content.txt');
date_default_timezone_set("UTC");
$dateNtime = date('Y-m-d H:i:s');
$readmeOutput = insertNewline($readmeOutput, 2);
$readmeOutput = appendText($readmeOutput, '**Last Updated On:** ' . $dateNtime . ' (UTC)');
$readmeOutput = insertNewline($readmeOutput, 2);
$readmeOutput = appendText($readmeOutput, '## List of Top 100 Startups across globe');
$readmeOutput = insertNewline($readmeOutput, 2);
$make         = 'http://www.startupranking.com/top/';
// get page contents
$url          = curl($make);
$regex        = "/<div class=\"name\"?>.*<a.*?>([^`]*?)<\/a><\/div>/";
preg_match_all($regex, $url, $startupNames);

$regex        = '/<div class="name"?>.*<a href="\/([^"]*)">/';
preg_match_all($regex, $url, $startupUrlsPath);

$doc = new DOMDocument();
@$doc->loadHTML($url);
$xml       = simplexml_import_dom($doc); // just to make xpath more simple
$locations = $xml->xpath('//*[@class="f32"]//img');

foreach ($startupNames[0] as $key => $value) {
    $value = strtolower(strip_tags($value));
    $value = str_replace(' ', '-', $value);
    if ($value != '') {
        $startupUrl   = $BASE_URL . lcfirst($startupUrlsPath[1][$key]);
        $readmeOutput = appendText($readmeOutput, ($key + 1) . '. [' . $value . '](' . $startupUrl . ') - ' . $locations[$key]['title']);
        $readmeOutput = insertNewline($readmeOutput, 1);
    }
}

$url          = 'http://www.startupranking.com/countries'; 
$xml          = fetchPageContent($url);
$startupNum = $xml->xpath('//tr[@class="f32"]//td[3]');

$readmeOutput = insertNewline($readmeOutput, 1);
$readmeOutput = appendText($readmeOutput, '## List of Countries');
$readmeOutput = insertNewline($readmeOutput, 2);
$readmeOutput = appendText($readmeOutput, 'This list is sorted in descending order of the number of startups a country has. Click the country to view the startups.');
$readmeOutput = insertNewline($readmeOutput, 2);

foreach ($listOfSupportedCountries['countries'] as $key => $value) {
    $noOfStartups = $startupNum[$key];
    $splitCountryName = explode("-", $value);
    $countryName      = ucfirst($splitCountryName[0]);
    for ($i = 1; $i < count($splitCountryName); $i++) {
        $countryName = $countryName . " " . ucfirst($splitCountryName[$i]);
    }
    $readmeOutput = appendText($readmeOutput, '- [' . $countryName . '](countries/' . $value . '.md)' . ' - ' . $noOfStartups);
    if ($noOfStartups > 1) {
        $readmeOutput = appendText($readmeOutput, ' startups');
    }
    else if ($noOfStartups == 1) {
        $readmeOutput = appendText($readmeOutput, ' startup');
    }
    $readmeOutput = insertNewline($readmeOutput, 1);
}

$postReadmeContent = insertNewline($postReadmeContent, 1);
$postReadmeContent = appendText($postReadmeContent, file_get_contents('post-readme-content.txt'));
$readmeOutput      = appendText($readmeOutput, $postReadmeContent);
file_put_contents('README.md', $readmeOutput);

/*for ($j = 0; $j < count($listOfSupportedCountries['countries']); $j++) {

    $hyphenSeparatedCountryName = $listOfSupportedCountries['countries'][$j];
    $splitCountryName           = explode("-", $hyphenSeparatedCountryName);
    $countryName                = ucfirst($splitCountryName[0]);
    for ($i = 1; $i < count($splitCountryName); $i++) {
        $countryName = $countryName . " " . ucfirst($splitCountryName[$i]);
    }
    $countryOutput = '## Top Ranked Startups in ' . $countryName;
    $countryOutput = insertNewline($countryOutput, 2);
    $make          = 'http://www.startupranking.com/top/' . $hyphenSeparatedCountryName;
    // get page contents
    $url           = curl($make);
    $regex         = "/<div class=\"name\"?>.*<a.*?>([^`]*?)<\/a><\/div>/";
    preg_match_all($regex, $url, $startupNames);

    $regex         = '/<div class="name"?>.*<a href="\/([^"]*)">/';
    preg_match_all($regex, $url, $startupUrlsPath);

    foreach ($startupNames[0] as $key => $value) {
        $value = strtolower(strip_tags($value));
        $value = str_replace(' ', '-', $value);
        if ($value != '') {
            $startupUrl    = $BASE_URL . lcfirst($startupUrlsPath[1][$key]);
            $countryOutput = appendText($countryOutput, ($key + 1) . '. [' . $value . '](' . $startupUrl . ')');
            $countryOutput = insertNewline($countryOutput, 1);
        }
    }
    $countryOutput = insertNewline($countryOutput, 1);
    $filename      = 'countries/' . $hyphenSeparatedCountryName . '.md';
    file_put_contents($filename, $countryOutput);
    $countryOutput = '';
}*/
?>