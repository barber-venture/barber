<?php
ob_start();
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
*/

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: Twitter Login ::</title>

<link href="../css/GmailForm.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
//echo "<pre>";
/* If method is set change API call made. Test is called by default. */
$content1 = $connection->get('account/verify_credentials');
//echo $content = simplexml_load_string($connection->get('account/verify_credentials'));
	//print_r($content1);die;
$content2['name'] = $content1->name;
$content2['screen_name']= $content1->screen_name;
$content2['location']	= $content1->location;
$content2['id_str']	= $content1->id_str;
$_SESSION['details']	= $content2;
if(isset($_SESSION['details']) && $_SESSION['details']!='')
		{	
		$details = $_SESSION['details'];
	
		$username = $details[screen_name];
		$name = $details[name];
		list($fname, $lname) = @split(' ', $name);
		$city = $details[location];
		$twitterid = $details[id_str];
		
			
                                  
          $userRequiredDetails = "";
		$userRequiredDetails.="?oid=".$twitterid."&first_name=".$fname."&last_name=".$lname."&email=".$username;
		header("location:".site_url."users/login/twitter".$userRequiredDetails);
		exit;
			
			
		}
include('html.inc');
