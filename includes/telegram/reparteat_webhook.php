<?php
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	require_once ("../../pdc-reparteat/includes/config.inc.php");
	require_once ("../../includes/functions.inc.php");
	require ("../class/TelegramBot/class.TelegramBot.php");
	
	//$request = '{"update_id":144096165,"message":{"message_id":3748,"from":{"id":5038507019,"is_bot":false,"first_name":"Ismael","username":"IsmaelRC7","language_code":"es"},"chat":{"id":5038507019,"first_name":"Ismael","username":"IsmaelRC7","type":"private"},"date":1657039167,"text":"/start","entities":[{"offset":0,"length":6,"type":"bot_command"}]}}';
	$request = file_get_contents("php://input");
	$dataRequest = $request;
	$request = json_decode($request,TRUE);
	$date = date('Y-m-d H:i:s');
	
	$chatId = $request["message"]["chat"]["id"];
	$name = $request["message"]["from"]["first_name"] . " " . $request["message"]["from"]["last_name"];
	
	if($request["message"]["text"] == "/start") {
		$content = "\n\n" . $date . "\n".$dataRequest . "\n\n";
		file_put_contents("webhook_start.log", $content, FILE_APPEND);
		
		$botObj = new TelegramBot(TELEGRAMTOKEN, $chatId);
		
		$text = $botObj->checkNewUser($chatId, $name);
		$botObj->sendMessage($text,"");
		
	} else {
		$content = "\n\n" . $date . "\n".$dataRequest . "\n\n";
		file_put_contents("webhook.log", $content, FILE_APPEND);
	}
	
?>