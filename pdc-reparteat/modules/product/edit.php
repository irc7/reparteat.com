<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once("../../includes/classes/Product/class.Product.php");
	require_once("../../includes/classes/Product/class.Category.php");
	require_once("../../includes/classes/Product/class.Component.php");
	require_once("../../includes/classes/Supplier/class.Supplier.php");
	require_once("../../includes/classes/Image/class.Image.php");
	
	$mnu = trim($_POST["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	$com = trim($_POST["com"]);
	$tpl = trim($_POST["tpl"]);
	$opt = trim($_POST["opt"]);
	if ($_POST) {
		$msg = "";
		$error = NULL;
		
		$id = intval($_POST["id"]);
		
		$product = new Product();
		
		$proBD = $product->infoProductById($id);
		
		
		$Date_start_hh = abs(intval($_POST["Date_start_hh"]));
		if ($Date_start_hh == NULL) {
			$Date_start_hh = "00";
		} elseif(strlen($Date_start_hh) == 1) {
			$Date_start_hh = '0' . $Date_start_hh;
		} elseif($Date_start_hh > 23) {
			$Date_start_hh = 23;
			$msg .= "El número de horas no puede ser mayor a 23.<br/>";
		}
		
		$Date_start_ii = abs(intval($_POST["Date_start_ii"]));
		if ($Date_start_ii == NULL) {
			$Date_start_ii = "00";
		} elseif(strlen($Date_start_ii) == 1) {
			$Date_start_ii = '0' . $Date_start_ii;
		} elseif($Date_start_ii > 59) {
			$Date_start_ii = 59;
			$msg .= "El número de minutos no puede ser mayor a 59.<br/>";
		}
		
		$DateAux = $_POST["date_day"]." ".$Date_start_hh.":".$Date_start_ii.":00";
		$product->dateStart = new DateTime($DateAux);
		
		$product->status = intval($_POST["status"]);
		$product->title = trim($_POST["Title"]);
		$product->idsupplier = intval($_POST["Supplier"]);
		$product->cost = floatval($_POST["Cost"]);
		
		$product->categories = $_POST["Category"];
		$product->updateCategories($id);
		
		$product->sumary = mysqli_real_escape_string($connectBD, trim($_POST["Sumary"]));
		$product->text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		
		$comsBD = $product->productComponents($id);
		$comsUp = array();
		$cont = 0;
		foreach($comsBD as $comp) {
			if(isset($_POST["IdCom-id-".$comp->ID]) && isset($_POST["TypeCom-id-".$comp->ID])) {
				$comsUp[$cont]["id"] = $_POST["IdCom-id-".$comp->ID];
				$comsUp[$cont]["type"] = $_POST["TypeCom-id-".$comp->ID];
				$comsUp[$cont]["cost"] = floatval($_POST["CostCom-id-".$comp->ID]);
				$cont++;
			}
		}
		
		for($i=1;$i<=20;$i++) {
			if(isset($_POST["IdCom-".$i]) && isset($_POST["TypeCom-".$i]) && intval($_POST["IdCom-".$i]) > 0 && trim($_POST["TypeCom-".$i]) != "") {
				$comsUp[$cont]["id"] = $_POST["IdCom-".$i];
				$comsUp[$cont]["type"] = $_POST["TypeCom-".$i];
				$comsUp[$cont]["cost"] = floatval($_POST["CostCom-".$i]);
				$cont++;
			}
		}
		$product->updateComponents($id, $comsUp);
		
		$imgs = new Image();
		$imgs->postName = "Image";
		$imgs->path = "product";
		$imgs->pathoriginal = "original";
		$imgs->pathresize = "image";
		$imgs->paththumb = "thumb";
		$imgs->files = $_FILES;
		$imgs->sizes = array(
					'0' => array ('width' => 900,
									'height' => 500
					),
					'1' => array ('width' => 500,
									'height' => 400
					),
					'2' => array ('width' => 400,
									'height' => 400
					));
		
		$imagesBD = $product->productImages($id);
		
		$fav = intval($_POST["fav"]);
		foreach($imagesBD as $imgBD) {
			$titlePost = "title-img-".$imgBD->ID;
			$actPost = "act-img-".$imgBD->ID;
			$posPost = "position-".$imgBD->ID;
			
			$actImage = intval($_POST[$actPost]);
			
			if($actImage == 1) {
				if($imgBD->URL != "") {
					$url = $imgs->dirbase.$imgs->path."/".$imgs->pathoriginal."/".$imgBD->URL;
					deleteFile($url);
					$url = $imgs->dirbase.$imgs->path."/".$imgs->pathresize."/".$imgBD->URL;
					deleteFile($url);
					for($i=0;$i<count($imgs->sizes);$i++) {
						$url = $imgs->dirbase.$imgs->path."/".$imgs->paththumb."/".($i+1)."-".$imgBD->URL;
						deleteFile($url);
					}
				}
				if($fav == $imgBD->ID) {
					$msg .= "Ha eliminado la imagen destacada del producto.<br/>";
				}
				$product->deleteImgBD($imgBD->ID);
			}else {
				$upImg["id"] = $imgBD->ID;
				$upImg["idassoc"] = $id;
				$upImg["title"] = trim($_POST[$titlePost]);
				$upImg["position"] = intval($_POST[$posPost]);
				if($fav == $imgBD->ID) {
					$upImg["fav"] = 1;
				}else{
					$upImg["fav"] = 0;
				}
				$product->updateImgBD($upImg);
			}
		}
		$images = $imgs->uploadMultiple();
	
		$product->addImages($id, $images);
	
		
		if($error == NULL) {

			$product->update($id);
			
			disconnectdb($connectBD);
			$msg .= "Producto <em>".$product->title."</em> actualizado correctamente";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$id."&msg=".utf8_decode($msg);
			header($location);
			
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar el producto.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar el producto.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>