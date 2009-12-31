<?php

$meat_api = 'http://meatinapark.appspot.com/multiplebbqfinder';

# subiaco
#$lat = -31.946409;
#$lon = 115.822313;
#$dis = 5; # km

$lat = floatval($_GET['lat']);
$lon = floatval($_GET['lon']);
$dis = floatval($_GET['radius'] / 1000);
$cnt = array_key_exists('count', $_GET) ? floatval($_GET['count']) : 10;

$url = "$meat_api?lat=$lat&long=$lon&distance=$dis&bbqCount=$cnt";

$content = file_get_contents($url);

$bbqs = json_decode($content);

/*
 * Sample BBQ object:
 *  object(stdClass)#1 (7) {
 *    ["name"]=>
 *    string(13) "BURSWOOD PARK"
 *    ["longitude"]=>
 *    float(115.89132070541)
 *    ["bbqId"]=>
 *    int(1050)
 *    ["latitude"]=>
 *    float(-31.963675386245)
 *    ["matchFound"]=>
 *    string(4) "true"
 *    ["type"]=>
 *    string(7) "Unknown"
 *    ["distanceToBbq"]=>
 *    string(3) "0.9"
 *  }
 */

$pois = array();
if ($bbqs->matchCount > 0) {
    foreach ($bbqs->bbqList as $bbq) {
        $pois[] = array( 
            'actions' => array(),
            'distance' => 1000 * floatVal($bbq->distanceToBbq),
            'id' => $bbq->bbqId,
            'lat' => 1000000 * floatVal($bbq->latitude),
            'lon' => 1000000 * floatVal($bbq->longitude),
            'line2' => "Type: ".$bbq->type,
            'title' => $bbq->name,
            'type' => 0
        );
    }
}

$output = array( 
    'layer' => 'meatinapark',
    'errorCode' => 0,
    'errorString' => 'ok',
    'hotspots' => $pois,
);

header('Content-type: application/json');
print json_encode($output);

