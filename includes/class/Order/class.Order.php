<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/

class Order extends System {
	
	protected $id;
	protected $ref;
	protected $idsupplier;
	protected $iduser;
	protected $idrepartidor;
	protected $idaddress;
	protected $idzone;
	protected $idmethodpay;
	protected $date_create;
	protected $date_start;
	protected $comment;
	protected $timesupplier;
	protected $timerepartidor;
	protected $subtotal;
	protected $shipping;
	protected $discount;
	protected $cost;
	protected $status;
	protected $send_start;
	protected $send_finish;
	
	public function __construct() {
	}
	
	
	public function add() {
		global $connectBD;
		$q = "INSERT INTO `".preBD."order`(`REF`, `IDSUPPLIER`, `IDUSER`, `IDREPARTIDOR`, `IDADDRESS`, `IDZONE`, `IDMETHODPAY`, `DATE_CREATE`, `DATE_START`, `COMMENT`, `TIMESUPPLIER`, `TIMEREPARTIDOR`, `SUBTOTAL`, `SHIPPING`, `DISCOUNT`, `COST`, `STATUS`, `SEND_START`, `SEND_FINISH`) 
		VALUES 
		('".$this->ref."','".$this->idsupplier."','".$this->iduser."','".$this->idrepartidor."','".$this->idaddress."','".$this->idzone."','".$this->idmethodpay."','".$this->date_create."','".$this->date_start."','".$this->comment."','".$this->timesupplier."','".$this->timerepartidor."','".$this->subtotal."','".$this->shipping."','".$this->discount."','".$this->cost."','".$this->status."','".$this->send_start."','".$this->send_finish."')";
		
		if(checkingQuery($connectBD, $q)) {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			return $idNew;
		} else {
			return false;
		}
	}
	public function addProduct($idProduct = null, $uds = null, $cost = null, $idCom = null, $type = null) {
		global $connectBD;
	
		$q = "INSERT INTO `".preBD."order_product`(`IDORDER`, `IDPRODUCT`, `UDS`, `COST`, `IDCOM`, `TYPE`) 
				VALUES 
			('".$this->id."','".$idProduct."','".$uds."','".$cost."','".$idCom."','".$type."')";
		if(checkingQuery($connectBD, $q)) {
			return true;
		} else {
			return false;
		}
	}
	public function addProductByIdOrder($id = null, $idProduct = null, $uds = null, $cost = null, $idCom = null, $type = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."order_product where true 
				and IDORDER = " . $id . " 
				and IDPRODUCT = ".$idProduct."
				and IDCOM = '".$idCom."'";
		$r = checkingQuery($connectBD, $q);
		$total = mysqli_num_rows($r);
		$oldPro = mysqli_fetch_object($r);
		
		if($total == 0) {
			$q = "INSERT INTO `".preBD."order_product`(`IDORDER`, `IDPRODUCT`, `UDS`, `COST`, `IDCOM`, `TYPE`) 
				VALUES 
				('".$id."','".$idProduct."','".$uds."','".$cost."','".$idCom."','".$type."')";
			if(checkingQuery($connectBD, $q)) {
				$idNew = mysqli_insert_id($connectBD);
				return $idNew;
			} else {
				return false;
			}
		} else {
			$costTotal = $oldPro->COST + $cost;
			$udsTotal = $uds + $oldPro->UDS;
			$q = "UPDATE `".preBD."order_product` SET 
					`UDS`= ".$udsTotal.",
					`COST`= ".$costTotal."
					WHERE true 
					and ID = " . $oldPro->ID;
			if(checkingQuery($connectBD, $q)) {
				return $oldPro->ID;
			} else {
				return false;
			}
		}
		
	}
	public function deleteProductByIdOrder($idOrder = null, $idProductOrder = null) {
		global $connectBD;
		$q = "DELETE FROM `".preBD."order_product` WHERE ID = " . $idProductOrder . " and IDORDER = " . $idOrder;
		if(checkingQuery($connectBD, $q)) {
			return true;
		} else {
			return false;
		}
	}
	public function updateTotalOrder($order) {
		global $connectBD;

		$products = $this->listProductOrder($order->ID);
		$subtotal = 0;
		foreach($products as $item) {
			$subtotal = $subtotal + $item->COST;
		}
		
		$total = $subtotal + $order->SHIPPING - $order->DISCOUNT;

		$q = "UPDATE `".preBD."order` SET `SUBTOTAL`='".$subtotal."', `COST`='".$total."' WHERE true and ID = " . $order->ID;
		if(checkingQuery($connectBD, $q)) {
			return true;
		}else{
			return false;
		}
	}
	
