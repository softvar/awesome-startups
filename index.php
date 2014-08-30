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

function insertNewline($str, $times) {
	for ($k = 0; $k < $times; $k++) {
		$str .= PHP_EOL;
	}
	return $str;
}

/*
 * Append text `$text` to the specified `$dest` destination string.
 */

function appendText($dest, $text) {
	$dest .= $text;
	return $dest;
}

/*
 *	Prepare README Content
 */
$readmeOutput = file_get_contents('pre-readme-content.txt');

$readmeOutput = appendText($readmeOutput, '## List of Top 100 Startups across worlds');
$readmeOutput = insertNewline($readmeOutput, 2);
$make         = 'http://www.startupranking.com/top/';
// get page contents
$url          = curl($make);
//echo $url;
$regex        = "/<div class=\"name\"?>.*<a.*?>([^`]*?)<\/a><\/div>/";

preg_match_all($regex, $url, $startupNames);
foreach ($startupNames[0] as $key => $value) {
    $value = strtolower(strip_tags($value));
    $value = str_replace(' ', '-', $value);
    if ($value != '') {
        $startupUrl = $BASE_URL . lcfirst($value);
        $readmeOutput = appendText($readmeOutput, ($key + 1) . '. [' . $value . '](' . $startupUrl . ')');
        $readmeOutput = insertNewline($readmeOutput, 1);
    }
}
$readmeOutput = insertNewline($readmeOutput, 1);
$readmeOutput = appendText($readmeOutput, '## List of Countries(sorted in descending order of the number of startups)');
$readmeOutput = insertNewline($readmeOutput, 2);

foreach ($listOfSupportedCountries['countries'] as $key => $value) {
    //echo ucfirst($value);
    $splitCountryName = explode("-", $value);
    $countryName      = ucfirst($splitCountryName[0]);
    for ($i = 1; $i < count($splitCountryName); $i++) {
        $countryName = $countryName . " " . ucfirst($splitCountryName[$i]);
    }
    $readmeOutput = appendText($readmeOutput, '- [' . $countryName . '](countries/' . $value . '.md)');
    $readmeOutput = insertNewline($readmeOutput, 1);
}

$postReadmeContent = file_get_contents('post-readme-content.txt');
$readmeOutput = appendText($readmeOutput, $postReadmeContent);
file_put_contents('README.md', $readmeOutput);

for ($j = 0; $j < 2; $j++) { // $j < count($listOfSupportedCountries)
    $hyphenSeparatedCountryName = $listOfSupportedCountries['countries'][$j];
    //echo ucfirst($value);
    $splitCountryName           = explode("-", $hyphenSeparatedCountryName);
    $countryName                = ucfirst($splitCountryName[0]);
    for ($i = 1; $i < count($splitCountryName); $i++) {
        $countryName = $countryName . " " . ucfirst($splitCountryName[$i]);
    }
    $countryOutput = '## ' . $countryName ;
    $countryOutput = insertNewline($countryOutput, 2);
    $make         = 'http://www.startupranking.com/top/' . $hyphenSeparatedCountryName;
    // get page contents
    $url          = curl($make);
    //echo $url;
    $regex        = "/<div class=\"name\"?>.*<a.*?>([^`]*?)<\/a><\/div>/";

    preg_match_all($regex, $url, $startupNames);
    foreach ($startupNames[0] as $key => $value) {
        $value = strtolower(strip_tags($value));
        $value = str_replace(' ', '-', $value);
        if ($value != '') {
            $startupUrl = $BASE_URL . lcfirst($value);
            $countryOutput = appendText($countryOutput, ($key + 1) . '. [' . $value . '](' . $startupUrl . ')');
            $countryOutput = insertNewline($countryOutput, 1);
        }
    }
    $countryOutput = insertNewline($countryOutput, 1);
    $filename = 'countries/' . $hyphenSeparatedCountryName . '.md';
    file_put_contents($filename, $countryOutput);
    $countryOutput = '';
}
?>