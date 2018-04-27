<?php
//var_dump($_SERVER);
if ($_SERVER["REQUEST_METHOD"] == "POST"):
	require "func.php";

	//var_dump("hello world");

	$city = trim($_POST["city"]);
	$hashtags = trim($_POST["hashtags"]);
	
	$resp = getLatLongFromCity($city);

	$test = array();

	// array_push($test, $hashtags);
	// echo $test;
	// echo $resp;

	$lat = $resp["location"]["lat"];

	$lng = $resp["location"]["lng"];

	// echo $lat;

	$jResp = json_encode($resp["location"]);

	// echo $hashtags." ".$lat." ".$lng;

	$ret = getTweetsByHashtagsLatLon($hashtags,$lat,$lng);

	// var_dump($jResp);
	// var_dump($ret);

	//var_dump($resp);

	echo $ret;

endif;

?>