	public function insertRegMod($action = null, $ref = null, $idProductAssoc = null, $title = null, $cost = null, $idUser = null) {
		global $connectBD;
		$now = new DateTime();
		$q = "INSERT INTO `".preBD."order_change`(`DATE_CREATE`, `REF`,`IDPRODUCTASSOC`, `TITLE`, `ACTION`, `COST`, `IDUSER`, `TABLE_USER`) 
			VALUES 
			('".$now->format("Y-m-d H:i:s")."', '".$ref."','".$idProductAssoc."','".$title."', '".$action."', '".$cost."', '".$idUser."', 'user_web')";
		checkingQuery($connectBD, $q);
	}
	public function updateStatus($id = null, $status = null, $statusOld = null) {
		global $connectBD;
		if($status != $statusOld) {
			$q = "UPDATE `".preBD."order` SET `STATUS`='".$status."' WHERE true and ID = " . $id;
			checkingQuery($connectBD, $q);
			
			$now = new DateTime();
			$q = "INSERT INTO `".preBD."order_staus_time`(`IDORDER`, `IDSTATUS`, `IDSTATUSOLD`, `DATE_CHANGE`) 
			VALUES 
			('".$id."','".$status."','".$statusOld."','".$now->format("Y-m-d H:i:s")."')";
			
			checkingQuery($connectBD, $q);
		}
		return $status;
	}
	public function updateTimeSup($id = null, $time = null) {
		global $connectBD;
		$now = new DateTime();
		$q = "UPDATE `".preBD."order` SET `TIMESUPPLIER`='".$time."', `DATE_START`='".$now->format("Y-m-d H:i:s")."' WHERE true and ID = " . $id;
		if(checkingQuery($connectBD, $q)) {
			return true;
		}else{
			return false;
		}
	}
	public function updateTimeRep($id = null, $idUser = null, $time = null) {
		global $connectBD;
		$q = "UPDATE `".preBD."order` SET `IDREPARTIDOR`='".$idUser."', `TIMEREPARTIDOR`='".$time."' WHERE true and ID = " . $id;
		if(checkingQuery($connectBD, $q)) {
			return true;
		}else{
			return false;
		}
	}
	public function updateRep($id = null, $idUser = null) {
		global $connectBD;
		$q = "UPDATE `".preBD."order` SET `IDREPARTIDOR`='".$idUser."' WHERE true and ID = " . $id;
		if(checkingQuery($connectBD, $q)) {
			return true;
		}else{
			return false;
		}
	}
	public function hourSend($order) {
		$now = new DateTime();
		if($order->DATE_START != "0000-00-00 00:00:00") {
			$date = new DateTime($order->DATE_START);
		}else {
			$date = new DateTime($order->DATE_CREATE);
		}
		$time = ($order->TIMESUPPLIER + $order->TIMEREPARTIDOR) * 60;//pasamos a segundos
		
		$segs = intval($date->getTimestamp()) + intval($time);
		
		$date->setTimestamp($segs);
		
		$string = "Hora estimada de entrega, ";
		if($now->format("d") == $date->format("d") && $now->format("m") == $date->format("m") && $now->format("Y") == $date->format("Y")) {
			$string .= "hoy";
		}else {
			$string .= $date->format("d-m-Y");
		}
		$string .= " a las " . $date->format("H:i");
		
		return $string;
	}
	public function franjaSend($order) {
		$now = new DateTime();
		$start = new DateTime($order->SEND_START);
		$finish = new DateTime($order->SEND_FINISH);
		
		$string = "Franja horaria de envío, ";
		if($now->format("d") == $start->format("d") && $now->format("m") == $start->format("m") && $now->format("Y") == $start->format("Y")) {
			$string .= "hoy";
		}else {
			$string .= $start->format("d-m-Y");
		}
		$string .= " de <strong>" . $start->format("H:i") . " a " . $finish->format("H:i")."</strong>";
		
		return $string;
	}
	public function franjaSendSupplier($order) {
		$now = new DateTime();
		$start = new DateTime($order->SEND_START);
		$finish = new DateTime($order->SEND_FINISH);
		
		$string = "Pedido finalizado para, ";
		if($now->format("d") == $start->format("d") && $now->format("m") == $start->format("m") && $now->format("Y") == $start->format("Y")) {
			$string .= "hoy";
		}else {
			$string .= $start->format("d-m-Y");
		}
		$string .= " a las <strong>" . $start->format("H:i") ."</strong>";
		
		return $string;
	}
	public function franjaInfo($order) {
		$now = new DateTime();
		$start = new DateTime($order->SEND_START);
		$finish = new DateTime($order->SEND_FINISH);
		
		$string = "";
		if($now->format("d") == $start->format("d") && $now->format("m") == $start->format("m") && $now->format("Y") == $start->format("Y")) {
			$string .= "Hoy";
		}else {
			$string .= $start->format("d-m-Y");
		}
		$string .= " de <strong>" . $start->format("H:i") . " a " . $finish->format("H:i")."</strong>";
		
		return $string;
	}
	public function franjaInfoNoDate($order) {
		$now = new DateTime();
		$start = new DateTime($order->SEND_START);
		$finish = new DateTime($order->SEND_FINISH);
		
		$string = "";
		/*if($now->format("d") == $start->format("d") && $now->format("m") == $start->format("m") && $now->format("Y") == $start->format("Y")) {
			$string .= "Hoy";
		}else {
			$string .= $start->format("d-m-Y");
		}*/
		$string .= "De <strong>" . $start->format("H:i") . " a " . $finish->format("H:i")."</strong>";
		
		return $string;
	}
	public function franjaInfoMin($order) {
		$now = new DateTime();
		$start = new DateTime($order->send_start);
		$finish = new DateTime($order->send_finish);
				
		$string = "";
		if($now->format("d") == $start->format("d") && $now->format("m") == $start->format("m") && $now->format("Y") == $start->format("Y")) {
			$string .= "Hoy";
		}else {
			$string .= $start->format("d-m-Y");
		}
		$string .= " de <strong>" . $start->format("H:i") . " a " . $finish->format("H:i")."</strong>";
		
		return $string;
	}
	public function infoOrderByIdStatus($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."order where true and ID = '" . $id . "' and STATUS = 1";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function orderByUser($idUser = null, $status = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		
		$q = "select * from ".preBD."order where true 
				and IDUSER = '" . $idUser . "'";
		if(count($statusArray) > 0 && $status != "") {
			$q .= " and (";
			for($i=0;$i<count($statusArray);$i++) {
				$q .= "STATUS = '".$statusArray[$i]."'"; 
				if($i<count($statusArray)-1) {
					$q .= " or ";
				}
			}
			$q .= ")";
		}
		$q .= " order by DATE_CREATE desc";
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		} 
		return $data;
			
	}
	public function orderByZone($Suppliers = null, $status = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		
		$data = array();
		if(count($Suppliers) > 0) {
			$q = "select * from ".preBD."order where true";
			for($i=0;$i<count($Suppliers);$i++) {
				if($i == 0) {
					$q .= " and(";
				}else {
					$q .= " or";
				}
				$q.= " IDSUPPLIER = '" . $Suppliers[$i]->ID . "'";
				if($i == count($Suppliers) - 1) {
					$q.=")";
				}
			}
			
			if(count($statusArray) > 0 && $status != "") {
				$q .= " and (";
				for($i=0;$i<count($statusArray);$i++) {
					$q .= "STATUS = '".$statusArray[$i]."'"; 
					if($i<count($statusArray)-1) {
						$q .= " or ";
					}
				}
				$q .= ")";
			}
			$q .= " order by DATE_CREATE desc";	
			$r = checkingQuery($connectBD, $q);
			
			while($row = mysqli_fetch_object($r)) {
				$data[]=$row;
			} 
		}
		return $data;
			
	}
	public function orderByZoneFilter($Suppliers = null, $status = null, $dateStart = null, $dateFinish = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		$aux = explode("-", $date);
		
		$dateS = new DateTime($dateStart);
		$dateF = new DateTime($dateFinish);
		$dateq .= " and DATE_CREATE BETWEEN '" . $dateS->format('Y-m-d 01:00:00') . "' and '" . $dateF->format('Y-m-d 23:59:59') . "'";
		
		$data = array();
		if(count($Suppliers) > 0) {
			$q = "select * from ".preBD."order where true";
			for($i=0;$i<count($Suppliers);$i++) {
				if($i == 0) {
					$q .= " and(";
				}else {
					$q .= " or";
				}
				$q.= " IDSUPPLIER = '" . $Suppliers[$i]->ID . "'";
				if($i == count($Suppliers) - 1) {
					$q.=")";
				}
			}
			
			if(count($statusArray) > 0 && $status != "") {
				$q .= " and (";
				for($i=0;$i<count($statusArray);$i++) {
					$q .= "STATUS = '".$statusArray[$i]."'"; 
					if($i<count($statusArray)-1) {
						$q .= " or ";
					}
				}
				$q .= ")";
			}
			$q .= $dateq." order by DATE_CREATE desc";	
			$r = checkingQuery($connectBD, $q);
			
			while($row = mysqli_fetch_object($r)) {
				$data[]=$row;
			} 
		}
		return $data;
			
	}
	public function orderByIdZoneFilter($Suppliers = null, $status = null, $dateStart = null, $dateFinish = null, $idZone = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		$aux = explode("-", $date);
		
		$dateS = new DateTime($dateStart);
		$dateF = new DateTime($dateFinish);
		$dateq .= " and DATE_CREATE BETWEEN '" . $dateS->format('Y-m-d 01:00:00') . "' and '" . $dateF->format('Y-m-d 23:59:59') . "'";
		
		$data = array();
		if(count($Suppliers) > 0) {
			$q = "select ".preBD."order.* from ".preBD."order 
					where true 
					and ".preBD."order.IDZONE = ".$idZone;
			for($i=0;$i<count($Suppliers);$i++) {
				if($i == 0) {
					$q .= " and(";
				}else {
					$q .= " or";
				}
				$q.= " ".preBD."order.IDSUPPLIER = '" . $Suppliers[$i]->ID . "'";
				if($i == count($Suppliers) - 1) {
					$q.=")";
				}
			}
			
			if(count($statusArray) > 0 && $status != "") {
				$q .= " and (";
				for($i=0;$i<count($statusArray);$i++) {
					$q .= "".preBD."order.STATUS = '".$statusArray[$i]."'"; 
					if($i<count($statusArray)-1) {
						$q .= " or ";
					}
				}
				$q .= ")";
			}
			$q .= $dateq." order by ".preBD."order.DATE_CREATE desc";	
			$r = checkingQuery($connectBD, $q);
			
			while($row = mysqli_fetch_object($r)) {
				$data[]=$row;
			} 
		}
		return $data;
			
	}
	public function followOrderZone($Suppliers) {
		global $connectBD; 
		
		$q = "select ".preBD."order.ref as pedido, 
				".preBD."order.send_start as send_start,
				".preBD."order.send_finish as send_finish,
				".preBD."order.status as idStatus,
				".preBD."order.status as idStatus,
				".preBD."order.date_create as fecha_pedido,
				".preBD."suppliers.title as establecimiento,
				".preBD."user_web.name as repartidor,
				".preBD."user_sup_web_address.street as dir_entrega,
				".preBD."order_status.title as estado,
				timestampdiff(minute,now(),date_add(s3.date_change, interval ".preBD."order.timesupplier minute)) as queda_cocina,
				timestampdiff(minute,s4.date_change,now()) as lleva_terminado,
				timestampdiff(minute,now(),date_add(s3.date_change, interval ".preBD."order.timesupplier + ".preBD."order.timerepartidor minute)) as queda_estimacion,
				date_add(s3.date_change, interval ".preBD."order.timesupplier + ".preBD."order.timerepartidor minute) as estimacion_entrega,
				s4.date_change as terminado_cocina,
				s3.date_change as comienzo_cocina
				from ".preBD."order
				left join ".preBD."suppliers on ".preBD."order.idsupplier = ".preBD."suppliers.id
				left join ".preBD."user_web on ".preBD."order.idrepartidor = ".preBD."user_web.id
				left join ".preBD."user_sup_web_address on ".preBD."order.iduser = ".preBD."user_sup_web_address.idassoc and ".preBD."order.idaddress = ".preBD."user_sup_web_address.id
				left join ".preBD."order_status on ".preBD."order.status = ".preBD."order_status.id
				left join ".preBD."order_staus_time s4 on ".preBD."order.id = s4.idorder and s4.idstatus = 4
				left join ".preBD."order_staus_time s3 on ".preBD."order.id = s3.idorder and s3.idstatus = 3
				where ".preBD."order.status < 6 and  ".preBD."order.status >= 2 and (";
				for($i=0;$i<count($Suppliers);$i++) {
					$q.= preBD."order.idsupplier = " . $Suppliers[$i]->ID;
					if($i<count($Suppliers)-1) {
						$q .= " or ";
					}
				}
				$q .= ") 
				ORDER BY SEND_START asc";
			
				
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		}
		return $data;
	}
	public function orderBySupplier($id = null, $status = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		
		$q = "select * from ".preBD."order where true and IDSUPPLIER = '" . $id . "'";
		if(count($statusArray) > 0 && $status != "") {
			$q .= " and (";
			for($i=0;$i<count($statusArray);$i++) {
				$q .= "STATUS = '".$statusArray[$i]."'"; 
				if($i<count($statusArray)-1) {
					$q .= " or ";
				}
			}
			$q .= ")";
		}
		$q .= " order by DATE_CREATE desc";
	
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		} 
		return $data;
			
	}
	public function orderBySupplierSend($id = null, $status = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		
		$q = "select * from ".preBD."order where true and IDSUPPLIER = '" . $id . "'";
		if(count($statusArray) > 0 && $status != "") {
			$q .= " and (";
			for($i=0;$i<count($statusArray);$i++) {
				$q .= "STATUS = '".$statusArray[$i]."'"; 
				if($i<count($statusArray)-1) {
					$q .= " or ";
				}
			}
			$q .= ")";
		}
		$q .= " order by SEND_START asc";
	
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		} 
		return $data;
			
	}
	public function orderByRep($id = null, $status = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		$dataAux = array();
		$data = array();
		if($status < 3 && $status != -1 && $status != "") {
			return $data;
		}
		$q = "select ".preBD."user_web_supplier_assoc.IDSUPPLIER 
				from ".preBD."user_web_supplier_assoc 
				inner join ".preBD."suppliers on ".preBD."user_web_supplier_assoc.IDSUPPLIER = ".preBD."suppliers.ID
				where true 
				and (".preBD."suppliers.STATUS = 1 or ".preBD."suppliers.STATUS = 2)
				and ".preBD."user_web_supplier_assoc.IDUSER = " . $id;
		$r = checkingQuery($connectBD, $q);
		$sup = array();
		while($row = mysqli_fetch_object($r)) {
			$sup[]=$row->IDSUPPLIER;
		}
		if(count($sup) == 0) {
			return $data;
		}
		$q = "select ".preBD."order.*
			from ".preBD."order where true";
		if($status == -1) {
			$q .= " and IDREPARTIDOR = '0' and STATUS = 3";
		}else{
			$q .= " and IDREPARTIDOR = '" . $id . "'";
			if(count($statusArray) > 0 && $status != "") {
				$q .= " and (";
				for($i=0;$i<count($statusArray);$i++) {
					$q .= "STATUS = '".$statusArray[$i]."'"; 
					if($i<count($statusArray)-1) {
						$q .= " or ";
					}
				}
				$q .= ")";
			}
		}
		if($status != "") {
			$q .= " and (";
			for($i=0;$i<count($sup);$i++) {
				$q .= "IDSUPPLIER = " . $sup[$i];
				if($i<count($sup)-1) {
					$q .= " or ";
				}
			}
			$q .= ")";
		}
		$q.=" order by DATE_CREATE desc";
		$r = checkingQuery($connectBD, $q);
		
		while($row = mysqli_fetch_object($r)) {
			$dataAux[]=$row;
		} 
		for($i=0;$i< count($dataAux);$i++) {
			if($dataAux[$i]->DATE_START != "0000-00-00 00:00:00") {
				$date = new DateTime($dataAux[$i]->DATE_START);
			}else {
				$date = new DateTime($dataAux[$i]->DATE_CREATE);
			}
			$timeHome = ($item->TIMESUPPLIER + $item->TIMEREPARTIDOR) * 60;//pasamos a segundos
			$segs = intval($date->getTimestamp()) + intval($timeHome);
			$dataAux[$i]->timeline = intval($date->setTimestamp($segs));
			
		}
		foreach($dataAux as $item) {
			$enc = false;
			$ind = 0;
			for($i=0;$i<count($data);$i++) {
				if($item->timeline < $data[$i]->timeline) {
					$enc = true;
					$ind = $i;
					break;
				}
			}
			if($enc) {
				for($j=count($data)-1;$j>=$ind;$j--) {
					$data[$j+1] = $data[$j];
				}
				$data[$ind] = $item;
			}else{
				$data[count($data)] = $item;
			}
		}
		return $data;
			
	}
	public function orderByRepList1($idUser = null) {//pedidos pendientes del repartidor agrupados por zona
		global $connectBD;
		$data = array();
		$q = "select ".preBD."user_web_supplier_assoc.IDSUPPLIER 
				from ".preBD."user_web_supplier_assoc 
				inner join ".preBD."suppliers on ".preBD."user_web_supplier_assoc.IDSUPPLIER = ".preBD."suppliers.ID
				where true 
				and (".preBD."suppliers.STATUS = 1 or ".preBD."suppliers.STATUS = 2)
				and ".preBD."user_web_supplier_assoc.IDUSER = " . $idUser;
		$r = checkingQuery($connectBD, $q);
		$sup = array();
		while($row = mysqli_fetch_object($r)) {
			$sup[]=$row->IDSUPPLIER;
		}
		if(count($sup) == 0) {
			return $data;
		}
		//los agrupamos por zona
		$aux = array();
		foreach($sup as $item){
			$q = "select * from ".preBD."user_sup_web_address where true and TYPE = 'proveedor' and IDASSOC = " . $item;
			$r = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_object($r);
			$aux[$row->IDZONE][] = $item;
		}
		
		foreach($aux as $keyZone => $zone) {
			foreach($zone as $sup){
				$q = "select ".preBD."order.*
						from ".preBD."order where true
						and ((IDREPARTIDOR = '0' and STATUS = 2)
						or (IDREPARTIDOR = '" . $idUser . "' and STATUS = 3))
						and IDSUPPLIER = " . $sup;
			
				$r = checkingQuery($connectBD, $q);
				while($row = mysqli_fetch_object($r)) {
					$data[$keyZone][$sup][] = $row;
				}
			}
		}
		return $data;
	}
	public function orderByRepGroupByFranjas($idUser = null, $send_start = null, $send_finish = null) {
		global $connectBD;
		$data = array();
		
		$q = "select ".preBD."order.*
				from ".preBD."order where true
				and ((IDREPARTIDOR = '0' and STATUS = 2)
				or (IDREPARTIDOR = '" . $idUser . "' and STATUS = 3))
				and SEND_START >= '" . $send_start. "'
				and SEND_FINISH <= '" . $send_finish. "'";
	
		$r = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		return $data;
	}
	public function orderByRepGroupByFranjasZones($idUser = null, $idZone = null, $send_start = null, $send_finish = null) {
			global $connectBD;
			$data = array();
			
			$q = "select ".preBD."order.*
					from ".preBD."order where true
					and ((IDREPARTIDOR = '0' and STATUS = 2)
					or (IDREPARTIDOR = '" . $idUser . "' and STATUS = 3))
					and IDZONE = '".$idZone."'
					and SEND_START >= '" . $send_start. "'
					and SEND_FINISH <= '" . $send_finish. "'";
		
			$r = checkingQuery($connectBD, $q);
			while($row = mysqli_fetch_object($r)) {
				$data[] = $row;
			}
			return $data;
		}

	public function orderFollow($idUser) {
		global $connectBD;
		
		$now = new DateTime();
		$q = "select * from ".preBD."order where true and (STATUS = 2 or STATUS = 3 or STATUS = 4 or STATUS = 5)
				and IDUSER = " . $idUser ."
				and DAY(DATE_CREATE) = ".$now->format("d")." 
				and MONTH(DATE_CREATE) = ".$now->format("m")."
				and YEAR(DATE_CREATE) = ".$now->format("Y");
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		
		return $data;
	}
	public function newRef() {
		global $connectBD;
		$q = "select * from ".preBD."order where true order by REF desc limit 0,1";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data->REF + 1;
		} else {
			return 1000000;
		}
		
	}
	public function infoStatusOrder($status = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."order_status where true and ID = '" . $status . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoOrderById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."order where true and ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoOrderByRef($ref = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."order where true and REF = '" . $ref . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function listProductOrder($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."order_product.*
				from ".preBD."order_product 
				where true and ".preBD."order_product.IDORDER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		return $data;
	}
	public function infoProductOrderByIdAssoc($id = null) {
		global $connectBD;
		
		$q = "select 
				".preBD."products.TITLE,
				".preBD."order_product.UDS,
				".preBD."order_product.COST,
				".preBD."order_product.IDCOM
				from ".preBD."order_product 
				inner join ".preBD."products on ".preBD."products.ID = ".preBD."order_product.IDPRODUCT
				where true 
				and ".preBD."order_product.ID = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		if($data->IDCOM != "") {
			$comString = "";
			$comps = explode("#-#", $data->IDCOM);
			$proObj = new Product();
			for($i=0;$i<count($comps);$i++) {
				$com = $proObj->infoProductCompByIdAssoc($comps[$i]);
				if($i >= 0 && $i < count($comps)){
					$data->TITLE .=  " + ";
				}
				$data->TITLE .=  $com->TITLE;
			}
		}	
		return $data;
	}
	public function listProductOrderEdit($id = null) {
		global $connectBD;
		
		$q = "select ".preBD."order_product.*
				from ".preBD."order_product 
				where true and ".preBD."order_product.TYPE = 'edit' 
				and ".preBD."order_product.IDORDER = " . $id;
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		return $data;
	}
	
	public function orderMethodPay($idM = null) {
		global $connectBD;
		
		$q = "select ".preBD."order_method_pay.*
				from ".preBD."order_method_pay 
				where true and ".preBD."order_method_pay.ID = " . $idM;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		
		return $data;
	}
	public function orderByStatus($status = null) {
		global $connectBD;
		
		$q = "select ".preBD."order.*
				from ".preBD."order 
				where true and ".preBD."order.STATUS = " . $status . " order by DATE_CREATE asc";
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)){
			$data[] = $row;
		}
		return $data;
	}
	
	public function orderAddress($idA = null) {
		global $connectBD;
		
		$q = "select 
				".preBD."user_sup_web_address.STREET,
				".preBD."zone.ID as IDZONE,
				".preBD."zone.CITY,
				".preBD."zone.CP,
				".preBD."zone.PROVINCE,
				".preBD."zone.COUNTRY
				from ".preBD."user_sup_web_address 
				inner join ".preBD."zone on ".preBD."zone.ID = ".preBD."user_sup_web_address.IDZONE
				where true and ".preBD."user_sup_web_address.ID = " . $idA;
		$r = checkingQuery($connectBD, $q);
		
		$data = mysqli_fetch_object($r);
		
		return $data;
	}
	public function checkViewOrder($idUser = null, $idOrder = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."order where true and ID = '" . $idOrder . "'";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			
			if($data->IDUSER == $idUser || $this->checkingProveedorOrder($idUser, $data->IDSUPPLIER) || $data->IDREPARTIDOR == $idUser) {
				return true;
			}else{
				return false;
			}
		} else {
			return false;
		}
	}
	public function checkViewOrderZone($user = null, $order = null, $idZone = null) {
		global $connectBD;

		if($user->IDTYPE == 5){
			$q = "select IDZONE from ".preBD."user_sup_web_address where true and IDASSOC = ".$order->IDSUPPLIER." and TYPE = 'proveedor'";
			$r = checkingQuery($connectBD, $q);
			$address = mysqli_fetch_object($r);

			$q = "select ".preBD."user_web_zone_assoc.IDUSER
					from ".preBD."user_web_zone_assoc 
					where ".preBD."user_web_zone_assoc.IDZONE = " . $address->IDZONE. " and IDUSER = ".$user->ID;
			$r = checkingQuery($connectBD, $q);
			$total = mysqli_num_rows($r);
		}else {
			$total = 0;
		}

		if($total > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function checkingProveedorOrder($idUser = null, $idSup = null) {
		global $connectBD;
		
		$q = "select 
				".preBD."user_web_supplier_assoc.ID
				from ".preBD."user_web_supplier_assoc 
				where true 
				and ".preBD."user_web_supplier_assoc.IDUSER = " . $idUser . " 
				and ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $idSup . " 
				and TYPE = 'proveedor'";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		if($total > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function checkingRepartidorOrder($idUser = null, $idSup = null) {
		global $connectBD;
		
		$q = "select 
				".preBD."user_web_supplier_assoc.ID
				from ".preBD."user_web_supplier_assoc 
				where true 
				and ".preBD."user_web_supplier_assoc.IDUSER = " . $idUser . " 
				and ".preBD."user_web_supplier_assoc.IDSUPPLIER = " . $idSup . " 
				and TYPE = 'repartidor'";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
	
		if($total > 0) {
			return true;
		}else{
			return false;
		}
	}
	public function listStatusOrder() {
		global $connectBD;
		
		$q = "select * from ".preBD."order_status where true order by ID asc";
		$r = checkingQuery($connectBD, $q);
		
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[] = $row;
		}
		return $data;
	}
	
	
	public function infoOrderByFilterDay($date, $filter, $Suppliers) {
		global $connectBD;
		$sup = $this->infoSupplierOrderDay($date, $filter, $Suppliers);
		
		$aux = explode("-", $date);
		if($filter == "day") {
			$yearq = " and YEAR(DATE_CREATE) = " . $aux[0];
			$monthq = " and MONTH(DATE_CREATE) = " . $aux[1];
			$dayq = " and DAY(DATE_CREATE) = " . $aux[2];
		}
		for($i=0;$i<count($sup);$i++) {
			$q = "select ".preBD."order.* from ".preBD."order 
					where true 
					and IDSUPPLIER = ".$sup[$i]["data"]->ID." 
					and STATUS = 6
					".$yearq.$monthq.$dayq . " 
					order by DATE_CREATE asc";
			
			$r = checkingQuery($connectBD, $q);
			$j=0;
			while($row = mysqli_fetch_object($r)) {
				$sup[$i]["order"][$j]["data"]=$row;
				 
				$qp = "select 
						".preBD."order_product.IDPRODUCT as id, 
						".preBD."order_product.UDS as uds, 
						".preBD."order_product.COST as cost, 
						".preBD."products.TITLE as title 
						from ".preBD."order_product 
						inner join ".preBD."products on ".preBD."products.ID = ".preBD."order_product.IDPRODUCT
						where true and ".preBD."order_product.IDORDER = " . $row->ID . "
						order by ".preBD."order_product.TYPE desc, ".preBD."order_product.ID asc";
				$rp = checkingQuery($connectBD, $qp);
				$z = 0;
				while($rowp = mysqli_fetch_array($rp)) {
					$sup[$i]["order"][$j]["product"][$z]=$rowp;
					$z++;
				}
				$j++;
			}
		}
		return $sup;
	}
	
	public function infoSupplierOrderDay($date, $filter, $Suppliers) {
		global $connectBD;
		$yearq = "";
		$monthq = "";
		$dayq = "";
		$supplierq = "";
		
		$aux = explode("-", $date);
		if($filter == "day") {
			$yearq = " and YEAR(DATE_CREATE) = " . $aux[0];
			$monthq = " and MONTH(DATE_CREATE) = " . $aux[1];
			$dayq = " and DAY(DATE_CREATE) = " . $aux[2];
		}
		
		if(count($Suppliers) > 0) {
			$supplierq = "and (";
			for($i=0;$i<count($Suppliers);$i++) {
				$supplierq .= preBD."order.IDSUPPLIER = " . $Suppliers[$i]->ID;
				if($i < count($Suppliers)-1) {
					$supplierq .= " or ";
				}
			}
			$supplierq .= ")";
		}
		
		$q = "select ".preBD."suppliers.* from ".preBD."order 
			inner join ".preBD."suppliers on ".preBD."suppliers.ID = ".preBD."order.IDSUPPLIER
			where true 
			and ".preBD."order.STATUS = 6
			" .$supplierq.$yearq . $monthq . $dayq . " 
			group by ".preBD."order.IDSUPPLIER
			order by ".preBD."suppliers.TITLE asc";
		
		$r = checkingQuery($connectBD, $q);
		$data = array();
		$i=0;
		while($row = mysqli_fetch_object($r)) {
			$data[$i]["data"]=$row;
			$i++;
		}
		return $data;
	} 
	public function infoOrderByFilterRango($dateStart, $dateFinish, $filter, $Suppliers) {
		global $connectBD;
		$sup = $this->infoSupplierOrderRango($dateStart, $dateFinish, $filter, $Suppliers);
		$dateq = "";
		
		if($filter == "day") {
			$dateS = new DateTime($dateStart);
			$dateF = new DateTime($dateFinish);
			$dateq .= " and DATE_CREATE BETWEEN '" . $dateS->format('Y-m-d 01:00:00') . "' and '" . $dateF->format('Y-m-d 23:59:59') . "'";
		}
		for($i=0;$i<count($sup);$i++) {
			$q = "select ".preBD."order.* from ".preBD."order 
					where true 
					and IDSUPPLIER = ".$sup[$i]["data"]->ID." 
					and STATUS = 6
					".$dateq . " 
					order by DATE_CREATE asc";
			
			$r = checkingQuery($connectBD, $q);
			$j=0;
			while($row = mysqli_fetch_object($r)) {
				$sup[$i]["order"][$j]["data"]=$row;
				 
				$qp = "select 
						".preBD."order_product.IDPRODUCT as id, 
						".preBD."order_product.UDS as uds, 
						".preBD."order_product.COST as cost, 
						".preBD."products.TITLE as title 
						from ".preBD."order_product 
						inner join ".preBD."products on ".preBD."products.ID = ".preBD."order_product.IDPRODUCT
						where true and ".preBD."order_product.IDORDER = " . $row->ID . "
						order by ".preBD."order_product.TYPE desc, ".preBD."order_product.ID asc";
				$rp = checkingQuery($connectBD, $qp);
				$z = 0;
				while($rowp = mysqli_fetch_array($rp)) {
					$sup[$i]["order"][$j]["product"][$z]=$rowp;
					$z++;
				}
				$j++;
			}
		}
		return $sup;
	}
	public function infoOrderZoneByFilterRango($dateStart, $dateFinish, $filter, $Suppliers, $idZone) {
		global $connectBD;
		$sup = $this->infoSupplierOrderRango($dateStart, $dateFinish, $filter, $Suppliers);
		$dateq = "";
		
		if($filter == "day") {
			$dateS = new DateTime($dateStart);
			$dateF = new DateTime($dateFinish);
			$dateq .= " and ".preBD."order.DATE_CREATE BETWEEN '" . $dateS->format('Y-m-d 01:00:00') . "' and '" . $dateF->format('Y-m-d 23:59:59') . "'";
		}
		for($i=0;$i<count($sup);$i++) {
			$q = "select ".preBD."order.* from ".preBD."order 
					where true 
					and ".preBD."order.IDZONE = ".$idZone."
					and ".preBD."order.IDSUPPLIER = ".$sup[$i]["data"]->ID." 
					and ".preBD."order.STATUS = 6
					".$dateq . " 
					order by ".preBD."order.DATE_CREATE asc";
			
			$r = checkingQuery($connectBD, $q);
			$j=0;
			while($row = mysqli_fetch_object($r)) {
				$sup[$i]["order"][$j]["data"]=$row;
				 
				$qp = "select 
						".preBD."order_product.IDPRODUCT as id, 
						".preBD."order_product.UDS as uds, 
						".preBD."order_product.COST as cost, 
						".preBD."products.TITLE as title 
						from ".preBD."order_product 
						inner join ".preBD."products on ".preBD."products.ID = ".preBD."order_product.IDPRODUCT
						where true and ".preBD."order_product.IDORDER = " . $row->ID . "
						order by ".preBD."order_product.TYPE desc, ".preBD."order_product.ID asc";
				$rp = checkingQuery($connectBD, $qp);
				$z = 0;
				while($rowp = mysqli_fetch_array($rp)) {
					$sup[$i]["order"][$j]["product"][$z]=$rowp;
					$z++;
				}
				$j++;
			}
		}
		return $sup;
	}
	public function infoSupplierOrderRango($dateStart, $dateFinish, $filter, $Suppliers) {
		global $connectBD;
		
		$dateq = "";
		$supplierq = "";
		
		if($filter == "day") {
			$dateS = new DateTime($dateStart);
			$dateF = new DateTime($dateFinish);
			$dateq .= " and DATE_CREATE BETWEEN '" . $dateS->format('Y-m-d 01:00:00') . "' and '" . $dateF->format('Y-m-d 23:59:59') . "'";
		}
		
		if(count($Suppliers) > 0) {
			$supplierq = "and (";
			for($i=0;$i<count($Suppliers);$i++) {
				$supplierq .= preBD."order.IDSUPPLIER = " . $Suppliers[$i]->ID;
				if($i < count($Suppliers)-1) {
					$supplierq .= " or ";
				}
			}
			$supplierq .= ")";
		}
		
		$q = "select ".preBD."suppliers.* from ".preBD."order 
			inner join ".preBD."suppliers on ".preBD."suppliers.ID = ".preBD."order.IDSUPPLIER
			where true 
			and ".preBD."order.STATUS = 6
			" .$supplierq.$dateq . " 
			group by ".preBD."order.IDSUPPLIER
			order by ".preBD."suppliers.TITLE asc";
	
		$r = checkingQuery($connectBD, $q);
		$data = array();
		$i=0;
		while($row = mysqli_fetch_object($r)) {
			$data[$i]["data"]=$row;
			$i++;
		}
		return $data;
	} 
	public function chekingOrderFranja($start, $finish, $max, $idZone) { 
		global $connectBD;

		$now = new DateTime();
		$q = "select ".preBD."order.* from ".preBD."order 
				inner join ".preBD."user_sup_web_address 
					on ".preBD."user_sup_web_address.ID = ".preBD."order.IDADDRESS 
					and ".preBD."user_sup_web_address.IDZONE = ".$idZone."
				where true 
				and ".preBD."order.SEND_START >= '". $start->format('Y-m-d H:i:s'). "'
				and ".preBD."order.SEND_FINISH <= '". $finish->format('Y-m-d H:i:s'). "' 
				and ".preBD."order.STATUS >= 2 and ".preBD."order.STATUS < 6";
		
		$r = checkingQuery($connectBD, $q);

		$total = mysqli_num_rows($r);
		
		if($total < $max) {
			return true;
		} else {
			return false;
		}
	}
	public function infoOrderByRepThreeDay($dateStart, $dateEnd, $idRep) {
		global $connectBD;
		
		$q = "select 
		".preBD."order.REF, 
		".preBD."order.SUBTOTAL, 
		".preBD."order.SHIPPING, 
		".preBD."order.IDMETHODPAY, 
		".preBD."order.COST, 
		".preBD."suppliers.TITLE 
		from ".preBD."order 
		inner join ".preBD."suppliers on ".preBD."suppliers.ID = ".preBD."order.IDSUPPLIER
		where true 
		and ".preBD."order.STATUS = 6
		and ".preBD."order.DATE_CREATE >= '".$dateStart->format("Y-m-d H:i:s")."'   
		and ".preBD."order.DATE_CREATE <= '".$dateEnd->format("Y-m-d H:i:s")."'   
		and ".preBD."order.IDREPARTIDOR = '".$idRep."'   
		order by ".preBD."order.DATE_CREATE asc";
		
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]["data"]=$row;
		}
		return $data;
		
	}
	public function repartidoresDay($filter, $idZone) {
		global $connectBD;
		$aux = explode("-", $filter);
		
		$dateq = " and YEAR(".preBD."order.DATE_CREATE) = " . $aux[0];
		$dateq .= " and MONTH(".preBD."order.DATE_CREATE) = " . $aux[1];
		$dateq .= " and DAY(".preBD."order.DATE_CREATE) = " . $aux[2];
		
		$q = "select DISTINCT ".preBD."order.IDREPARTIDOR
				FROM ".preBD."order 
				inner join ".preBD."user_sup_web_address 
				on ".preBD."user_sup_web_address.ID = ".preBD."order.IDADDRESS and ".preBD."user_sup_web_address.IDZONE = " . $idZone . "
				where true 
				and STATUS = 6" . $dateq;
		
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row->IDREPARTIDOR;
		}
		return $data;
	}
	public function calculateTotalOrders($orders) {
		$total = 0;
		$totalTPV = 0;
		$totalCash = 0;
		foreach($orders as $item) {
			if($item['data']->IDMETHODPAY == 1) {
				$totalCash = $totalCash + $item['data']->COST;
				$total = $total + $item['data']->COST;
			}else if($item['data']->IDMETHODPAY == 2 || $item['data']->IDMETHODPAY == 3) {
				$totalTPV = $totalTPV + $item['data']->COST;
			}
		}
		$totals = array();
		$totals["cash"] = $totalCash;
		$totals["tpv"] = $totalTPV;
		$totals["total"] = $total;
		return  $totals;

	}
	public function listMethodPay() {
		global $connectBD;	
		$q = "select * from ".preBD."order_method_pay where ACTIVE = 1 order by ID asc";
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		}
		return $data;
	}
	public function infoTpvResponse($ref = null) {
		global $connectBD;
		$q="select * from ".preBD."tpv_record where REFERENCE = " . $ref;
		$r = checkingQuery($connectBD, $q);

		$row = mysqli_fetch_object($r);
		$data = array();
		$data["tpv"] = $row;
		if($row->RESPONSE >= 100 && $row->RESPONSE != 400 && $row->RESPONSE != 900) {
			$q = "select * from ".preBD."tpv_error where CODE = " . $row->RESPONSE;
			$re = checkingQuery($connectBD, $q);
			if($rowError = mysqli_fetch_object($re)) {
				$data["error"] = $rowError->DESCRIPTION;
			}else{
				$data["error"] = "Error desconocido";
			}
		}else{
			$data["error"] = "";
		}

		return $data;
	}
	public function infoLastOrderByUser($idUser = null, $idSupplier = null, $idZone = null) {
		global $connectBD;
		
		$q = "select ".preBD."order.* from ".preBD."order 
				inner join ".preBD."user_sup_web_address
				on ".preBD."user_sup_web_address.ID = ".preBD."order.IDADDRESS and ".preBD."user_sup_web_address.IDZONE = ".$idZone."
				where true 
				and ".preBD."order.IDUSER = '" . $idUser . "' 
				and ".preBD."order.IDSUPPLIER = " . $idSupplier. "
				and (".preBD."order.IDMETHODPAY = 2 or ".preBD."order.IDMETHODPAY = 3)
				and ".preBD."order.STATUS = 1 order by DATE_CREATE desc limit 0,1";
		$r = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($r)) {
			return $data;
		} else {
			return false;
		}
	}
	public function deleteOrder($idOrder) {
		global $connectBD;
		
		$q = "DELETE FROM `".preBD."order_staus_time` WHERE IDORDER = " . $idOrder;
		checkingQuery($connectBD, $q);
		$q = "DELETE FROM `".preBD."order_product` WHERE IDORDER = " . $idOrder;
		checkingQuery($connectBD, $q);
		$q = "DELETE FROM `".preBD."order` WHERE ID = " . $idOrder;
		checkingQuery($connectBD, $q);
		
		return true;
		
	}
}