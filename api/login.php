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
    //var_dump($postdata);
    $user = UserWeb::checkUserWeb($postdata->login, $postdata->password);
    if($user)
    {
        $token = api::generateToken($user);
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "Successful login.",
                "token" => $token,
                "email" => $user->LOGIN,
                "datetime" => date("Y-m-d H:i:s")
            ));
    }
    else{
        api::sendError("Credenciales incorrectas");
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
?>