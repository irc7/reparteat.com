<?php
/*
Author: info@ismaelrc.es
Date: 2022-11-03

Proveedor
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
	require_once("../../includes/classes/Image/class.Image.php");
}else {
	require_once "includes/classes/class.System.php";
	require_once("includes/classes/Image/class.Image.php");
}

class Multislide extends System {
	
	protected $id;
	public $idhook;
	protected $title;
	protected $subtitle;
	protected $image;
	protected $image_mobile;
	protected $video;
	protected $type;
	protected $status;
	protected $position;
	
	
	public function __construct() {
	}
	
	
	public function add(){
		
		global $connectBD;
		
		$q = "INSERT INTO `".preBD."multislide`(`IDHOOK`, `TITLE`, `SUBTITLE`, `IMAGE`, `IMAGE_MOBILE`, `VIDEO`, `TYPE`, `STATUS`, `POSITION`)
				VALUES 
			('".$this->idhook."', '".$this->title."', '".$this->subtitle."', '".$this->image."', '".$this->image_mobile."', '".$this->video."', '".$this->type."', '".$this->status."', '".$this->position."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			return $idNew;					
		}

	}
	public function update($id){
		
		global $connectBD;
		$this->id = $id;
		
		$q = "UPDATE `".preBD."multislide` SET 
				`IDHOOK`='".$this->idhook."',
				`TITLE`='".$this->title."',
				`SUBTITLE`='".$this->subtitle."', 
				`IMAGE`='".$this->image."',
				`IMAGE_MOBILE`='".$this->image_mobile."', 
				`VIDEO`='".$this->video."',
				`TYPE`='".$this->type."',
				`STATUS`='".$this->status."'
			WHERE ID = " . $id;
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return $id;					
		}
	}
	public function updatePosition() {
		global $connectBD;
		$hook = array();
		$q = "select ID from ".preBD."multislide_hook order by ID asc";
		$r = checkingQuery($connectBD, $q);
		while($data = mysqli_fetch_object($r)) {
			$hook[] = $data->ID;
		}
		
		for($i=0;$i< count($hook);$i++) {
			$q = "select ID from ".preBD."multislide where true
					and IDHOOK = " . 	$hook[$i] . "
					order by POSITION asc";
			$r = checkingQuery($connectBD, $q);
			$cont = 1;
			while($row = mysqli_fetch_object($r)) {
				$q = "UPDATE `".preBD."multislide` SET `POSITION`=".$cont." WHERE ID = ". $row->ID;
				checkingQuery($connectBD, $q);
				$cont++; 
			}
		}
	}
	public function infoMultislideById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."multislide where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function allMultislide() {
		global $connectBD;
		
		$q = "select * from ".preBD."multislide where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	public function listFilter($filterHook = null , $order = null) {
		global $connectBD;
		$sup = array();
		$q = "select ".preBD."multislide.* 
				from ".preBD."multislide 
				where true 
				and ".preBD."multislide.IDHOOK = " . $filterHook. "
				order by ".preBD."multislide.POSITION ". $order;

		$r = checkingQuery($connectBD, $q);
		
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		return $sup;
	}
	public function lastPositionByHook($idhook = null) {
		global $connectBD;
		
		$q = "select count(*) as total
				from ".preBD."multislide 
				where ".preBD."multislide.IDHOOK = " . $idhook;
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data->total;
		} else {
			return false;
		}
	}
	
	public function upStatus($id, $status){
		global $connectBD;
		$q = "UPDATE ".preBD."multislide SET 
				STATUS = ". $status ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	
	public function delete($dataBD){
		global $connectBD;
		$q = "select * from ".preBD."multislide_hook";
		$r = checkingQuery($connectBD, $q);
		$allHook = array();
		while($row = mysqli_fetch_object($r)){
			$allHook[] = $row;
		}
		//borro imagen
		$imgs = new Image();
		$imgs->path = "multislide";
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
		$imgsM->path = "multislide";
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
		
		//Borro el baner
		$q = "delete from ".preBD."multislide where ID = " . $dataBD->ID;
		$res = checkingQuery($connectBD, $q);

		$this->updatePosition();
		
		return $res;		
	}
	
}