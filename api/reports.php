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
require "../includes/class/Report/class.Report.php";

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
use Report;

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
		$userObj = new UserWeb();
		$user = UserWeb::infoUserWebByLogin($token->data->email);
		$reportObj = new Report();
		$zObj = new Zone();
		$orderObj = new Order();
        if (isset($_GET['filter'])) {
			$filter = trim($_GET['filter']);
			$dateStartDay = new DateTime($filter." ".REPDAYS);
			$dateFinishDay = new DateTime($filter." ".REPDAYF);
			$dateStartNight = new DateTime($filter." ".REPNIGHTS);
			$dateFinishNight = new DateTime($filter." ".REPNIGHTF);
			
		}else {
			$filter = $now->format("Y-m-d");
			$dateStartDay = new DateTime($now->format("Y-m-d")." ".REPDAYS);
			$dateFinishDay = new DateTime($now->format("Y-m-d")." ".REPDAYF);
			$dateStartNight = new DateTime($now->format("Y-m-d")." ".REPNIGHTS);
			$dateFinishNight = new DateTime($now->format("Y-m-d")." ".REPNIGHTF);
		}
		$filterstring = $filter; 
		$dateCheck = new DateTime($filter. " 00:00:00");
		$orders = array();
		$reports = array();
		if($user->IDTYPE == 3) {
			$ind = 0;
			$reportBD = $reportObj->checkingReportUser($filter, $user->ID);
			$orders = array();
			$ordersDay = array();
			$ordersNight = array();
			$msgError = NULL;
			$ordersDay = $orderObj->infoOrderByRepThreeDay($dateStartDay, $dateFinishDay, $user->ID);
			$ordersNight = $orderObj->infoOrderByRepThreeDay($dateStartNight, $dateFinishNight, $user->ID);
			$orders = array_merge($ordersDay, $ordersNight);
			$totals = array();
			$totals = $orderObj->calculateTotalOrders($orders);
			if($reportBD) {
				$reports[0]['id'] = $reportBD->ID;
				$reports[0]['idRep'] = $reportBD->IDREP;
				$reports[0]['name'] = $reportBD->NAME;
				$reports[0]['date'] = new DateTime($reportBD->DATECREATE);
				if(count($ordersDay)>0){
					$reports[0]['day'] = 1;
					$reports[0]['orderDay'] = count($ordersDay);
				}else {
					$reports[0]['day'] = 0;
					$reports[0]['orderDay'] = 0;
				}
				if(count($ordersNight)>0){ 
					$reports[0]['night'] = 1;
					$reports[0]['orderNight'] = count($ordersNight);
				}else {
					$reports[0]['night'] = 0;
					$reports[0]['orderNight'] = 0;
				}
				$reports[0]['salaryDay'] = $reportBD->SALARYDAY;
				$reports[0]['salaryNight'] = $reportBD->SALARYNIGHT;
				$reports[0]['payCash'] = $totals['cash'];
				$reports[0]['payTPV'] = $totals['tpv'];
				$reports[0]['cost'] = $reportBD->COST;
				$reports[0]['text'] = $reportBD->TEXT;
				$reports[0]['total'] = $totals['total'] - $reportBD->COST - $reportBD->SALARYDAY - $reportBD->SALARYNIGHT;
				$reports[0]['type'] = "bd";
			}else {
				$reports[0]['id'] = 0;
				$reports[0]['idRep'] = $user->ID;
				$reports[0]['name'] = $user->NAME ." ". $user->SURNAME;
				$reports[0]['date'] = $dateCheck;
				if(count($ordersDay)>0){
					$reports[0]['day'] = 1;
					$reports[0]['orderDay'] = count($ordersDay);
				}else {
					$reports[0]['day'] = 0;
					$reports[0]['orderDay'] = 0;
				}
				if(count($ordersNight)>0){ 
					$reports[0]['night'] = 1;
					$reports[0]['orderNight'] = count($ordersNight);
				}else {
					$reports[0]['night'] = 0;
					$reports[0]['orderNight'] = 0;
				}
				$reports[0]['salaryDay'] = 0;
				$reports[0]['salaryNight'] = 0;
				$reports[0]['payCash'] = $totals['cash'];
				$reports[0]['payTPV'] = $totals['tpv'];
				$reports[0]['cost'] = 0;
				$reports[0]['text'] = "";
				$reports[0]['total'] = $totals['total'] - $reports[0]['cost'] - $reports[0]['salaryDay'] - $reports[0]['salaryNight'];
				$reports[0]['type'] = "new";
			}
			//pre($reports);
			
		}else if($user->IDTYPE == 5 || $user->IDTYPE == 1) {
			if(isset($_GET["zone"])) {
				$idZone = intval($_GET["zone"]);
			}else {
				$idZone = 0;
			}
			if(($user->IDTYPE == 5 && $idZone > 0 ) || $user->IDTYPE == 1) {
				$reps = $orderObj->repartidoresDay($filter, $idZone);
				$reports = array();
				$ind = 0;
				for($i=0;$i<count($reps);$i++) {
					$userRep = $userObj->infoUserWebById($reps[$i]);
					$reportBD = $reportObj->checkingReportUser($filter, $userRep->ID);
					$orders = array();
					$ordersDay = array();
					$ordersNight = array();
					$msgError = NULL;
					$ordersDay = $orderObj->infoOrderByRepThreeDay($dateStartDay, $dateFinishDay, $userRep->ID);
					$ordersNight = $orderObj->infoOrderByRepThreeDay($dateStartNight, $dateFinishNight, $userRep->ID);
					$orders = array_merge($ordersDay, $ordersNight);
					$totals = array();
					$totals = $orderObj->calculateTotalOrders($orders);
					
					if($reportBD) {
						$reports[$i]['id'] = $reportBD->ID;
						$reports[$i]['idRep'] = $reportBD->IDREP;
						$reports[$i]['name'] = $reportBD->NAME;
						$reports[$i]['date'] = new DateTime($reportBD->DATECREATE);
						if(count($ordersDay)>0){
							//$reports[$i]['day'] = 1;
							//$reports[$i]['orderDay'] = count($ordersDay);
							$reports[$i]['day'] = 1;
							$reports[$i]['orderDay'] = count($ordersDay);
						}else {
							//$reports[$i]['day'] = $reportBD->DAY;
							//$reports[$i]['orderDay'] = $reportBD->ORDERDAY;
							$reports[$i]['day'] = 0;
							$reports[$i]['orderDay'] = 0;
						}
						if(count($ordersNight)>0){
							//$reports[$i]['night'] = 1;
							//$reports[$i]['orderNight'] = count($ordersNight);
							$reports[$i]['night'] = 1;
							$reports[$i]['orderNight'] = count($ordersNight);
						}else{
							//$reports[$i]['night'] = $reportBD->NIGHT;
							//$reports[$i]['orderNight'] = $reportBD->ORDERNIGHT;
							$reports[$i]['night'] = 0;
							$reports[$i]['orderNight'] = 0;
						}
						$reports[$i]['salaryDay'] = $reportBD->SALARYDAY;
						$reports[$i]['salaryNight'] = $reportBD->SALARYNIGHT;
						//$reports[$i]['payCash'] = $reportBD->PAYCASH;
						//$reports[$i]['payTPV'] = $reportBD->PAYTPV;
						$reports[$i]['payCash'] = $totals['cash'];
						$reports[$i]['payTPV'] = $totals['tpv'];
						$reports[$i]['cost'] = $reportBD->COST;
						$reports[$i]['text'] = $reportBD->TEXT;
						$reports[$i]['total'] = $totals['total'] - $reportBD->COST - $reportBD->SALARYDAY - $reportBD->SALARYNIGHT;
						$reports[$i]['type'] = "bd";
						
					}else {
						$reports[$i]['id'] = 0;
						$reports[$i]['idRep'] = $userRep->ID;
						$reports[$i]['name'] = $userRep->NAME ." ". $userRep->SURNAME;
						$reports[$i]['date'] = $dateCheck;
						if(count($ordersDay)>0){
							$reports[$i]['day'] = 1;
							$reports[$i]['orderDay'] = count($ordersDay);
						}else {
							$reports[$i]['day'] = 0;
							$reports[$i]['orderDay'] = 0;
						}
						if(count($ordersNight)>0){
							$reports[$i]['night'] = 1;
							$reports[$i]['orderNight'] = count($ordersNight);
						}else {
							$reports[$i]['night'] = 0;
							$reports[$i]['orderNight'] = 0;
						}
						$reports[$i]['salaryDay'] = 0;
						$reports[$i]['salaryNight'] = 0;
						$reports[$i]['payCash'] = $totals['cash'];
						$reports[$i]['payTPV'] = $totals['tpv'];
						$reports[$i]['cost'] = 0;
						$reports[$i]['text'] = "";
						$reports[$i]['total'] = $totals['total'] - $reports[$i]['cost'] - $reports[$i]['salaryDay'] - $reports[$i]['salaryNight'];
						$reports[$i]['type'] = "new";
					}
				}
			}
		}		
		http_response_code(200);
		echo json_encode($reports);
        
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
		$idZone = intval($postdata->zone);
		$supObj = new Supplier();
		$orderObj = new Order();
		$userObj = new UserWeb();
		$zObj = new Zone();
		$itemObj = new Report();
		if($user->IDTYPE == 3 || ($user->IDTYPE == 5 && $idZone > 0) || $user->IDTYPE == 1) {	
			$action = trim($postdata->action);
			$itemObj->id = intval($postdata->idReport);
			$itemObj->idRep = intval($postdata->idRep);
			$itemObj->dateCreate = new DateTime($postdata->dateCreate);
			$dateString = $itemObj->dateCreate->format('Y-m-d');
			$itemObj->name = trim($postdata->name);
			if(isset($postdata->day) && $postdata->day == 1) {
				$itemObj->day = 1;
				$itemObj->orderDay = intval($postdata->orderDay);
				$itemObj->salaryDay = floatval($postdata->salaryDay);
			}else {
				$itemObj->day = 0;
				$itemObj->orderDay = 0;
				$itemObj->salaryDay = 0;
			}
			if(isset($postdata->night) && $postdata->night == 1) {
				$itemObj->night = 1;
				$itemObj->orderNight = intval($postdata->orderNight);
				$itemObj->salaryNight = floatval($postdata->salaryNight);
			}else {
				$itemObj->night = 0;
				$itemObj->orderNight = 0;
				$itemObj->salaryNight = 0;
			}
			$itemObj->payCash = floatval($postdata->payCash);
			$itemObj->payTPV = floatval($postdata->payTPV);
			$itemObj->cost = floatval($postdata->cost);
			$itemObj->total = floatval($postdata->total);
			
			$itemObj->text = trim($postdata->text);
			 
			if($result == 0) {
				if($action == "create") {
					$id = $itemObj->add();
				}else if($action == "edit") {
					
					$itemObj->update();
				}
				$msg .= "Formulario guardado correctamente";
			} else {
				$result = 1;
				$msg .= "Error al guardar el formulario.";
			}
			
		}else{
			$result = 1;
			$msg.= "Sin acceso";
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