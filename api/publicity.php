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
//require_once "../includes/class/class.System.php";
require "../pdc-reparteat/includes/classes/Publicity/class.Publicity.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use api;
use publicity;


//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    /*$token = api::checkToken();
    if($token) {*/
        //$user = UserWeb::infoUserWebByLogin($token->data->email);
        if(!isset($_GET["hook"])) //si no trae ref es listado
        {
            

        } else { //consulta ref pedido concreto
            $hook = $_GET["hook"];
            $zone = intval($_GET["zone"]);
			$pubObj = new Publicity();
            $res = $pubObj->listFilter($hook, $zone);
            $result=array();
            foreach ($res as $row)
            {
                if($row->STATUS==1)
                {
                    $row->TEXT = html_entity_decode(str_replace("&nbsp;"," ", $row->TEXT));
                    array_push($result, $row);
                }
            }
            http_response_code(200);
            echo json_encode($result);
        }
        
    /*} else {
        api::sendError("Sin acceso");
    }*/
}

//POST METHOD: order modificar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        
        http_response_code(200);
        echo json_encode("");
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
function conversorSegundosHoras($tiempo_en_segundos) {
    $horas = floor($tiempo_en_segundos / 3600);
    $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
    $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

    return $horas . ':' . $minutos . ":" . $segundos;
}

?>