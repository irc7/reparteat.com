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
//require_once ("../includes/functions.inc.php");
require_once ("../pdc-reparteat/includes/functions/global.functions.php");
require_once ("../includes/checkSession.php");
require_once "../includes/class/class.System.php";
require "../includes/class/Zone/class.Zone.php";
require "../includes/class/Supplier/class.Supplier.php";
//require "../includes/class/Supplier/class.CategorySup.php";
//require "../includes/class/Supplier/class.TimeControl.php";
require "../includes/class/Product/class.CategoryPro.php";
require "../includes/class/Product/class.Product.php";
require_once ("../includes/class/Image/class.Image.php");
require "../includes/class/UserWeb/class.UserWeb.php";
require "../includes/class/Order/class.Order.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
/*use Zone;
use Supplier;
use api;
use Product;
use CategoryPro;*/

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        if(isset($_GET["id"]))
        {
            $userObj = new UserWeb();
            $userBD = $userObj->infoUserWebById($_GET['id']);
            http_response_code(200);
            echo json_encode($userBD);
        }

    } else {
        api::sendError("Sin acceso");
    }
}

//POST METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $userObj = new UserWeb();
        $id = $token->data->id;
        $userObj->id = $id;
        $userBD = $userObj->infoUserWebById($id);
        $changeLog = false;
        $changePass = false;
        if(isset($postdata->email) && (isset($postdata->pass) && !empty($postdata->pass) && isset($postdata->passRepeat)))
        {
            $Email = trim($postdata->email);
            if ($Email != $userBD->LOGIN) {
                $enc = $userObj->infoUserWebByLogin($Email);
                $userObj->login = $Email;
                if($enc!= false) {
                    $error = 1;
                    $msg .= "El e-mail ya está siendo utilizado por otro usuario.";
                }else {
                    $changeLog = true;
                }
            }
            $userObj->pass = trim($postdata->pass);
            $PassRepeat = trim($postdata->passRepeat);
            if(strlen($userObj->pass) >= 8){		
                if($userObj->pass != $PassRepeat) {
                    $error = 1;
                    $msg .= "Los campos de contraseñas no coinciden.";
                }else {
                    $changePass = true;
                }
            }else{
                $error = 1;
                $msg .= "La contraseña debe tener al menos 8 caracteres.";
            }
        }
        if($error == 0) {
            $userObj->status = $userBD->STATUS;
            $name = trim($postdata->name);
            $userObj->name = $userObj->resetStringName($name);
            $surname = trim($postdata->surname);
            $userObj->surname = $userObj->resetStringName($surname);
            $userObj->dni = converMayusc(trim($postdata->dni));
            if($Dni != "" && !$userObj->validateDNI()) {
                $Dni = "";
                $msg .= "El DNI introducido no tiene un formato correcto.";
            }
            $userObj->phone = mysqli_real_escape_string($connectBD, trim($postdata->phone));
            if(isset($postdata->idtelegram)) {
                $userObj->idtelegram = mysqli_real_escape_string($connectBD, trim($postdata->idtelegram));
            }else{
                $userObj->idtelegram = "";
            }
            
            $userObj->superadmin = $userBD->SUPERADMIN;
            
            
            if($changeLog) {
                $userObj->updateLog($id);
            }
            if($changePass) {
                $userObj->updatePass($id);
            }
            
            $userObj->update($id);
            
            $result->ID = $userObj->id;
            $result->NAME = $userObj->name;
            $result->SURNAME = $userObj->surname;
            $result->DNI = $userObj->dni;
            $result->SUPERADMIN = $userObj->superadmin;
            $result->PHONE = $userObj->phone;
            $result->IDTELEGRAM = $userObj->idtelegram;
            $result->STATUS = $userObj->status;
            
            $msg.= "Datos actualizados correctamente";
        } else {
            $error = 1;
            $msg.= "Error al actualizar los datos";
        }
        $result->error = $error;
        $result->msg = $msg;
        http_response_code(200);
        echo json_encode($result);
    } else {
        api::sendError("Sin acceso");
    }
}

