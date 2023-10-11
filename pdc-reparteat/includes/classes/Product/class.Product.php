<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
	require_once("../../includes/classes/Image/class.Image.php");
}else {
	require_once "includes/classes/class.System.php";
	require_once("includes/classes/Image/class.Image.php");
}


class Product extends System {
	
	protected $id;
	protected $idsupplier;
	protected $title;
	protected $sumary;
	protected $text;
	protected $position;
	protected $status;
	protected $dateStart;
	protected $dateEnd;
	protected $home;
	protected $cost;
	
	
	public function __construct() {
	}
	
	
	public function add(){
		
		global $connectBD;
		
		$q = "INSERT INTO `".preBD."products`(`IDSUPPLIER`, `TITLE`, `SUMARY`, `TEXT`, `POSITION`, `STATUS`, `DATE_START`, `DATE_END`, `HOME`, `COST`)
				VALUES 
			('".$this->idsupplier."', '".$this->title."', '".$this->sumary."', '".$this->text."', '".$this->position."', '".$this->status."', '".$this->dateStart->format('Y-m-d H:i:s')."', '".$this->dateEnd."', '".$this->home."', '".number_format($this->cost,2,'.','')."')";
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			//asociaciones
			$this->updateCategories($idNew);
			
			$slug = formatNameUrl($this->title);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $idNew . " and TYPE = 'product'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			
			$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
						VALUES ('".$slug."','product','".$this->idsupplier."','".$idNew."','product','".$this->title."')";
			checkingQuery($connectBD, $q);
			
			
			return $idNew;					
		}
	}
	public function update($id){
		
		global $connectBD;
		$this->id = $id;
		
		$q = "UPDATE `".preBD."products` SET 
				`IDSUPPLIER`='".$this->idsupplier."',
				`TITLE`='".$this->title."',
				`SUMARY`='".$this->sumary."', 
				`TEXT`='".$this->text."', 
				`POSITION`='0',
				`STATUS`='".$this->status."',
				`DATE_START`='".$this->dateStart->format('Y-m-d H:i:s')."',
				`DATE_END`='',
				`HOME`='".$this->home."',
				`COST`='".$this->cost."'
			WHERE ID = " . $id;
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			//asociaciones
			$this->updateCategories($id);
			
			$slug = formatNameUrl($this->title);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id . " and TYPE = 'product'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			$q = "UPDATE `".preBD."url_web` SET 
				`SLUG`='".$slug."',
				`SEC_VIEW`='".$this->idsupplier."',
				`TITLE`='".mysqli_real_escape_string($connectBD,$this->title)."' 
				WHERE ID_VIEW = '" . $id . "' and TYPE = 'product'";
			checkingQuery($connectBD, $q);
			
			return $id;					
		}
	}
	public function updateCategories($id = null) {
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."products_cat_assoc` WHERE IDPRODUCT = " . $id;
		checkingQuery($connectBD, $q);
		
		for($i=0;$i<count($this->categories);$i++) {
			$q = "INSERT INTO `".preBD."products_cat_assoc`(`IDCAT`, `IDPRODUCT`) 
					VALUES 
					(".$this->categories[$i].",".$id.")";
			checkingQuery($connectBD, $q);			
		}
	}
	public function totalImageProduct($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."product_images where true and IDASSOC = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
		
	}
	public function productImages($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."product_images where true and IDASSOC = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$imgs = array();
		while($data = mysqli_fetch_object($r)) {
			$imgs[] = $data;
		}
		return $imgs;
		
	}
	public function productImageFav($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."product_images where true and IDASSOC = " . $id . " and FAV = 1";
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		
		return $data;
		
	}
	public function addImages($id = null, $imgs) {
		global $connectBD;
		
		$position = $this->totalImageProduct($id)+1;
		$fav = 0;
		if($position == 1) {
			$fav = 1;
		}
		if(count($imgs) > 0) {
			for($i=0;$i<count($imgs);$i++) {
				if($imgs[$i]['image'] != "") {
					$q = "INSERT INTO `".preBD."product_images`(`IDASSOC`, `TYPE`, `TITLE`, `URL`, `POSITION`, `FAV`) 
						VALUES 
						(".$id.", 'product', '".$imgs[$i]['image']."', '".$imgs[$i]['image']."',".($position+$i).", ".$fav.")";
					checkingQuery($connectBD, $q);
					$fav = 0;
				}
			}
		}
	}
	public function updateImgBD($dataImg) {
		global $connectBD;
		$q = "UPDATE `".preBD."product_images` SET 
			`TITLE`= '".$dataImg["title"]."',
			`POSITION`= ".$dataImg["position"].",
			`FAV`= ".$dataImg["fav"]."
			WHERE ID = ". $dataImg["id"];
			
		checkingQuery($connectBD, $q);
	}
	public function deleteImgBD($idImg = null) {
		global $connectBD;
		$q = "DELETE FROM `".preBD."product_images` WHERE ID = ". $idImg;
		checkingQuery($connectBD, $q);
		
	}
	public function addComponent($id = null, $coms) {
		global $connectBD;
			
		for($i=0;$i<count($coms);$i++) {
			if($this->checkingComponent($id, $coms[$i]["id"]) == 0) {
				$q = "INSERT INTO `".preBD."products_com_assoc`(`IDCOM`, `IDPRODUCT`, `TYPE`, `COST`) 
						VALUES 
						(".$coms[$i]["id"].",".$id.",'".$coms[$i]["type"]."','".number_format($coms[$i]["cost"],2,'.','')."')";
				checkingQuery($connectBD, $q);			
			}
		}
	}
	public function updateComponents($id = null, $coms) {
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."products_com_assoc` WHERE IDPRODUCT = " . $id;
		checkingQuery($connectBD, $q);
		
		for($i=0;$i<count($coms);$i++) {
			if($this->checkingComponent($id, $coms[$i]["id"]) == 0) {
				$q = "INSERT INTO `".preBD."products_com_assoc`(`IDCOM`, `IDPRODUCT`, `TYPE`, `COST`) 
						VALUES 
						(".$coms[$i]["id"].",".$id.",'".$coms[$i]["type"]."','".number_format($coms[$i]["cost"],2,'.','')."')";
				checkingQuery($connectBD, $q);			
			}
		}
	}
	
	public function checkingComponent($id = null, $idCom = null) {
		global $connectBD;
		$q = "select * from `".preBD."products_com_assoc` where IDPRODUCT = '" .$id. "' and IDCOM = '".$idCom."'";
		$r = checkingQuery($connectBD, $q);
		
		$exist = mysqli_num_rows($r);
		
		return $exist;
	}
	public function infoProductById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."products where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function productComsByIdCom($idCom = null) {
		global $connectBD;
		$q = "select ".preBD."products_com.*
				from ".preBD."products_com 
				where true 
				and ".preBD."products_com.ID = ".$idCom;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		
		return $data;
	}
	public function listProduct() {
		global $connectBD;
		
		$q = "select * from ".preBD."products where true";
		$r = checkingQuery($connectBD, $q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data->IDCAT;
		}
		return $sup;
	}
	public function totalProduct() {
		global $connectBD;
		
		$q = "select * from ".preBD."products where true";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysql_num_rows($r);
		
		return $total;
	}
	
	public function productComponents($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_com_assoc.ID,
				".preBD."products_com_assoc.IDCOM,
				".preBD."products_com_assoc.COST,
				".preBD."products_com.TITLE,
				".preBD."products_com_assoc.TYPE
				from ".preBD."products_com 
				inner join ".preBD."products_com_assoc 
				on ".preBD."products_com.ID = ".preBD."products_com_assoc.IDCOM 
				and ".preBD."products_com_assoc.IDPRODUCT = ".$id."
				where true order by ".preBD."products_com_assoc.TYPE asc, ".preBD."products_com_assoc.ID asc";
		$r = checkingQuery($connectBD, $q);
		$auxData = array();
		while($data = mysqli_fetch_object($r)) {
			$auxData[] = $data;
		}
		return $auxData;
	}
	
	public function infoCategories($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_cat_assoc.IDCAT
				from ".preBD."products_cat_assoc 
				where ".preBD."products_cat_assoc.IDPRODUCT = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($data = mysqli_fetch_object($r)) {
			$cats[] = $data->IDCAT;
		}
		return $cats;
	}
	public function totalInfoCategories($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_cat.*
				from ".preBD."products_cat_assoc 
				inner join ".preBD."products_cat on ".preBD."products_cat_assoc.IDCAT = ".preBD."products_cat.ID
				where ".preBD."products_cat_assoc.IDPRODUCT = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($data = mysqli_fetch_object($r)) {
			$cats[] = $data;
		}
		return $cats;
	}
	public function infoSupplier($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."suppliers.*
				from ".preBD."suppliers 
				inner join ".preBD."products on ".preBD."products.IDSUPPLIER = ".preBD."suppliers.ID
				where ".preBD."products.ID = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		return $data;
	}
	
	
	public function upStatus($id, $status){
		global $connectBD;
		$q = "UPDATE ".preBD."products SET 
				STATUS = ". $status ." 
				where ID = " . $id;
		checkingQuery($connectBD, $q);
	}
	
	public function delete($id){
		global $connectBD;
		
		//borro las imágenes
		$imgObj = new Image();
		$imgObj->path = "product";
		$imgObj->pathoriginal = "original";
		$imgObj->pathresize = "image";
		$imgObj->paththumb = "thumb";
		$imgObj->sizes = array(
					'0' => array ('width' => 900,
									'height' => 500
					),
					'1' => array ('width' => 500,
									'height' => 400
					),
					'2' => array ('width' => 400,
									'height' => 400
					));
		$imagesBD = $this->productImages($id);
		
		foreach($imagesBD as $imgBD) {
			if($imgBD->URL != "") {
				$url = $imgObj->dirbase.$imgObj->path."/".$imgObj->pathoriginal."/".$imgBD->URL;
				deleteFile($url);
				$url = $imgObj->dirbase.$imgObj->path."/".$imgObj->pathresize."/".$imgBD->URL;
				deleteFile($url);
				for($i=0;$i<count($imgObj->sizes);$i++) {
					$url = $imgObj->dirbase.$imgObj->path."/".$imgObj->paththumb."/".($i+1)."-".$imgBD->URL;
					deleteFile($url);
				}
			}
			$this->deleteImgBD($imgBD->ID);
		}
		//Asociaciones a components
		$q = "delete from ".preBD."products_com_assoc where IDPRODUCT = " . $id;
		$res = checkingQuery($connectBD, $q);
		//Asociaciones a categorias
		$q = "delete from ".preBD."products_cat_assoc where IDPRODUCT = " . $id;
		$res = checkingQuery($connectBD, $q);
		
		//Borro el producto
		$q = "delete from ".preBD."products where ID = " . $id;
		$res = checkingQuery($connectBD, $q);
		return $res;		
	}
	public function infoProductByIdNoStatus($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."products where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function productIcon($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_com.ID
				from ".preBD."products_com 
				inner join ".preBD."products_com_assoc 
				on ".preBD."products_com.ID = ".preBD."products_com_assoc.IDCOM 
				and ".preBD."products_com_assoc.IDPRODUCT = ".$id."
				where true order by ".preBD."products_com_assoc.TYPE asc, ".preBD."products_com_assoc.ID asc";
		$r = checkingQuery($connectBD, $q);
		$auxData = array();
		while($data = mysqli_fetch_object($r)) {
			$q2 = "select DISTINCT ".preBD."products_com_icon.*
				from ".preBD."products_com_icon 
				inner join ".preBD."products_com_icon_assoc 
				on ".preBD."products_com_icon.ID = ".preBD."products_com_icon_assoc.IDICON
				and (".preBD."products_com_icon_assoc.IDCOM = ".$data->ID; 
				while($data = mysqli_fetch_object($r)) {
					$q2 .= " or ".preBD."products_com_icon_assoc.IDCOM = ".$data->ID;
				}
				$q2 .= ") where true order by ".preBD."products_com_icon.TITLE asc";
			$r2 = checkingQuery($connectBD, $q2);
			while($data2 = mysqli_fetch_object($r2)) {
				$auxData[] = $data2;
			}
		}
		
		return $auxData;
	}

	public function listProductBySupplier($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products.*,
				".preBD."url_web.SLUG 
				from ".preBD."products 
				inner join ".preBD."url_web 
				on ".preBD."url_web.ID_VIEW = ".preBD."products.ID and ".preBD."url_web.TYPE = 'product'
				where true and ".preBD."products.IDSUPPLIER = " . $id . " and ".preBD."products.STATUS = 1
				order by ".preBD."products.DATE_START desc";
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		return $data;
	}
	public function productComponentsType($id = null, $type = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_com_assoc.ID,
				".preBD."products_com_assoc.IDCOM,
				".preBD."products_com_assoc.COST,
				".preBD."products_com.TITLE,
				".preBD."products_com_assoc.TYPE
				from ".preBD."products_com 
				inner join ".preBD."products_com_assoc 
				on ".preBD."products_com.ID = ".preBD."products_com_assoc.IDCOM 
				and ".preBD."products_com_assoc.IDPRODUCT = ".$id."
				and ".preBD."products_com_assoc.TYPE = '".$type."'
				where true order by ".preBD."products_com_assoc.ID asc";
		$r = checkingQuery($connectBD, $q);
		$auxData = array();
		while($data = mysqli_fetch_object($r)) {
			$auxData[] = $data;
		}
		return $auxData;
	}
	public function infoProductCompByIdAssoc($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."products_com.ID,
				".preBD."products_com.TITLE, 
				".preBD."products_com_assoc.COST,
				".preBD."products_com_assoc.TYPE
				from ".preBD."products_com_assoc 
				inner join ".preBD."products_com 
				on ".preBD."products_com.ID = ".preBD."products_com_assoc.IDCOM 
				where true 
				and ".preBD."products_com_assoc.ID = ".$id;
		$r = checkingQuery($connectBD, $q);
		$data = mysqli_fetch_object($r);
		return $data;
	}
}