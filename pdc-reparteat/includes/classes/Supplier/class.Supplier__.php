<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Proveedor
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
	require_once("../../includes/classes/Image/class.Image.php");
}else {
	require_once "includes/classes/class.System.php";
	require_once("includes/classes/Image/class.Image.php");
}

class Supplier extends System {
	
	protected $id;
	protected $title;
	protected $text;
	protected $eslogan;
	protected $logo;
	protected $image;
	protected $phone;
	protected $movil;
	protected $cost;
	protected $minimo;
	protected $time;
	protected $idtelegram;
	protected $status;
	protected $categories;
	protected $idproveedor;
	protected $idsrepartidor;
	protected $view_img;
	
	
	public function __construct() {
	}
	
	
	public function add(){
		
		global $connectBD;
		
		
		
		$q = "INSERT INTO `".preBD."suppliers`(`TITLE`, `TEXT`, `ESLOGAN`, `LOGO`, `IMAGE`, `PHONE`, `MOVIL`, `COST`, `MIN`, `TIME`,  `IDTELEGRAM`, `STATUS`, `VIEW_IMG`)
				VALUES 
			('".$this->title."', '".$this->text."', '".$this->eslogan."', '".$this->logo."', '".$this->image."', '".$this->phone."', '".$this->movil."', '".number_format($this->cost,2,'.','')."', '".number_format($this->minimo,2,'.','')."', '".$this->time."', '".$this->idtelegram."', '".$this->status."', '".$this->view_img."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			//asociaciones
			$this->updateCategories($idNew);
			$this->addUser($idNew);
			
			$slug = formatNameUrl($this->title);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $idNew . " and TYPE = 'supplier'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			
			$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
						VALUES ('".$slug."','supplier',0,'".$idNew."','supplier','".$this->title."')";
			checkingQuery($connectBD, $q);
			
			return $idNew;					
		}
	}
	public function update($id){
		
		global $connectBD;
		$this->id = $id;
		
		$q = "UPDATE `".preBD."suppliers` SET 
				`TITLE`='".$this->title."',
				`TEXT`='".$this->text."',
				`ESLOGAN`='".$this->eslogan."', 
				`LOGO`='".$this->logo."', 
				`IMAGE`='".$this->image."',
				`PHONE`='".$this->phone."',
				`MOVIL`='".$this->movil."',
				`COST`='".$this->cost."',
				`MIN`='".$this->minimo."',
				`TIME`='".$this->time."',
				`IDTELEGRAM`='".$this->idtelegram."',
				`STATUS`='".$this->status."',
				`VIEW_IMG`='".$this->view_img."'
			WHERE ID = " . $id;
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			//asociaciones
			$this->updateCategories($id);
			$this->updateUser($id);
			
			$slug = formatNameUrl($this->title);
			
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id . " and TYPE = 'supplier'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			$q = "select * from `".preBD."url_web` WHERE ID_VIEW = '" . $id . "' and TYPE = 'supplier'";
			$r= checkingQuery($connectBD, $q);
			if($row = mysqli_fetch_object($r)) {
				$q = "UPDATE `".preBD."url_web` SET 
					`SLUG`='".$slug."',
					`TITLE`='".mysqli_real_escape_string($connectBD,$this->title)."' 
					WHERE ID_VIEW = '" . $id . "' and TYPE = 'supplier'";
			}else{
				$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
						VALUES ('".$slug."','supplier',0,'".$id."','supplier','".$this->title."')";
			}
			checkingQuery($connectBD, $q);
			
			return $id;					
		}
	}
	public function updateCategories($id = null) {
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."supplier_cat_assoc` WHERE IDSUPPLIER = " . $id;
		checkingQuery($connectBD, $q);
		
		for($i=0;$i<count($this->categories);$i++) {
			$q = "INSERT INTO `".preBD."supplier_cat_assoc`(`IDCAT`, `IDSUPPLIER`) 
					VALUES 
					(".$this->categories[$i].",".$id.")";
			checkingQuery($connectBD, $q);			
		}
	}
	public function addUser($id = null) {
		global $connectBD;
		
		$q = "INSERT INTO `".preBD."user_web_supplier_assoc`(`IDUSER`, `IDSUPPLIER`, `TYPE`) 
		VALUES 
		(".$this->idproveedor.",".$id.", 'proveedor')";
		checkingQuery($connectBD, $q);	
		
		
	}
	public function addUserRep($idSup, $repArray, $idZone) {
		global $connectBD;

		$qR = "INSERT INTO `".preBD."user_web_supplier_assoc`(`IDUSER`, `IDSUPPLIER`, `TYPE`, `POSITION`, `IDZONE`) 
		VALUES ";
		for($i=0;$i<count($repArray);$i++) {
			if($i>0) {
				$qR .= ", ";	
			}
			$qR .= "(".$repArray[$i]["id"].",".$idSup.", 'repartidor', ".$repArray[$i]["p"].", ".$idZone.")";
		}
		
		checkingQuery($connectBD, $qR);
	}
	public function updateUser($id = null) {
		global $connectBD;
		$q = "UPDATE `".preBD."user_web_supplier_assoc` SET 
				`IDUSER`=".$this->idproveedor."
				WHERE IDSUPPLIER = ".$id." and TYPE = 'proveedor'";
		checkingQuery($connectBD, $q);			
		
	}
	public function updateUserRep($id = null, $repArray, $idZone) {
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."user_web_supplier_assoc` WHERE IDSUPPLIER = ".$id." and TYPE = 'repartidor' and IDZONE = '".$idZone."'";
		checkingQuery($connectBD, $q);
		if(count($repArray) > 0) {
			$qR = "INSERT INTO `".preBD."user_web_supplier_assoc`(`IDUSER`, `IDSUPPLIER`, `TYPE`, `POSITION`, `IDZONE`) 
				VALUES ";
			for($i=0;$i<count($repArray);$i++) {
				if($i>0) {
					$qR .= ", ";	
				}
				$qR .= "(".$repArray[$i]["id"].",".$id.", 'repartidor', ".$repArray[$i]["p"].", ".$idZone.")";
			}
			checkingQuery($connectBD, $qR);

		}
	}
	public function infoSupplierById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."suppliers where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function listSupplier() {
		global $connectBD;
		
		$q = "select * from ".preBD."suppliers where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data->IDCAT;
		}
		return $sup;
	}
	public function allSupplier() {
		global $connectBD;
		
		$q = "select * from ".preBD."suppliers where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	
	public function supplierAddress($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_sup_web_address.*
				from ".preBD."user_sup_web_address 
				where ".preBD."user_sup_web_address.IDASSOC = " . $id. " and TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function supplierAddressZone($id = null, $zone = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_sup_web_address.*
				from ".preBD."user_sup_web_address 
				where ".preBD."user_sup_web_address.IDASSOC = " . $id. " 
				and ".preBD."user_sup_web_address.IDZONE = " . $zone. " and TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function allSupplierAddress($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_sup_web_address.*
				from ".preBD."user_sup_web_address 
				where ".preBD."user_sup_web_address.IDASSOC = " . $id. " and TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		$data= array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		}
		return $data;
	}
	public function supplierTimeControl($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."supplier_horario.*
				from ".preBD."supplier_horario 
				where ".preBD."supplier_horario.IDASSOC = " . $id. " and TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		$time = array();
		while($data = mysqli_fetch_object($r)) {
			$time[] = $data;
		}
		return $time;
	}
	
	public function supplierTimeControlZone($id = null, $zone = null) {
		global $connectBD;
		
		$q = "select ".preBD."supplier_horario.*
				from ".preBD."supplier_horario 
				where ".preBD."supplier_horario.IDASSOC = " . $id. " 
				and ".preBD."supplier_horario.IDZONE = " . $zone. " 
				and TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		$time = array();
		while($data = mysqli_fetch_object($r)) {
			$time[] = $data;
		}
		return $time;
	}
	
	public function infoSupplierUser($id = null, $type = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web_supplier_assoc.IDUSER
				from ".preBD."user_web_supplier_assoc 
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " and TYPE = '".$type."'";
		$r = checkingQuery($connectBD, $q);
		
		$users = array();
		while($data = mysqli_fetch_object($r)) {
			$users[] = $data->IDUSER;
		}
		return $users;
	}
	public function infoSupplierUserPosition($id = null, $type = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web_supplier_assoc.IDUSER, ".preBD."user_web_supplier_assoc.POSITION
				from ".preBD."user_web_supplier_assoc 
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " and TYPE = '".$type."'";
		$r = checkingQuery($connectBD, $q);
		
		$users = array();
		while($data = mysqli_fetch_object($r)) {
			$users[] = $data;
		}
		return $users;
	}
	public function infoSupplierUserPositionZone($id = null, $idzone = null, $type = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web_supplier_assoc.IDUSER, ".preBD."user_web_supplier_assoc.POSITION
				from ".preBD."user_web_supplier_assoc 
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " 
				and ".preBD."user_web_supplier_assoc.IDZONE = " . $idzone. " 
				and ".preBD."user_web_supplier_assoc.TYPE = '".$type."'";
		$r = checkingQuery($connectBD, $q);
		
		$users = array();
		while($data = mysqli_fetch_object($r)) {
			$users[] = $data;
		}
		return $users;
	}
	public function infoCategories($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."supplier_cat_assoc.IDCAT
				from ".preBD."supplier_cat_assoc 
				where ".preBD."supplier_cat_assoc.IDSUPPLIER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($data = mysqli_fetch_object($r)) {
			$cats[] = $data->IDCAT;
		}
		return $cats;
	}
	public function totalInfoSupplierUser($id = null, $type = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web.NAME, ".preBD."user_web.SURNAME
				from ".preBD."user_web_supplier_assoc 
				inner join ".preBD."user_web on ".preBD."user_web_supplier_assoc.IDUSER = ".preBD."user_web.ID
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " and TYPE = '".$type."'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function getSupplierProduct($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products.*
				from ".preBD."products 
				where ".preBD."products.IDSUPPLIER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		return $total;
		
	}
	public function upStatus($id, $status){
		global $connectBD;
		$q = "UPDATE ".preBD."suppliers SET 
				STATUS = ". $status ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	
	public function upViewImg($id, $v){
		global $connectBD;
		$q = "UPDATE ".preBD."suppliers SET 
				VIEW_IMG = ". $v ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	
	public function deleteZoneDelivery($id=null,$zone=null){
		global $connectBD;
		//Horarios
		$q = "delete from ".preBD."supplier_horario 
				where IDASSOC = " . $id. " 
				and IDZONE = " . $zone. " 
				and TYPE = 'proveedor'";
		$res1 = checkingQuery($connectBD, $q);
		//Dirección
		$q = "delete from ".preBD."user_sup_web_address 
				where IDASSOC = " . $id. " 
				and IDZONE = " . $zone. " 
				and TYPE = 'proveedor'";
		$res2 = checkingQuery($connectBD, $q);
		
		$q = "delete from `".preBD."user_web_supplier_assoc` 
				where IDSUPPLIER = ".$id." 
				and TYPE = 'repartidor' 
				and IDZONE = '".$zone."'";
		$res3 = checkingQuery($connectBD, $q);
		
		if($res1 && $res2 && $res3){
			return true;
		}
	}
	public function delete($supBD){
		global $connectBD;
		
		//borro logo
		$imgLogo = new Image();
		$imgLogo->sizes = array(
					'0' => array ('width' => 400,
									'height' => 400
					));
		$imgLogo->path = "supplier";
		$imgLogo->pathoriginal = "original";
		$imgLogo->pathresize = "logo";
		$imgLogo->paththumb = "thumb";
		if($supBD->LOGO != "") {
			$url = $imgLogo->dirbase.$imgLogo->path."/".$imgLogo->pathoriginal."/".$supBD->LOGO;
			deleteFile($url);
			$url = $imgLogo->dirbase.$imgLogo->path."/".$imgLogo->pathresize."/".$supBD->LOGO;
			deleteFile($url);
			for($i=0;$i<count($imgLogo->sizes);$i++) {
				$url = $imgLogo->dirbase.$imgLogo->path."/".$imgLogo->paththumb."/".($i+1)."-".$supBD->LOGO;
				deleteFile($url);
			}
		}
		//borro imagen
		$imgs = new Image();
		$imgs->path = "supplier";
		$imgs->pathoriginal = "original";
		$imgs->pathresize = "image";
		$imgs->paththumb = "thumb";
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
		if($supBD->IMAGE != "") {
			$url = $imgs->dirbase.$imgs->path."/".$imgs->pathoriginal."/".$supBD->IMAGE;
			deleteFile($url);
			$url = $imgs->dirbase.$imgs->path."/".$imgs->pathresize."/".$supBD->IMAGE;
			deleteFile($url);
			for($i=0;$i<count($imgs->sizes);$i++) {
				$url = $imgs->dirbase.$imgs->path."/".$imgs->paththumb."/".($i+1)."-".$supBD->IMAGE;
				deleteFile($url);
			}
		}
		
		//Horarios
		$q = "delete from ".preBD."supplier_horario where IDASSOC = " . $supBD->ID. " and TYPE = 'proveedor'";
		$res = checkingQuery($connectBD, $q);
		//Dirección
		$q = "delete from ".preBD."user_sup_web_address where IDASSOC = " . $supBD->ID. " and TYPE = 'proveedor'";
		$res = checkingQuery($connectBD, $q);
		//Asociaciones a categorias
		$q = "delete from ".preBD."supplier_cat_assoc where IDSUPPLIER = " . $supBD->ID;
		$res = checkingQuery($connectBD, $q);
		
		//Borro el producto
		$q = "delete from ".preBD."suppliers where ID = " . $supBD->ID;
		$res = checkingQuery($connectBD, $q);
		
		return $res;		
	}
	
}