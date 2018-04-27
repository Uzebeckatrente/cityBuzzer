<?php





require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;


function getLatLong(){
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_URL => 'https://ipinfo.io/json',
	    CURLOPT_RETURNTRANSFER => 1
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);

	curl_close($curl);

	#### getting lat,long####

	$myIP = json_decode($resp,true)['ip'];
	// printf($myIP);

	$_API = "bc9e49a552d3076e511cc5380f261ea86440f24f1fc89dde16a20bfccf48dc7c";
	$_URL = "http://api.ipinfodb.com/v3/ip-city/?key=$_API&ip=$myIP&format=json";


	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_URL => $_URL,
	    CURLOPT_RETURNTRANSFER => 1
	));
	// Send the request & save response to $resp
	$ipinfo = curl_exec($curl);


	$ipinfoArray = json_decode($ipinfo,true);

	$longitude = $ipinfoArray["longitude"];
	$latitude = $ipinfoArray["latitude"];

	// printf("lat: ".$latitude." and long: ".$longitude);

	curl_close($curl);

	return array(41.373113, 2.150515);

	// return array($latitude,$longitude);
}



function getTweetsByHashtagsLatLon($a,$latitude,$longitude){

	$consumer_key = 'dkcVFKUqojO4pC3w4ADbTwa9t';
	$consumer_secret = 'W2GmYUt12qjRMUirbYsZxXo515QvQANQBc2Hu6xnhgJqyDPW58';
	$access_token = '325189589-eemt7b88xkjoGOkhzZc7mZCpkGKfKHrXxeOPswL2';
	$access_token_secret = '5mhi23izfoc6kJSEK8uTZFYmNT4EAluZ9gpCBTT6qrnfg';
	// $ret = getLatLong();

	#####getting all tweets with appropriate hashtags and shit, within latitude and longitude and shit yo####
	// $keywords = array("brit00asdfasdf","brit00000");
	
	// $hashtags = split(",",$a);

	list($hashtag1, $hashtag2) = explode(',', $a, 2);

	$search_string = str_replace(","," OR ",$a);





	$latitude = 52.513786599999996;
	$longitude = 13.396233400000028;

	$connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

	// $content = $connection->get("account/verify_credentials");

	// var_dump($content);




	///this is how it really should be. the problem is that not enough tweets
	//share there geolocation that have been tweeted in the last time frame
	

	// $params = array('q' => $search_string,"geocode" => $latitude.','.$longitude.',20mi', "count" => 100, "exclude_replies" => true);



///the old code:::::
	// $params = array('q' => $search_string,'count' => 100, "exclude_replies" => true);


	// $statuses = $connection->get("search/tweets",$params);
//////

	$params1 = array('q' => $hashtag1,'count' => 300, "exclude_replies" => true);
	
	$statuses1 = $connection->get("search/tweets",$params1);

	$params2 = array('q' => $hashtag2,'count' => 300, "exclude_replies" => true);
	
	$statuses2 = $connection->get("search/tweets",$params2);

	// var_dump($statuses);
	// $borf = json_encode($statuses,true);
	// $otro = json_encode($statuses,false);

	// return $borf;

	// print_r($statuses);
	// printf("borf diddly|N");
	// print_r($statuses);

	// // printf("hello world");
	// // // printf("sizeof statuses: ".sizeof($statuses));
	// // // print_r($statuses);
	// print("hello");
	// $content = $connection->get('search/tweets', array('q' => '#brit00000'));
	// print("meee");
	// print_r($content->statuses);

	$tweets = array();

	// foreach ($statuses->statuses as $key => $tweet) {
		
	// 	array_push($tweets,$tweet->text);
	// }

	foreach ($statuses2->statuses as $key => $tweet) {
		
		array_push($tweets,$tweet->text);
	}

	foreach ($statuses1->statuses as $key => $tweet) {
		
		array_push($tweets,$tweet->text);
	}

	// $allen = json_decode($content);


	// print_r("borf: ".$borf);
	// print_r("otro: ".$otro);

	// print_r("bssss: ".$borf['statuses']);
	// print_r("hullo: ".$borf->statuses);
	// print_r("22222: ".$otro[0]);

	// print("nubmer of responses: ".sizeof($content->statuses));


	// printf("done");

	// print_r($params);
	// print_r($content);
	// var_dump($tweets);
	
	return json_encode($tweets);
}
###getting user's (my) ip address###

function getNearbyCities(){
	
	$ret = getLatLong();
	$latitude = $ret[0];
	$longitude = $ret[1];

	// set request options
	$responseStyle = 'short'; // the length of the response
	$citySize = 'cities15000'; // the minimal number of citizens a city must have
	$radius = 100; // the radius in KM
	$maxRows = 5; // the maximum number of rows to retrieve
	$username = 'felixherron'; // the username of your GeoNames account

	// get nearby cities based on range as array from The GeoNames API
	$nearbyCities = json_decode(file_get_contents('http://api.geonames.org/findNearbyPlaceNameJSON?lat='.$latitude.'&lng='.$longitude.'&style='.$responseStyle.'&cities='.$citySize.'&radius='.$radius.'&maxRows='.$maxRows.'&username='.$username, true));

	$retCities = array();

	foreach ($nearbyCities as $key => $cities){
		for ($i = 0; $i < $maxRows; $i ++){
			array_push($retCities, $cities[$i]->name);
		}
		
	}

	return $retCities;


}

function getLatLongFromCity($city){
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here

	str_replace(" ","+",$city);

	$aParams = array(
	    CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&key=AIzaSyCELRQOw47u6MNjnSb5LCAVPZSsflfCFNU',
	    CURLOPT_RETURNTRANSFER => 1
	);

	curl_setopt_array($curl, $aParams);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 

	//var_dump($aParams);
	// Send the request & save response to $resp
	$resp = curl_exec($curl);

//the response is json
	$aJson = json_decode($resp,true);

	if(!empty(curl_error())) {
		throw new Exception(curl_error());
	} 

	$tmp = array_pop($aJson['results']);

	$aJsonResponse =  $tmp['geometry'];
	 



	curl_close($curl);

	return $aJsonResponse;

}

$googleAPIKey = 'AIzaSyCELRQOw47u6MNjnSb5LCAVPZSsflfCFNU';



?>