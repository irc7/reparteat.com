<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/

class Icon extends System {
	
	protected $id;
	protected $title;
	protected $icon;
	
	
	public function __construct() {
	}
	
	public function listIcon($searchq = null, $recordq = null,$firstrecord = null,$recordsperpage = null) {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_com_icon where true " . $searchq . $recordq . " ORDER BY TITLE asc LIMIT " . $firstrecord . ", " . $recordsperpage;
		$r = checkingQuery($connectBD, $q);
		$total = mysqli_num_rows($r);
		
		$icons = array();
		while($row = mysqli_fetch_object($r)) {
			$icons[] = $row;
		}
		return $icons;
	}
	public function allIcon() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_com_icon where true";
		$r = checkingQuery($connectBD, $q);
		
		$icons = array();
		while($row = mysqli_fetch_object($r)) {
			$icons[] = $row;
		}
		return $icons;
	}
	public function totalIcon() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."products_com_icon where true";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
	}
	
	public function infoIconById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."products_com_icon where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function allIconComponent($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_com_icon.* 
				from ".preBD."products_com_icon_assoc
				inner join ".preBD."products_com_icon on ".preBD."products_com_icon_assoc.IDICON = ".preBD."products_com_icon.ID
				where true and ".preBD."products_com_icon_assoc.IDCOM = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		$auxData = array();
		
		while($data = mysqli_fetch_object($res)) {
			$auxData[]=$data;
		}
		return $auxData;
	}
	public function add(){
		global $connectBD;
		$q = "INSERT INTO ".preBD."products_com_icon (TITLE, ICON) 
				VALUES 
			('".$this->title."', '".$this->icon."')";
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			return $idNew;					
		}
	}
	public function update($id = null){
		global $connectBD;
		
		$q = "UPDATE `".preBD."products_com_icon` SET
				`TITLE`='".$this->title. "', 
				`ICON`='".$this->icon . "'
			WHERE ID = " . $id;
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			return true;					
		}
	}
	
	public function delete($id){
		global $connectBD;
		
		//borro las imágenes
		$imgIcon = new Image();
		$imgIcon->postName = "Icon";
		$imgIcon->sizes = array(
					'0' => array ('width' => 30,
									'height' => 30
					));
		$imgIcon->path = "product";
		$imgIcon->pathoriginal = "original";
		$imgIcon->paththumb = "icon";
		
		if($this->icon != "") {
			$url = $imgIcon->dirbase.$imgIcon->path."/".$imgIcon->pathoriginal."/".$this->icon;
			deleteFile($url);
			for($i=0;$i<count($imgIcon->sizes);$i++) {
				$url = $imgIcon->dirbase.$imgIcon->path."/".$imgIcon->paththumb."/".($i+1)."-".$this->icon;
				deleteFile($url);
			}
		}
		$q = "delete from ".preBD."products_com_icon_assoc where IDICON = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		$q = "delete from ".preBD."products_com_icon where ID = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		return $res;		
	}
	
}