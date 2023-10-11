<?php
setlocale(LC_TIME, "es_ES");
date_default_timezone_set("Europe/Paris");
require_once ("api.php");
require_once ("../pdc-reparteat/includes/database.php");
$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
require_once ("../pdc-reparteat/includes/config.inc.php");
require_once ("../includes/functions.inc.php");
require_once ("../includes/checkSession.php");
require_once "../includes/class/class.System.php";
require "../includes/class/UserWeb/class.UserWeb.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use UserWeb;
use api;

//POST METHOD: login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    //ALTER TABLE `ree_user_web` ADD `LAST_TOKEN` varchar(255) NULL;
    if($token) {
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        $q="UPDATE ".preBD."user_web SET LAST_TOKEN = '".$postdata->token."' WHERE ID=".$user->ID;
        //echo $q;
        checkingQuery($connectBD, $q);
        http_response_code(200);
        echo json_encode($user);
    } else {
        api::sendError("Sin acceso");
    }
}
//GET METHOD: Me
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        http_response_code(200);
        echo json_encode($user);
    } else {
        api::sendError("Sin acceso");
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        if(isset($postdata->test))
        {
            //var_dump($user);
            sendGCM("RepartEat Test", "Esto es un test de envío push", $user->LAST_TOKEN);
        }else{   
            
            $q="SELECT DISTINCT IDUSER, LAST_TOKEN from ".preBD."user_web_supplier_assoc a INNER JOIN ".preBD."user_web u ON a.IDUSER=u.ID where TYPE='proveedor' AND LAST_TOKEN IS NOT NULL AND LAST_TOKEN!=''";
            //echo $q;
            $result = checkingQuery($connectBD, $q);
            foreach($result as $row)
            {
                sendGCM($postdata->titulo, $postdata->texto, $row["LAST_TOKEN"]);
            }
            http_response_code(200);
            echo json_encode($user);
        }
    } else {
        api::sendError("Sin acceso");
    }
}

function sendGCM($title, $message, $token) {


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = '{
        "to": "'.$token.'",
        "notification": {
            "sound": "default",
            "body": "'.$message.'",
            "content_available": true,
            "title": "'.$title.'" ,
            "imageUrl": "https://reparteat.com/template/images/logo_green.png"
            }
    }';

    $headers = array (
            'Authorization: key=' . "AAAAvCDXxG4:APA91bERaoHDyaqfE83CVLshesHp5ZiDMNpZ5EX0_1QhNlEb_Rso-1YgoKVI--QPNTwEskGoURrAYJIfzP0fVjhGNtmIbhs0LTvwOiaPxD217L2sebZlPzBgrV-dvFRe40v84cVdGzw7",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    //echo $result;
    curl_close ( $ch );
}
?>