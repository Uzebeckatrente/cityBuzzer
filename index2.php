<?php

$consumer_key = 'dkcVFKUqojO4pC3w4ADbTwa9t';
$consumer_secret = 'W2GmYUt12qjRMUirbYsZxXo515QvQANQBc2Hu6xnhgJqyDPW58';
$access_token = '325189589-eemt7b88xkjoGOkhzZc7mZCpkGKfKHrXxeOPswL2';
$access_token_secret = '5mhi23izfoc6kJSEK8uTZFYmNT4EAluZ9gpCBTT6qrnfg';



require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

$content = $connection->get("account/verify_credentials");

// var_dump($content);

// $new_status = $connection->post("statuses/update",["status" => "brit00000"]);

// print_r($new_status);

$params = array('q' => '#flyeaglesfly',"geocode"=>'52.532069,13.379694,1500000000km','since_id'=>'012619984051000','result_type'=>'mixed');

// $new_status = $connection->post("statuses/update",["status" => "just doing some homie #citybuzzer testing don't mind me"]);
// var_dump($params);

// printf("borf");
$statuses = $connection->get("search/tweets",$params);


$borf = json_encode($statuses,true);
$otro = json_encode($statuses,false);

// print_r($borf);

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

foreach ($statuses->statuses as $key => $tweet) {
    // print_r($tweet->text);
    // $tweet->user->name;
    // print($tweet['text']);
    // print_r($tweet);
    // print("\n\n");
    // var_dump($tweet);
    array_push($tweets,$tweet->text);
}


print_r($statuses);
// print_r($tweets[0]);

printf("\nhello world");
print_r($tweets);
// printf("sizeof statuses: ".sizeof($statuses));
// print_r($statuses);
// printf($statuses."n0az");

// for ($i = 0; $i < sizeof($statuses); $i ++){

//  // printf($statuses[$i]." ".$i);
//  echo "Borffff\n".sizeof($statuses).$statuses['0'];
// }

?>


