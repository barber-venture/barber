<?php 

//$tweet_text = 'Hello Twitter';
//print "Posting...\n";
//echo $result = postToTwitter("testprojectsnow@gmail.com","test1234","hello test");
//print "Response code: " . $result . "\n";

include("twitter.php");

$twitter = new TwitterComponent;

$twitter->login("testprojectsnow@gmail.com","test1234");
$twitter->post_tweet("hello hii arun");

//function postToTwitter($twitterusername,$twitterpassword,$tweet)
//{
//$host = "http://twitter.com/statuses/update.xml?status=".urlencode(stripslashes(urldecode($tweet)));
//
//		$status = urlencode ( stripslashes ( urldecode ( $tweet ) ) );
//		
//		$curl = curl_init ();
//		curl_setopt ( $curl, CURLOPT_URL, $host );
//		curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 2 );
//		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
//		curl_setopt ( $curl, CURLOPT_POST, 1 );
//		curl_setopt ( $curl, CURLOPT_POSTFIELDS, "status=$status" );
//		curl_setopt ( $curl, CURLOPT_USERPWD, "$twitterusername:$twitterpassword" );
//		
//		$result = curl_exec ( $curl );
//		$resultArray = curl_getinfo ( $curl );
//		
//		echo "<pre>";
//		print_r($resultArray);
//		curl_close ( $curl );
//		
//		if ($resultArray ['http_code'] == 200)
//			return 'Tweet Posted';
//		else
//			return 'Could not post Tweet to Twitter right now. Try again later.';
//
//}




?>