//PUT METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $orderObj = new Order();
	    $userObj = new UserWeb();
	    $zObj = new Zone();
        $Suppliers = $zObj->listSupplierZone($postdata->idZone);
        //$orders = $orderObj->infoOrderByFilterRango($postdata->filterstringStart, $postdata->filterstringFinish, "day", $Suppliers);
        if($postdata->idZone != 0){
            $orders = $orderObj->infoOrderZoneByFilterRango($postdata->filterstringStart, $postdata->filterstringFinish, "day", $Suppliers, $postdata->idZone);
        }else{
            $orders = $orderObj->infoOrderByFilterRango($postdata->filterstringStart, $postdata->filterstringFinish, "day", $Suppliers);
        }	
        
        if(count($orders) > 0) {
			$cont = 0;
			$ind = 0;
			$rows = array();
			for($i=0;$i<count($orders);$i++) {
				$product = array();
				$tSubtotal = 0;
				$tShipping = 0;
				$tCost = 0;
				if(is_iterable($orders[$i]["order"])) {
					for($j=0;$j<count($orders[$i]["order"]);$j++) {
						$tSubtotal = $tSubtotal + $orders[$i]["order"][$j]["data"]->SUBTOTAL;
						$tShipping = $tShipping + $orders[$i]["order"][$j]["data"]->SHIPPING;
						$tCost = $tCost + $orders[$i]["order"][$j]["data"]->COST;
						if(is_iterable($orders[$i]["order"][$j]["product"])) {
							for($z=0;$z<count($orders[$i]["order"][$j]["product"]);$z++) {
								$item = $orders[$i]["order"][$j]["product"][$z];
								$enc = false;
								for($x=0;$x<count($product);$x++) {
									if($product[$x]["id"] == $item["id"]) {
										$enc = true;
									break;
									}
								}
								if($enc) {
									$product[$x]["uds"] = $product[$x]["uds"] + $item["uds"];
									$product[$x]["cost"] = $product[$x]["cost"] + $item["cost"];
								}else {
									$product[] = $item;
								}
							}
						}
					}
				}
				$rows[$ind]['name'] = $orders[$i]["data"]->TITLE;
				$rows[$ind]['tSubtotal'] = $tSubtotal;
				$rows[$ind]['tShipping'] = $tShipping;
				$rows[$ind]['tCost'] = $tCost;
				$rows[$ind]['tOrder'] = count($orders[$i]["order"]);
				$rows[$ind]['product'] = $product;
				$ind++;
            }
            $totalDayOrder = 0;
            $totalDaySubtotal = 0;
            $totalDayShipping = 0;
            $totalDay = 0;
            foreach($rows as $item) { 
                $totalDayOrder += $item['tOrder'];
                $totalDaySubtotal += $item['tSubtotal'];
                $totalDayShipping += $item['tShipping'];
                $totalDay += $item['tCost'];
            }
            $result=array("data" => $rows, "totalDayOrder" => $totalDayOrder, "totalDaySubtotal" => $totalDaySubtotal, "totalDayShipping" => $totalDayShipping, "totalDay" => $totalDay);
            http_response_code(200);
            echo json_encode($result);
        }
        
    } else {
        api::sendError("Sin acceso");
    }
}

//DELETE METHOD: profile
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        global $connectBD;
        $id = $token->data->id;
        $qs = "UPDATE `".preBD."user_web` 
                SET 
            `STATUS`='0' WHERE ID = " . $id;
        checkingQuery($connectBD, $qs);
        http_response_code(200);
        echo json_encode(array("id" => $idNew));
    } else {
        api::sendError("Sin acceso");
    }
}

function converMayusc($texto){
    $find = array('á','é','í','ó','ú','ñ');
    $replac = array('Á','É','Í','Ó','Ú','Ñ');
    $text = str_replace ($find, $replac, $texto); 
    $text = strtoupper($text);
    return ($text);
}

?>