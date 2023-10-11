<?php

//$servername = "ecarpintero.sytes.net";
$servername = "localhost";
//$username = "ecarpin";
$username = "reparteat";
$password = "To6s#n70";
//$dbname = "Fourier";
$dbname = "reparteat_shop";
$botToken = "771897679:AAF5z6T5oIreukjyDBExoQz1AttD342WMHU";
$website = "https://api.telegram.org/bot".$botToken;






$update = file_get_contents('php://input');
$update = json_decode($update,TRUE);

$info = "Hola Enrique, este es tu bot";
$menu = '["Ⓒ","℗"],["Correctivo sin asignación"]';
$botonera_inline = '[{"text":"Boton","url":"www.google.es"},{"text":"Inline","switch_inline_query":""}],[{"text":"Inline2","callback_data":"ABC"}]';

$chatId = $update["message"]["chat"]["id"];
$name = $update['message']['from']['first_name'];
$message = $update["message"]["text"];

$query = $update['callback_query'];
$queryId = $query['id'];
$queryUserId = $query['from']['id'];
$queryData = $query['data'];


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    //die("Connection failed: " . $conn->connect_error);
    $info = "No se pudo conectar con la base de datos";
} 


newUser($chatId,$name);

$agg = json_encode($update,JSON_PRETTY_PRINT);
sendMessage($chatId,$agg,$menu,TRUE);

if($queryData == "ABC"){
	sendMessage($queryUserId,"Prueba OK",$menu,TRUE);
	exit();
}


switch ($message) {
	case 'a':
		sendMessage($chatId,$info,$botonera_inline,false);
		//sendMessage($chatId,$info,$botonera_inline,FALSE);
		break;
}



function sendMessage($chatId,$text,$menu,$tipo){
	if(isset($menu)){
		if($tipo == TRUE){
			$tastierino = '&reply_markup={"keyboard":['.urlencode($menu).'],"resize_keyboard":true}';
		}
		else{
			$tastierino = '&reply_markup={"inline_keyboard":['.urlencode($menu).'],"resize_keyboard":true}';
		}
	}
	$url = $GLOBALS[website]."/sendMessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode($text).$tastierino;
	file_get_contents($url);
}

function newUser($chatId,$nombre){
	//Devuelve falso si el usuario ya esta registrado y verdadero en caso contrario
	global $conn;
	$result = $conn -> query("SELECT usChatId FROM usuarios WHERE usChatId = $chatId");
	if($result->num_rows > 0){
		return FALSE;
	}
	else{
		$conn -> query("INSERT INTO usuarios (usChatId,usNombre) VALUES ($chatId,'$nombre')");
		return TRUE;
	}
}

function editMessage($chatId,$message_id,$newText){
	$url = $GLOBALS[website]."/editMessageText?chat_id=$chatId&message_id=$message_id&parse_mode=HTML&text=".urlencode($newText).$tastierino;
	file_get_contents($url);
}

?>