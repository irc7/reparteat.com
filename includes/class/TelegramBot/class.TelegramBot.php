<?php
class TelegramBot{
	
	public function __construct($apikey,$chatid){
		$this->apikey= $apikey;
		$this->chatid = $this->checkChatId($chatid);
	}
	public function sendMessage($msj, $boton){
		$a_params['chat_id'] = $this->chatid;
		if($boton != "") {
			$a_params['reply_markup'] = '{"inline_keyboard":['.$boton.']}';
		}
		$a_params['text'] = urlencode($msj);
		$a_params['parse_mode'] = 'HTML' ;
		$this->doCall("sendMessage",$a_params);
	}
	public function doCall($method,$a_params=array()){
	  $params = array();
	  foreach($a_params as $key=>$value){
		$params[]=$key.'='.$value;
	  }
	  
	  $endpoint = "https://api.telegram.org/bot".$this->apikey."/".$method;
	  if (count($params)>0){
		$endpoint.='?'.implode("&",$params);
	  }
	 

	  $data = json_decode(file_get_contents($endpoint),true);

	  return $data;

	}
	public function checkChatId($chatid){
		global $connectBD;
		
		$q = "select * from ".preBD."telegram_bot where usChatId = '".$chatid."'";
		$r = checkingQuery($connectBD, $q);
		
		if($row = mysqli_fetch_object($r)) {
			return $row->usChatId;
		}else{
			return false;
		}
	}
	public function checkNewUser($chatId, $name){
		global $connectBD;
		
		$q = "select * from ".preBD."telegram_bot where usChatId = '".$chatId."'";
		$r = checkingQuery($connectBD, $q);
		
		if($row = mysqli_fetch_object($r)) {
			$q = "UPDATE `".preBD."telegram_bot` SET `usNombre`='".$name."' WHERE usId = " . $row->usId;
			$r = checkingQuery($connectBD, $q);
			$msg = "Ya tiene creado un chat con RepartEat. ID = " . $chatId;
		}else {
			$q = "INSERT INTO ".preBD."telegram_bot (usChatId, usNombre) VALUES ('".$chatId."','".$nombre."')";
			$r = checkingQuery($connectBD, $q);
			$msg = "Ya esta listo para recibir las alertas sobre los pedidos que se realicen en RepartEat. ID Telegram: " . $chatId;
		}
		return $msg;
	}
}