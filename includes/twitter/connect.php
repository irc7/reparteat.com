<?php
	
	function reformatDate($s) {
		$t = explode(' ', $s);
		return $t[0] . ', ' . $t[2] . ' ' . $t[1] . ' ' . $t[5] . ' ' . $t[3] . ' ' . $t[4];
	}

	 function parseTweet($s) {
		return parseTags(parseNames(parseLinks($s)));
	}

	function parseLinks($s) {
		return preg_replace_callback(
						'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', create_function(
								'$matches', 'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
						), $s
		);
	}

	function parseNames($s) {
		return preg_replace('/@(\w+)/', '<a href="http://twitter.com/$1">@$1</a>', $s);
	}

	function parseTags($s) {
		return preg_replace('/\s+#(\w+)/', ' <a href="http://search.twitter.com/search?q=%23$1">#$1</a>', $s);
	}
	function object_to_array(stdClass $Class){
		$Class = (array)$Class;
		foreach($Class as $key => $value){
			if(is_object($value)&&get_class($value)==='stdClass'){
				$Class[$key] = object_to_array($value);
			}
		}
		return $Class;
	}
	function objectToArray($object){
		if(!is_object( $object ) && !is_array( $object )){
			  return $object;
		}
		if(is_object($object)){
			$object = get_object_vars( $object );
		}
		return array_map('objectToArray', $object );
	}          
	
	
	$tmhOAuth = new tmhOAuth(array(
            'consumer_key'        => $my_consumer_key,
            'consumer_secret'     => $my_consumer_secret,
            'user_token'          => $my_access_token,
            'user_secret'         => $my_access_token_secret,
            'curl_ssl_verifypeer' => false
        ));

	$code = $tmhOAuth->request(
          'GET', 
		  $tmhOAuth->url('1.1/account/verify_credentials'), 
		  array(
          	'include_entities' => false,
    		'skip_status' => true,
          )
        );

	$userInfoObj = json_decode($tmhOAuth->response['response']);
	
	$twitterName = $userInfoObj->screen_name;
	$fullName = $userInfoObj->name;
	$twitterAvatarUrl = $userInfoObj->profile_image_url;
	$feedTitle = $twitterName . ' Twitter ' . $twitterName . 'Timeline';


	$code = $tmhOAuth->request(
		'GET', 
		$tmhOAuth->url('1.1/statuses/user_timeline'), 
		array(
			'include_entities' => true,
			'count' => TotalTweets,
		)
	);

	/*actualizamos datos del usuario de Twitter*/
	/*condición para evitar que meta datos vacíos por si Twitter bloquea durante 15 minutos por exceso de peticiones*/
	if((isset($twitterName) && $twitterName != "") && (isset($fullName) && $fullName != "") && (isset($twitterAvatarUrl) && $twitterAvatarUrl != "") && (isset($feedTitle) && $feedTitle != "")) {		
		if($numero == 0){
			$qI = "insert into ".preBD."cache_twitter (TIME, FULLNAME, TWITTER_NAME, TWITTER_AVATAR_URL, FEED_TITLE) VALUES ";
			$qI .= " ('".$time."', '".$fullName."', '".$twitterName."', '".$twitterAvatarUrl."', '".$feedTitle."')";

			checkingQuery($connectBD,$qI);	
		}else if($numero > 0){
			$qU = "update ".preBD."cache_twitter set 
					TIME = '".$time."', 
					FULLNAME = '".$fullName."',
					TWITTER_NAME = '".$twitterName."',
					TWITTER_AVATAR_URL = '".$twitterAvatarUrl."',
					FEED_TITLE = '".$feedTitle."'
					where ID = 1";

			checkingQuery($connectBD,$qU);
		}
	}	
		
		
	$homeTimelineObj = json_decode($tmhOAuth->response['response']); 
	
	/*actualizamos los 10 últimos tweets*/
	/*borramos primero lo que hay y ponemos el autoincrement a 0*/
	$del = " TRUNCATE ".preBD."cache_tweets";
	checkingQuery($connectBD,$del);
	
	/*actualizamos los tweets*/
	foreach ($homeTimelineObj as $currentitem){ 
		$parsedTweet = tmhUtilities::entify_with_options(
			objectToArray($currentitem), 
			array(
				'target' => 'blank',
			)
		);
		$parsedTweet = emoji_unified_to_html($parsedTweet);
		$tweetDate = new DateTime($currentitem->created_at);
		
		if (isset($currentitem->retweeted_status)){
			$avatar = mysqli_real_escape_string($currentitem->retweeted_status->user->profile_image_url);
			$rt = mysqli_real_escape_string("&nbsp;&nbsp;&nbsp;&nbsp;[<em style=\'font-size:smaller;\'>Retweed by " . $currentitem->user->name . " <a href=\'http://twitter.com/" . $currentitem->user->screen_name . "\'>@" . $currentitem->user->screen_name . "</a></em>]");
			$tweeter =  mysqli_real_escape_string($currentitem->retweeted_status->user->screen_name);
			$fullname = mysqli_real_escape_string($currentitem->retweeted_status->user->name);
			$tweetTitle = emoji_unified_to_html($currentitem->retweeted_status->text);
			
		}else{
			$avatar = mysqli_real_escape_string($currentitem->user->profile_image_url);
			$rt = '';
			$tweeter = mysqli_real_escape_string($currentitem->user->screen_name);
			$fullname = mysqli_real_escape_string($currentitem->user->name);
			$tweetTitle = emoji_unified_to_html($currentitem->text);
		}
		$tweetTitle = mysqli_real_escape_string($tweetTitle);
		$parsedTweet = mysqli_real_escape_string($parsedTweet);
		
		
		$qIt = "insert into ".preBD."cache_tweets (DATETWEET, AVATAR, RT, TWEETER, TWEET_TITLE, FULLNAME_TWEET, PARSE_TWEET) 
				VALUES 
				('".$tweetDate->format("Y-m-d H:i:s")."', '".$avatar."', '".$rt."', '".$tweeter."', '".$tweetTitle."', '".$fullname."', '".$parsedTweet."')";
		checkingQuery($connectBD,$qIt);					
	}
	
?>