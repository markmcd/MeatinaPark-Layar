<?php

$meat_api = 'http://meatinapark.appspot.com/bbqfinder';

# subiaco
#$lat = -31.946409;
#$lon = 115.822313;
#$dis = 5; # km

$lat = floatval($_GET['lat']);
$lon = floatval($_GET['lon']);
$dis = floatval($_GET['radius'] / 1000);

$content = file_get_contents("$meat_api?lat=$lat&long=$lon&distance=$dis");

$bbq = json_decode($content);

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

if ($bbq->matchFound == 'true') {
    $poi = array(array( 
        'actions' => array(),
        'distance' => 1000 * floatVal($bbq->distanceToBbq),
        'id' => $bbq->bbqId,
        'lat' => 1000000 * floatVal($bbq->latitude),
        'lon' => 1000000 * floatVal($bbq->longitude),
        'title' => $bbq->name,
        'type' => 0
    ));
}
else {
    $poi = array();
}

$output = array( 
    'layer' => 'meatinapark',
    'errorCode' => 0,
    'errorString' => 'ok',
    'hotspots' => $poi
);

header('Content-type: application/json');
print json_encode($output);

