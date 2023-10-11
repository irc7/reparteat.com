<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Proveedor
*/
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
	protected $extra;
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
		
		
		
		$q = "INSERT INTO `".preBD."suppliers`(`TITLE`, `TEXT`, `ESLOGAN`, `LOGO`, `IMAGE`, `PHONE`, `MOVIL`, `COST`, `MIN`, `TIME`,  `EXTRA_ORDER`,  `IDTELEGRAM`, `STATUS`, `VIEW_IMG`)
				VALUES 
			('".$this->title."', '".$this->text."', '".$this->eslogan."', '".$this->logo."', '".$this->image."', '".$this->phone."', '".$this->movil."', '".number_format($this->cost,2,'.','')."', '".number_format($this->minimo,2,'.','')."', '".$this->time."', '".$this->extra."', '".$this->idtelegram."', '".$this->status."', '".$this->view_img."')";
		
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
	public function update_all($id){
		
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
				`EXTRA_ORDER`='".$this->extra."',
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
			$q = "UPDATE `".preBD."url_web` SET 
				`SLUG`='".$slug."',
				`TITLE`='".mysqli_real_escape_string($connectBD,$this->title)."' 
				WHERE ID_VIEW = '" . $id . "' and TYPE = 'supplier'";
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
	public function update($id){
		
		global $connectBD;
		$this->id = $id;
		
		$q = "UPDATE `".preBD."suppliers` SET ";
			if($this->title != "") {
				$q .= "`TITLE`='".$this->title."',";
			}
			if($this->phone != "") {
				$q .= "`PHONE`='".$this->phone."',";
			}
			if($this->movil != "") {
				$q .= "`MOVIL`='".$this->movil."',";
			}
			if($this->minimo > 0) {	
				$q .= "`MIN`='".$this->minimo."',";
			}
			if($this->time > 0) {
				$q .= "`TIME`='".$this->time."',";
			}
			$q .= "`STATUS`='".$this->status."'
			WHERE ID = " . $id;
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			//asociaciones
			$this->updateCategories($id);
			
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
			$q = "UPDATE `".preBD."url_web` SET 
				`SLUG`='".$slug."',
				`TITLE`='".mysqli_real_escape_string($connectBD,$this->title)."' 
				WHERE ID_VIEW = '" . $id . "' and TYPE = 'supplier'";
			checkingQuery($connectBD, $q);
			
			return $id;					
		}
	}
	
	public function infoSupplierById($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."suppliers.*, 
				".preBD."url_web.SLUG 
			from ".preBD."suppliers 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."suppliers.ID and ".preBD."url_web.TYPE = 'supplier'
			where true and ".preBD."suppliers.ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function totalListSupplier($search = null, $filterCat = null) {
		global $connectBD;
		$qsearch1 = "";
		$qsearch2 = "";
		if($search > 0) {
			$qsearch1 = " inner join ".preBD."user_sup_web_address 
					on ".preBD."user_sup_web_address.IDASSOC = ".preBD."suppliers.ID and ".preBD."user_sup_web_address.TYPE = 'proveedor'";
			$qsearch2 = "and ".preBD."user_sup_web_address.IDZONE = " . $search;	
		}
		$q = "select DISTINCT ".preBD."suppliers.* from ".preBD."suppliers".$qsearch1." where true and (STATUS = 1 or STATUS = 2) ".$qsearch2;
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
		
	}
	public function listSupplier($search = null, $filterCat = null, $start = null, $finish = null) {
		global $connectBD;
		$qsearch1 = "";
		$qsearch2 = "";
		if($search > 0) {
			$qsearch1 = "inner join ".preBD."user_sup_web_address 
					on ".preBD."user_sup_web_address.IDASSOC = ".preBD."suppliers.ID and ".preBD."user_sup_web_address.TYPE = 'proveedor'";
			$qsearch2 = "and ".preBD."user_sup_web_address.IDZONE = " . $search;	
		}
		
		$q = "select DISTINCT ".preBD."suppliers.*, 
				".preBD."url_web.SLUG 
				from ".preBD."suppliers 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."suppliers.ID and ".preBD."url_web.TYPE = 'supplier'
				".$qsearch1."
				where true 
				and (".preBD."suppliers.STATUS = 1 or ".preBD."suppliers.STATUS = 2)
				".$qsearch2."
				order by RAND()"; 
				//limit ".$start.", ".$finish;limite desactivado para que no haya paginacion
	
		$r = checkingQuery($connectBD, $q);
		//pre($q);
		
		$sup = array();
		while($data = mysqli_fetch_object($r)) {
			$sup[] = $data;
		}
		//pre(count($sup));
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
		
		$q = "select 
				".preBD."user_sup_web_address.ID,
				".preBD."user_sup_web_address.STREET,
				".preBD."zone.ID as idZone,
				".preBD."zone.CITY,
				".preBD."zone.PROVINCE,
				".preBD."zone.CP,
				".preBD."zone.ORDER_LIMIT,
				".preBD."zone.REP_LIMIT
				from ".preBD."user_sup_web_address 
				inner join ".preBD."zone on ".preBD."zone.ID = ".preBD."user_sup_web_address.IDZONE
				where ".preBD."user_sup_web_address.IDASSOC = " . $id. " and ".preBD."user_sup_web_address.TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function supplierAddressAll($id = null) {
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
	public function supplierTimeControl($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."supplier_horario.*
				from ".preBD."supplier_horario 
				where ".preBD."supplier_horario.IDASSOC = " . $id. " and TYPE = 'proveedor'
				order by DAY asc, START_H asc";
		$r = checkingQuery($connectBD, $q);
		$time = array();
		while($data = mysqli_fetch_object($r)) {
			$time[] = $data;
		}
		return $time;
	}
	public function checkingOpen($id = null, $idZone = null) {
		global $connectBD;
		
		$now = new DateTime();
		$day = $now->format("N");
		$hora = $now->format("G");
		$min = intval($now->format("i"));

		$q ="select * 
		from ".preBD."user_sup_web_address 
		where true 
		and ".preBD."user_sup_web_address.TYPE = 'proveedor'
		and ".preBD."user_sup_web_address.ACTIVE = 1
		and ".preBD."user_sup_web_address.IDASSOC = " . $id;
		if(intval($idZone) > 0) {
			$q .= "	and ".preBD."user_sup_web_address.IDZONE = " . $idZone;
		}
		$r = checkingQuery($connectBD, $q);
		//$total = mysqli_num_rows($r);
		$row = mysqli_fetch_object($r);
		if($row) {
			$q = "select ".preBD."supplier_horario.*
					from ".preBD."supplier_horario 
					where ".preBD."supplier_horario.IDASSOC = " . $id. " 
					and TYPE = 'proveedor'";
					if(intval($idZone) > 0) {
						$q .= "	and IDZONE = " . $idZone;
					}
			$q .= " and DAY = '" . $day."'
					and (
							(START_H = '".$hora."' 
								and START_M <= '".$min."' 
								and (FINISH_H > '".$hora."' 
									or (FINISH_H = '".$hora."' 
											and FINISH_M >= '".$min."'
										)
									)
							)
						or (START_H < '".$hora."' and (FINISH_H > '".$hora."' 
									or (FINISH_H = '".$hora."' 
											and FINISH_M >= '".$min."'
										)
									)
							)
						or (
								(START_H < '".$hora."' 
									or (START_H = '".$hora."' 
										and START_M <= '".$min."'
										)
								) 
								and FINISH_H = '".$hora."' 
								and FINISH_M >= '".$min."'
							)
						)
					limit 0, 1";
			
			$r = checkingQuery($connectBD, $q);
			$total = mysqli_num_rows($r);
			if($total > 0) {
				$row = mysqli_fetch_object($r);
				$time["status"] = 1;
				$time["time"] = $row;
			} else {
				$q = "select ".preBD."supplier_horario.*
					from ".preBD."supplier_horario 
					where ".preBD."supplier_horario.IDASSOC = " . $id. " 
					and TYPE = 'proveedor'";
				if(intval($idZone) > 0) {
					$q .= "	and IDZONE = " . $idZone;
				}
				$q .= "	and (DAY = '" . $day."' and START_H > '".$hora."')
					or (DAY = '" . $day."' and START_H = '".$hora."' and START_M >= '".$min."')
					order by DAY asc, START_H asc, START_M asc
					limit 0, 1";
					
				$r = checkingQuery($connectBD, $q);
				$totalSem = mysqli_num_rows($r);
				if($totalSem > 0) {
					$row = mysqli_fetch_object($r);
					$time["status"] = 0;
					$time["time"] = $row;
				}else {
					$time["status"] = 0;
					$time["time"] = null;
				}
			}
		} else {
			$time["status"] = 0;
			$time["time"] = null;
		}
		return $time;
	}
	public function checkingOpenFranjaPedania($id = null, $idZone = null, $timeMore = null) {
		global $connectBD;
	
		$now = new DateTime();
		$timeStimed = $now->getTimestamp();
		
		$startTime = new DateTime();
		$startTime->setTimestamp($timeStimed);

		$day = $startTime->format("N");
		$hora = $startTime->format("G");
		$min = intval($startTime->format("i"));

		
		$q = "select ".preBD."supplier_horario.*
		from ".preBD."supplier_horario 
		where ".preBD."supplier_horario.IDASSOC = " . $id. " 
		and TYPE = 'proveedor'
		and IDZONE = ".$idZone."
		and ((DAY = '" . $day."' and START_H < '".$hora."')
		or (DAY = '" . $day."' and START_H = '".$hora."' and START_M <= '".$min."' and FINISH_H > '".$hora."')
		or (DAY = '" . $day."' and START_H = '".$hora."' and START_M <= '".$min."' and FINISH_H = '".$hora."' and FINISH_M > '".$min."')
		or (DAY = '" . $day."' and START_H < '".$hora."' and FINISH_H > '".$hora."')
		or (DAY = '" . $day."' and START_H < '".$hora."' and FINISH_H = '".$hora."' and FINISH_M < '".$min."')
		) order by DAY asc, START_H asc, START_M asc
		limit 0, 1";
		$r = checkingQuery($connectBD, $q);
		$totalSem = mysqli_num_rows($r);
		
		if($totalSem > 0) {
			$row = mysqli_fetch_object($r);
			$time["status"] = 1;
			$time["time"] = $row;
		}else {
			$time["status"] = 0;
			$time["time"] = null;
		}
		
		return $time;
	}
	
	public function checkingOpenFranja($id = null, $idZone = null, $timeMore = null) {
		global $connectBD;
		
		$now = new DateTime();
		$timeStimed = $now->getTimestamp();
		
		$startTime = new DateTime();
		$startTime->setTimestamp($timeStimed);


		$day = $startTime->format("N");
		$hora = $startTime->format("G");
		$min = intval($startTime->format("i"));
		$q = "select ".preBD."supplier_horario.*
				from ".preBD."supplier_horario 
				where ".preBD."supplier_horario.IDASSOC = " . $id. " 
				and TYPE = 'proveedor'
				and DAY = '" . $day."'
				and (
						(START_H = '".$hora."' 
							and START_M <= '".$min."' 
							and (FINISH_H > '".$hora."' 
								or (FINISH_H = '".$hora."' 
										and FINISH_M >= '".$min."'
									)
								)
						)
					or (START_H < '".$hora."' and (FINISH_H > '".$hora."' 
								or (FINISH_H = '".$hora."' 
										and FINISH_M >= '".$min."'
									)
								)
						)
					or (
							(START_H < '".$hora."' 
								or (START_H = '".$hora."' 
									and START_M <= '".$min."'
									)
							) 
							and FINISH_H = '".$hora."' 
							and FINISH_M >= '".$min."'
						)
					)
				and IDZONE = ".$idZone."
				limit 0, 1";
		$r = checkingQuery($connectBD, $q);
		$total = mysqli_num_rows($r);
		if($total > 0) {
			$row = mysqli_fetch_object($r);
			$time["status"] = 1;
			$time["time"] = $row;
		} else {
			$q = "select ".preBD."supplier_horario.*
				from ".preBD."supplier_horario 
				where ".preBD."supplier_horario.IDASSOC = " . $id. " 
				and TYPE = 'proveedor'
				and (DAY = '" . $day."' and START_H > '".$hora."')
				or (DAY = '" . $day."' and START_H = '".$hora."' and START_M >= '".$min."')
				and IDZONE = ".$idZone."
				order by DAY asc, START_H asc, START_M asc
				limit 0, 1";
				
			$r = checkingQuery($connectBD, $q);
			$totalSem = mysqli_num_rows($r);
			if($totalSem > 0) {
				$row = mysqli_fetch_object($r);
				$time["status"] = 0;
				$time["time"] = $row;
			}else {
				$time["status"] = 0;
				$time["time"] = null;
			}
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
	
	public function infoSupplierUserZone($id = null, $type = null, $zone = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web_supplier_assoc.IDUSER
				from ".preBD."user_web_supplier_assoc 
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " and TYPE = '".$type."' and IDZONE = '".$zone."'";
		
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
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " and TYPE = '".$type."' order by ".preBD."user_web_supplier_assoc.POSITION asc";
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

	public function isUserWebSupplier($idSup = null, $user = null) {
		global $connectBD;
		
		if($user->IDTYPE == 2) {
			$q = "select ".preBD."user_web_supplier_assoc.IDUSER
					from ".preBD."user_web_supplier_assoc 
					where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $idSup. " and IDUSER = ".$user->ID." and TYPE = 'proveedor'";
			$r = checkingQuery($connectBD, $q);
			$total = mysqli_num_rows($r);
		}else if($user->IDTYPE == 5){
			$q = "select ".preBD."zone.*
				from ".preBD."zone
				inner join ".preBD."user_web_zone_assoc on ".preBD."user_web_zone_assoc.IDZONE = ".preBD."zone.ID and ".preBD."user_web_zone_assoc.IDUSER = " . $user->ID . "
				where true and ".preBD."zone.STATUS = '1'";
			$rz = checkingQuery($connectBD, $q);
			$total = 0;
			while($rowz = mysqli_fetch_object($rz)){
				if($total == 0){
					$q = "select DISTINCT ".preBD."user_sup_web_address.IDASSOC
						from ".preBD."user_sup_web_address
						where true and ".preBD."user_sup_web_address.TYPE = 'proveedor' 
						and ".preBD."user_sup_web_address.IDZONE = " . $rowz->ID ." 
						and ".preBD."user_sup_web_address.IDASSOC = " . $idSup;
					$r = checkingQuery($connectBD, $q);
					$total = mysqli_num_rows($r);
				}else{
					break;
				}
			}
		}	
		if($total > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function infoCategories($id = null) {
		global $connectBD;
		
		$q = "select 
				".preBD."supplier_cat_assoc.IDCAT, 
				".preBD."suppliers_cat.TITLE
				from ".preBD."supplier_cat_assoc 
				inner join ".preBD."suppliers_cat on ".preBD."supplier_cat_assoc.IDCAT = ".preBD."suppliers_cat.ID
				where ".preBD."supplier_cat_assoc.IDSUPPLIER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($data = mysqli_fetch_object($r)) {
			$cats[] = $data;
		}
		return $cats;
	}
	public function totalInfoSupplierUser($id = null, $type = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web.NAME, ".preBD."user_web.SURNAME, ".preBD."user_web.LOGIN
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
	public function infoSupplierRepartidor($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."user_web.ID, ".preBD."user_web.NAME, ".preBD."user_web.SURNAME, ".preBD."user_web.LOGIN, ".preBD."user_web.IDTELEGRAM
				from ".preBD."user_web_supplier_assoc 
				inner join ".preBD."user_web on ".preBD."user_web_supplier_assoc.IDUSER = ".preBD."user_web.ID
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $id. " and TYPE = 'repartidor'";
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		
		return $data;
		
	}
	
	public function assignSupplierRepartidor($order = null) {
		global $connectBD;
		
		$q = "select IDZONE from ".preBD."user_sup_web_address where ID = " . $order->IDADDRESS;
		$r = checkingQuery($connectBD, $q);		
		$row = mysqli_fetch_object($r);
		$idZone = $row->IDZONE;
		$addressZone = $this->supplierAddressZone($order->IDSUPPLIER, $row->IDZONE);

		$qZ = "select TIME_DELIVERY as timeRepartidor from ".preBD."zone where ID = " .$idZone;
		$rZ = checkingQuery($connectBD, $qZ);
		$zoneOrder = mysqli_fetch_object($rZ);
		
		$qSup = "select EXTRA_ORDER as extra from ".preBD."suppliers where ID = " . $order->IDSUPPLIER;
		$rSup = checkingQuery($connectBD, $qSup);		
		$orderSup = mysqli_fetch_object($rSup);
		
		$q = "select ".preBD."user_web.ID, 
				".preBD."user_web.NAME, 
				".preBD."user_web.SURNAME, 
				".preBD."user_web.LOGIN, 
				".preBD."user_web.IDTELEGRAM
				from ".preBD."user_web_supplier_assoc 
				inner join ".preBD."user_web on ".preBD."user_web_supplier_assoc.IDUSER = ".preBD."user_web.ID
				where ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $order->IDSUPPLIER. " 
				and ".preBD."user_web_supplier_assoc.IDZONE = " . $idZone. " 
				and TYPE = 'repartidor' 
				order by ".preBD."user_web_supplier_assoc.POSITION asc";
		$r = checkingQuery($connectBD, $q);		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
	
		$enc = false;
		$repOK = array();
		foreach($data as $rep){
			$q = "select count(*) as total from ".preBD."order 
					where true 
					and IDREPARTIDOR = " .$rep->ID . " 
					and SEND_START = '" .$order->SEND_START . "' and SEND_FINISH = '" .$order->SEND_FINISH . "' 
					and STATUS > 2 and STATUS < 6 and IDZONE = " . $idZone;
			$r = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_object($r);
			
			
			if(($addressZone->ORDER_LIMIT + $orderSup->extra) > $row->total) {
				$enc = true;
				$repOK[] = $rep;
				break;
			}
		}
		if($enc) {
			$q = "UPDATE `".preBD."order` SET `IDREPARTIDOR`='".$repOK[0]->ID."', `TIMEREPARTIDOR`='".$zoneOrder->timeRepartidor."' WHERE true and ID = " . $order->ID;
			if(checkingQuery($connectBD, $q)) {
				return $repOK;
			}else{
				return false;
			}
		}
		
		return $data;
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

	public function supplierAddressZone($id = null, $zone = null) {
		global $connectBD;
		$q = "select 
				".preBD."user_sup_web_address.*,
				".preBD."zone.ID as idZone,
				".preBD."zone.CITY,
				".preBD."zone.PROVINCE,
				".preBD."zone.CP,
				".preBD."zone.ORDER_LIMIT,
				".preBD."zone.REP_LIMIT
				from ".preBD."user_sup_web_address 
				inner join ".preBD."zone on ".preBD."zone.ID = ".preBD."user_sup_web_address.IDZONE
				where ".preBD."user_sup_web_address.IDASSOC = " . $id. " 
				and ".preBD."user_sup_web_address.IDZONE = " . $zone. "
				and ".preBD."user_sup_web_address.TYPE = 'proveedor'";

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

	
}