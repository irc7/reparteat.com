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
require_once ("../includes/class/class.System.php");
require_once ("../includes/class/Address/class.Address.php");
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

        $addressObj = new Address();
        $addressObj->idassoc = $user->ID;
        $addressObj->street = trim($postdata->street);
        $addressObj->idzone = trim($postdata->zone);
        $addressObj->type = "user";
        $addressObj->fav = 0;
        $addressObj->add();

        http_response_code(200);
        echo json_encode(array("code"=>1, "msg" => "Dirección añadida correctamente"));
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
?>