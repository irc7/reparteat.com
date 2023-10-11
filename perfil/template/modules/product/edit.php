<?php	
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	require_once("../../../../includes/class/class.System.php");
	
	require_once("../../../../includes/class/Product/class.Product.php");
	require_once("../../../../includes/class/Product/class.CategoryPro.php");
	require_once("../../../../includes/class/Product/class.Component.php");
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Image/class.Image.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	$msg = "";
	$error = 0;
	
	$idSup = intval($_POST["idSup"]);
	
	$supplier = new Supplier();
	if($supplier->isUserWebSupplier($idSup, $_SESSION[nameSessionZP])) {
		if($_POST) {
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
			
			$DateAux = $_POST["date_start"]." ".$Date_start_hh.":".$Date_start_ii.":00";
			$product->dateStart = new DateTime($DateAux);

			$product->status = intval($_POST["status"]);
			$product->title = trim($_POST["Title"]);
			$product->idsupplier = $idSup;
			$product->cost = floatval($_POST["Cost"]);
			
			$product->categories = $_POST["Category"];
			
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
				
				
				$msg .= "Producto <em>".$product->title."</em> actualizado correctamente";
				
			} else {
				$error = 1;
				$msg .= "Error al registrar el producto.";
			}
		}else{
			$error = 1;
			$msg.= NOPOST;
		}
	}else{
		$error = 1;
		$msg.= NOACCESS;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	
	disconnectdb($connectBD);
	$location = "Location: " . DOMAINZP . "?view=product&mod=product&tpl=edit&sup=".$idSup."&id=".$id;
	header($location);

?>