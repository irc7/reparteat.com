<?php
/*
Author: info@ismaelrc.es
Date: 2022-10-04

Proveedor
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
	require_once("../../includes/classes/Image/class.Image.php");
}else {
	require_once "includes/classes/class.System.php";
	require_once("includes/classes/Image/class.Image.php");
}

class Publicity extends System {
	
	protected $id;
	public $hook;
	public $zone;
	protected $title;
	protected $subtitle;
	protected $text;
	protected $image;
	protected $image_mobile;
	protected $link;
	protected $target;
	protected $type;
	protected $status;
	
	
	public function __construct() {
		$this->hook = array();
		$this->zone = array();
	}
	
	
	public function add(){
		
		global $connectBD;
		
		$q = "INSERT INTO `".preBD."publicity`(`TITLE`, `SUBTITLE`, `TEXT`, `IMAGE`, `IMAGE_MOBILE`, `LINK`, `TARGET`, `TYPE`, `STATUS`)
				VALUES 
			('".$this->title."', '".$this->subtitle."', '".$this->text."', '".$this->image."', '".$this->image_mobile."', '".$this->link."', '".$this->target."', '".$this->type."', '".$this->status."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			//`HOOK - ZONE`
			if(count($this->zone)>0 && count($this->hook)>0) {
				$q = "INSERT INTO `".preBD."publicity_hook_zone`(`HOOK`, `IDZONE`, `IDITEM`, `POSITION`) VALUES ";
				
				for($i=0;$i< count($this->hook);$i++) {
					for($j=0;$j< count($this->zone);$j++) {
						$position = $this->lastPositionByHook($this->hook[$i],$this->zone[$j])+1;
						$q.= "(".$this->hook[$i].",".$this->zone[$j].",".$idNew.",".$position."),";
						$cont++;
					}
				}
				
				checkingQuery($connectBD, substr($q,0,-1));
			}
			return $idNew;					
		}

	}
	public function update($id){
		
		global $connectBD;
		$this->id = $id;
		
		$q = "UPDATE `".preBD."publicity` SET 
				`TITLE`='".$this->title."',
				`SUBTITLE`='".$this->subtitle."', 
				`TEXT`='".$this->text."',
				`IMAGE`='".$this->image."',
				`IMAGE_MOBILE`='".$this->image_mobile."', 
				`LINK`='".$this->link."',
				`TARGET`='".$this->target."',
				`TYPE`='".$this->type."',
				`STATUS`='".$this->status."'
			WHERE ID = " . $id;
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idAssoc = array();
			
			for($i=0;$i< count($this->hook);$i++) {
				for($j=0;$j< count($this->zone);$j++) {
					if($this->checkingHookZone($this->hook[$i], $this->zone[$j], $id)) {
						$q = "select ID from ".preBD."publicity_hook_zone where true
							and HOOK = " . 	$this->hook[$i] . "
							and IDZONE = ".	$this->zone[$j] . "
							and IDITEM = ". $id;
						$r = checkingQuery($connectBD, $q);
						$data = mysqli_fetch_object($r);
						$idAssoc[] = $data->ID;
					}
				}
			}
			$q = "DELETE FROM `".preBD."publicity_hook_zone` WHERE true and IDITEM = " . $id; 
			for($i=0;$i< count($idAssoc);$i++) {
				$q.=" and ID != " . $idAssoc[$i];
			}
			checkingQuery($connectBD, $q);

			$this->updatePosition();

			for($i=0;$i< count($this->hook);$i++) {
				for($j=0;$j< count($this->zone);$j++) {
					if(!$this->checkingHookZone($this->hook[$i], $this->zone[$j], $id)) {
						$position = $this->lastPositionByHook($this->hook[$i],$this->zone[$j])+1;
						$q = "INSERT INTO `".preBD."publicity_hook_zone`(`HOOK`, `IDZONE`, `IDITEM`, `POSITION`) VALUES 
						(".$this->hook[$i].",".$this->zone[$j].",".$id.",".$position.")";
						checkingQuery($connectBD, $q);
					}
				}
			}
			return $id;					
		}
	}
	public function updatePosition() {
		global $connectBD;
		$hook = array();
		$q = "select ID from ".preBD."publicity_hook order by ID asc";
		$r = checkingQuery($connectBD, $q);
		while($data = mysqli_fetch_object($r)) {
			$hook[] = $data->ID;
		}
		
		$zone = array();
		$q = "select ID from ".preBD."zone order by ID asc";
		$r = checkingQuery($connectBD, $q);
		while($data = mysqli_fetch_object($r)) {
			$zone[] = $data->ID;
		}

		for($i=0;$i< count($hook);$i++) {
			for($j=0;$j< count($zone);$j++) {
				$q = "select ID from ".preBD."publicity_hook_zone where true
						and HOOK = " . 	$hook[$i] . "
						and IDZONE = ".	$zone[$j] . "
						order by POSITION asc";
				$r = checkingQuery($connectBD, $q);
				$cont = 1;
				while($row = mysqli_fetch_object($r)) {
					$q = "UPDATE `ree_publicity_hook_zone` SET `POSITION`=".$cont." WHERE ID = ". $row->ID;
					checkingQuery($connectBD, $q);
					$cont++; 
				}
			}
		}
	}
	public function infoPublicityById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."publicity where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function allPublicity() {
		global $connectBD;
		
		$q = "select * from ".preBD."publicity where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	public function listFilter($filterHook = null, $filterZone = null) {
		global $connectBD;
		$sup = array();
		$q = "select ".preBD."publicity.*";
		if(($filterZone != null && $filterZone != 0 && $filterZone != "") && ($filterHook != null && $filterHook != 0 && $filterHook != "")) {
			$q .= ",".preBD."publicity_hook_zone.POSITION";
			$positionOrder = preBD."publicity_hook_zone.POSITION asc, ";
		}
		
		$q .= " from ".preBD."publicity ";
		
		if(($filterZone != null && $filterZone != 0 && $filterZone != "") || ($filterHook != null && $filterHook != 0 && $filterHook != "")) {
			$q .= " inner join ".preBD."publicity_hook_zone on true";
		}
		
		if($filterZone != null && $filterZone != 0 && $filterZone != "") {
			$q .= " and (".preBD."publicity_hook_zone.IDZONE = " . $filterZone. " and ".preBD."publicity_hook_zone.IDITEM = ".preBD."publicity.ID)";
		}
		
		if($filterHook != null && $filterHook != 0 && $filterHook != "") {
			$q .= " and (".preBD."publicity_hook_zone.HOOK = " . $filterHook. " and ".preBD."publicity_hook_zone.IDITEM = ".preBD."publicity.ID)";
		}
		
		$q .= " where true group by ".preBD."publicity.ID";
		$q .= " order by ".$positionOrder. "ID desc";

		$r = checkingQuery($connectBD, $q);
		
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	public function lastPositionByHook($idhook = null, $idzone = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where ".preBD."publicity_hook_zone.HOOK = " . $idhook . " and ".preBD."publicity_hook_zone.IDZONE = " . $idzone;
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data->total;
		} else {
			return false;
		}
	}
	public function infoPositionById($hook,$zone, $id) {
		global $connectBD;
		$q = "select POSITION from ".preBD."publicity_hook_zone where true
						and HOOK = " . 	$hook . "
						and IDZONE = ".	$zone . "
						and IDITEM = ". $id;
		$r = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($r);

		return $data->POSITION;;
	}
	public function checkingZone($zone = null, $item = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where ".preBD."publicity_hook_zone.IDZONE = " . $zone . " and ".preBD."publicity_hook_zone.IDITEM = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
	public function checkingHook($hook = null, $item) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where ".preBD."publicity_hook_zone.HOOK = " . $hook . " and ".preBD."publicity_hook_zone.IDITEM = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
	
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
	public function checkingHookZone($hook = null, $zone = null, $item = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."publicity_hook_zone 
				where true
				and ".preBD."publicity_hook_zone.HOOK = " . $hook . " 
				and ".preBD."publicity_hook_zone.IDZONE = " . $zone . " 
				and ".preBD."publicity_hook_zone.IDITEM = " . $item;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
	
		if($data->total>0) {
			return true;
		} else {
			return false;
		}
	}
	public function upStatus($id, $status){
		global $connectBD;
		$q = "UPDATE ".preBD."publicity SET 
				STATUS = ". $status ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	
	public function delete($dataBD){
		global $connectBD;
		$q = "select * from ".preBD."publicity_hook";
		$r = checkingQuery($connectBD, $q);
		$allHook = array();
		while($row = mysqli_fetch_object($r)){
			$allHook[] = $row;
		}
		//borro imagen
		$imgs = new Image();
		$imgs->path = "publicity";
		$imgs->pathoriginal = "original";
		$imgs->pathresize = "";
		$imgs->paththumb = "image";
		$sizes = array();
		for($i=0;$i< count($allHook);$i++) {
			$sizes[$i]['width'] = $allHook[$i]->WIDTH;
			$sizes[$i]['height'] = $allHook[$i]->HEIGHT;
		}
		$imgs->sizes = $sizes;
			
		if($dataBD->IMAGE != "") {
			$url = $imgs->dirbase.$imgs->path."/".$imgs->pathoriginal."/".$dataBD->IMAGE;
			deleteFile($url);
			//$url = $imgs->dirbase.$imgs->path."/".$imgs->pathresize."/".$dataBD->IMAGE;
			//deleteFile($url);
			for($i=0;$i<count($imgs->sizes);$i++) {
				$url = $imgs->dirbase.$imgs->path."/".$imgs->paththumb."/".($i+1)."-".$dataBD->IMAGE;
				deleteFile($url);
			}
		}
		//borro imagen mobile
		$imgsM = new Image();
		$imgsM->path = "publicity";
		$imgsM->pathoriginal = "original";
		$imgsM->pathresize = "";
		$imgsM->paththumb = "mobile";
		$sizes = array();
		for($i=0;$i< count($allHook);$i++) {
			$sizes[$i]['width'] = $allHook[$i]->WIDTH_MOBILE;
			$sizes[$i]['height'] = $allHook[$i]->HEIGHT_MOBILE;
		}
		$imgsM->sizes = $sizes;
		if($dataBD->IMAGE != "") {
			$url = $imgsM->dirbase.$imgsM->path."/".$imgsM->pathoriginal."/".$dataBD->IMAGE_MOBILE;
			deleteFile($url);
			//$url = $imgsM->dirbase.$imgsM->path."/".$imgsM->pathresize."/".$dataBD->IMAGE_MOBILE;
			//deleteFile($url);
			for($i=0;$i<count($imgsM->sizes);$i++) {
				$url = $imgsM->dirbase.$imgsM->path."/".$imgsM->paththumb."/".($i+1)."-".$dataBD->IMAGE_MOBILE;
				deleteFile($url);
			}
		}
		
		//Borro posicones
		$q = "delete from ".preBD."publicity_hook_zone where IDITEM = " . $dataBD->ID;
		$res = checkingQuery($connectBD, $q);
		
		//Borro el baner
		$q = "delete from ".preBD."publicity where ID = " . $dataBD->ID;
		$res = checkingQuery($connectBD, $q);

		$this->updatePosition();
		
		return $res;		
	}
	
}