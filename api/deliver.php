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
require_once ("../perfil/includes/functions.php");
require_once ("../includes/checkSession.php");
require_once "../includes/class/class.System.php";
require_once ("../includes/class/TelegramBot/class.TelegramBot.php");
require_once ("../lib/FileAccess/class.FileAccess.php");
require_once("../includes/class/class.phpmailer.php");
require_once("../includes/class/class.smtp.php");
require "../includes/class/Zone/class.Zone.php";
require "../includes/class/Supplier/class.Supplier.php";
//require "../includes/class/Supplier/class.CategorySup.php";
//require "../includes/class/Supplier/class.TimeControl.php";
require "../includes/class/Product/class.CategoryPro.php";
require "../includes/class/Product/class.Product.php";
require "../includes/class/UserWeb/class.UserWeb.php";
require "../includes/class/Address/class.Address.php";
require "../includes/class/Order/class.Order.php";

require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use Zone;
use Supplier;
use api;
use Product;
use CategoryPro;
use UserWeb;
use Address;
use Order;

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        if(isset($_GET["supplier"]))
        {
            $supObj = new Supplier();
            $userRep = $supObj->infoSupplierUserPosition($_GET["supplier"], 'repartidor');
            $userObj = new UserWeb();
            $result = array();
            foreach($userRep as $rep)
            {
                $repUser = $userObj->infoUserWebById($rep->IDUSER);
                $result[] = $repUser;
            }
            http_response_code(200);
            echo json_encode($result);
        }
    } else {
        api::sendError("Sin acceso");
    }
}

//POST METHOD: order modificar repartidor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $now = new DateTime();
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        $result=0;
        $msg="";
        $newRep = intval($postdata->newRep);
		$ref = intval($postdata->ref);
		$userObj = new UserWeb();
		$ordObj = new Order();
		$order = $ordObj->infoOrderByRef($ref);
		if($order) {
			if($newRep != $order->IDREPARTIDOR) {
				if($user->IDTYPE == 5) {
					if($ordObj->updateRep($order->ID,$newRep)) {
						$usersSendBD=array();
						$usersSendBD[] = $userObj->infoUserWebById($newRep);
						$cont = 0;
						$userSend = array();
						for($i=0;$i<count($usersSendBD);$i++) {
							$botObj = new TelegramBot(TELEGRAMTOKEN, $usersSendBD[$i]->IDTELEGRAM);
							if($botObj->chatid !== false){
								$text ="Nuevo pedido en RepartEat. Restaurante: ".$supplierCart->TITLE.".";
								$text .= "\nREF: " . $order->REF.".";
								$urlBoton = DOMAINZP.'?view=order&mod=order&tpl=delivery&rep='.$usersSendBD[$i]->ID.'&filter=to-deliver';
								$boton = '[{"text":"Ir a pedidos pendientes","url":"'.urlencode($urlBoton).'"}]';
								
								$botObj->sendMessage($text,$boton);
								//$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
								//file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
							}else {
								$content = "\n\nERROR (idChat Repartidor incorrecta o nula):" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
								file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
							}
							
							$userSend[$cont]["name"] = $usersSendBD[$i]->NAME . " " . $usersSendBD[$i]->SURNAME;
							$userSend[$cont]["mail"] = $usersSendBD[$i]->LOGIN;
							$cont++;
						}
						
						$msg = "El pedido con referencia <strong>".$ref."</strong> ha sido asignado a " . $usersSendBD[0]->NAME . " " . $usersSendBD[0]->SURNAME;	
					}else{
						$result = 1;
						$msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, si el problema persiste consulte con el administrador";	
					}
				
				} else {
					$result = 1;
					$msg = "No tiene permisos para realizar esta acción";	
				}		
			}else {
				$result = 1;
				$msg = "El pedido ya se encuentra asignado a este repartidor.";	
			}
		}else{
			$result = 1;
			$msg = "No se encuentra ningún pedido con referencia: ".$ref;
		}
        $ar_result["result"] = $result;
        $ar_result["msg"] = $msg;
        http_response_code(200);
        echo json_encode($ar_result);
    } else {
        api::sendError("Sin acceso");
    }
}

?>