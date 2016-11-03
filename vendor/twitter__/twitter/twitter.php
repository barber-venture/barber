<?php
class TwitterComponent  {
   
    var $user;
    var $token='';
    var $ch='';
    var $action='';

    // Logs in to twitter
    function login($user,$pass) {
        $this->user = $user;

        // Initialize CH
        if (!function_exists("curl_init")) die("This requires the CURL module, please install CURL for php.");
        $this->ch = curl_init();

        // Parse the login form
        curl_setopt($this->ch, CURLOPT_URL, "https://mobile.twitter.com/session/new");
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->user . ".txt");
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->user . ".txt");
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3 ");
        $page = curl_exec($this->ch);

        $page = stristr($page, "<div class='signup-body'>");
        preg_match("/form action=\"(.*?)\"/", $page, $this->action);
        preg_match("/input name=\"authenticity_token\" type=\"hidden\" value=\"(.*?)\"/", $page, $this->token);

        // Login and get your home page
		$strpost="";
        $strpost = "authenticity_token=".urlencode($this->token[1])."&username=".urlencode($user)."&password=".urlencode($pass);
		curl_setopt($this->ch, CURLOPT_URL, $this->action[1]);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $strpost);
        $page = curl_exec($this->ch);
        
        // Verify that we logged in ok
        preg_match("/\<div class=\"warning\"\>(.*?)\<\/div\>/", $page, $warning);
        if (isset($warning[1])) return $warning[1];
        $page = stristr($page,"<div class='tweetbox'>");
        preg_match("/form action=\"(.*?)\"/", $page, $this->action);
        preg_match("/input name=\"authenticity_token\" type=\"hidden\" value=\"(.*?)\"/", $page, $this->authenticity_token);
    }

    function post_tweet($status) {

        // Set status
        $tweet['display_coordinates']='';
        $tweet['in_reply_to_status_id']='';
        $tweet['lat']='';
        $tweet['long']='';
        $tweet['place_id']='';
        $tweet['text']=$status;
        $ar = array("authenticity_token" => $this->token[1], "tweet"=>$tweet);
        $data = http_build_query($ar);
        curl_setopt($this->ch, CURLOPT_URL, $this->action[1]);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $page = curl_exec($this->ch);

        return true;
    }


    function follow($author_name) {

        $action = "http://mobile.twitter.com/$author_name/follow";
        $ar = array("authenticity_token" => $this->token[1], "tweet"=>$tweet, 'last_url'=>'/$author_name', );
        $data = http_build_query($ar);
        curl_setopt($this->ch, CURLOPT_URL, $action);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $page = curl_exec($this->ch);

        return true;
    }
}
?>