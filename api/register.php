<?php
setlocale(LC_TIME, "es_ES");
date_default_timezone_set("Europe/Paris");
require_once ("../pdc-reparteat/includes/database.php");
$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
require_once ("../pdc-reparteat/includes/config.inc.php");
require_once ("../includes/functions.inc.php");
require_once("../perfil/includes/functions.php");
require_once ("../includes/checkSession.php");
require_once "../includes/class/class.System.php";
require "../includes/class/UserWeb/class.UserWeb.php";
require_once("../includes/class/Address/class.Address.php");

require_once ("../includes/lib/Util/class.Util.php");
require_once ("../includes/lib/FileAccess/class.FileAccess.php");
require_once("../includes/class/class.phpmailer.php");
	require_once("../includes/class/class.smtp.php");
	
	require_once("../includes/class/personal_keys.php");
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use UserWeb;

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/*protected $id;
protected $idtype;
protected $name;
protected $surname;
protected $dni;
protected $phone;
protected $idtelegram;
protected $login;
protected $pass;
protected $status;
protected $saldo;
protected $superadmin;*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $user = new UserWeb();
    $enc = $user->infoUserWebByLogin($postdata->login);
    if($enc && $enc->STATUS != 5) {
        $error = 1;
        $msg .= "Ya existe un usuario registrado con el correo electrónico que nos ha proporcionado.<br/>";
    } else {
        $error = 0;
        $user->idtype = 4;
        $user->name = $postdata->name;
        $user->surname = $postdata->surname;
        $user->dni = $postdata->dni;
        $user->phone = $postdata->phone;
        $user->idtelegram = '';
        $user->login = $postdata->login;
        $user->pass = $postdata->pass;
        $user->status = 1;
        $user->saldo = 0;
        $user->superadmin = 0;
        $res = $user->add();
        $address = new Address();
        $address->street = trim($postdata->street);
        $address->type = "user";
        $address->idassoc = $res;
        $address->fav = 1;
        $address->idzone = intval($postdata->zone);
        if($address->street != "" || $address->idzone == 0) {
            if($enc && $enc->STATUS == 5) {
                $address->deleteByUser($enc->ID);
            }
            $address->add();
        }
        $subject = "Confirmación cuenta de correo";
        $tpl = "confirm-user";
        $text = "Se esta intentando registrar con este correo <em>".$user->login."</em> en la web <strong>reparteat.com</strong>.";
        $text .= "<div style='width:100%;height:20px;display:block;clear:both;'>&nbsp;</div>";
        $text .= "<a class='btn btn-mail' href='".DOMAINZP."confirmacion?email=".$user->login."'>Confirmar email</a>";
        
        $msg .= "Se le ha enviado un correo electrónico de confirmación. Mire su bozón de correo para activar su cuenta.";
        $msg .= sendMailAlertZP($user, $subject, $text, $tpl);
    }
    if($error==0)
    {
        http_response_code(200);
        echo json_encode(array("message" => $res));
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => $msg));
    }
}
?>