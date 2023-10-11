<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/
if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
}else {
	require_once "includes/classes/class.System.php";
}

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
		$q = "INSERT INTO `".preBD."order`(`REF`, `IDSUPPLIER`, `IDUSER`, `IDREPARTIDOR`, `IDADDRESS`, `IDZONE`, `IDMETHODPAY`, `DATE_CREATE`, `DATE_START`, `COMMENT`, `TIMESUPPLIER`, `TIMEREPARTIDOR`, `SUBTOTAL`, `SHIPPING`, `COST`, `STATUS`) 
		VALUES 
		('".$this->ref."','".$this->idsupplier."','".$this->iduser."','".$this->idrepartidor."','".$this->idaddress."','".$this->idzone."','".$this->idmethodpay."','".$this->date_create."','".$this->date_start."','".$this->comment."','".$this->timesupplier."','".$this->timerepartidor."','".$this->subtotal."','".$this->shipping."','".$this->cost."','".$this->status."')";
		
		if(checkingQuery($connectBD, $q)) {
			$idNew = mysqli_insert_id($connectBD);
			$this->id = $idNew;
			return $idNew;
		} else {
			return false;
		}
	}
	public function addProduct($idProduct = null, $uds = null, $cost = null, $idCom = null) {
		global $connectBD;
		
		$q = "INSERT INTO `".preBD."order_product`(`IDORDER`, `IDPRODUCT`, `UDS`, `COST`, `IDCOM`) 
				VALUES 
			('".$this->id."','".$idProduct."','".$uds."','".$cost."','".$idCom."')";
		if(checkingQuery($connectBD, $q)) {
			return true;
		} else {
			return false;
		}
	}
	public function updateStatus($id = null, $status = null, $statusOld = null) {
		global $connectBD;
		$q = "UPDATE `".preBD."order` SET `STATUS`='".$status."' WHERE true and ID = " . $id;
		checkingQuery($connectBD, $q);
		
		$now = new DateTime();
		$q = "INSERT INTO `".preBD."order_staus_time`(`IDORDER`, `IDSTATUS`, `IDSTATUSOLD`, `DATE_CHANGE`) 
		VALUES 
		('".$id."','".$status."','".$statusOld."','".$now->format("Y-m-d H:i:s")."')";
		
		checkingQuery($connectBD, $q);
		
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
	public function listOrder($status = null, $order = null, $firstrecord = null, $recordsperpage = null) {
		global $connectBD;
		
		$statusArray = array();
		$statusArray = explode("-", $status);
		
		$dataAux = array();
		$data = array();
		
		if($status < 3 && $status != -1 && $status != "") {
			return $data;
		}
		
		$q = "select ".preBD."order.*
			from ".preBD."order where true";
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
		$q.=" order by ".$order." desc LIMIT " . $firstrecord . ", " . $recordsperpage;
		$r = checkingQuery($connectBD, $q);
		
		while($row = mysqli_fetch_object($r)) {
			$dataAux[]=$row;
		} 
		/*
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
		*/
		return $dataAux;
			
	}
	public function followOrder() {
		global $connectBD;
		
		$q = "select ref as pedido, 
				date_create as fecha_pedido,
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
				where ".preBD."order.status <> 6 AND UNIX_TIMESTAMP(date_create) >= UNIX_TIMESTAMP(curdate())
				ORDER BY REF desc";
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)) {
			$data[]=$row;
		}
		return $data;
	}
	public function infoOrderByFilterDay($date, $filter) {
		global $connectBD;
		$sup = $this->infoSupplierOrderDay($date, $filter);
		
		$aux = explode("-", $date);
		if($filter == "day") {
			$yearq = " and YEAR(DATE_CREATE) = " . $aux[2];
			$monthq = " and MONTH(DATE_CREATE) = " . $aux[1];
			$dayq = " and DAY(DATE_CREATE) = " . $aux[0];
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
						where true and ".preBD."order_product.IDORDER = " . $row->ID;
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
	public function infoSupplierOrderDay($date, $filter) {
		global $connectBD;
		
		$aux = explode("-", $date);
		if($filter == "day") {
			$yearq = " and YEAR(DATE_CREATE) = " . $aux[2];
			$monthq = " and MONTH(DATE_CREATE) = " . $aux[1];
			$dayq = " and DAY(DATE_CREATE) = " . $aux[0];
		}
		
		$q = "select ".preBD."suppliers.* from ".preBD."order 
			inner join ".preBD."suppliers on ".preBD."suppliers.ID = ".preBD."order.IDSUPPLIER
			where true 
			and ".preBD."order.STATUS = 6
			" .$yearq . $monthq . $dayq . " 
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
			$q .= " and IDREPARTIDOR = '0'";
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
		$q .= " and (";
		for($i=0;$i<count($sup);$i++) {
			$q .= "IDSUPPLIER = " . $sup[$i];
			if($i<count($sup)-1) {
				$q .= " or ";
			}
		}
		$q .= ")";
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
	public function infoOrderStatusTime($idOrder = null) {
		global $connectBD;
		$q = "select ".preBD."order_staus_time.*,
				".preBD."order_status.TITLE,
				".preBD."order_status.ICON,
				".preBD."order_status.COLOR
				from ".preBD."order_staus_time 
				inner join ".preBD."order_status on ".preBD."order_status.ID = ".preBD."order_staus_time.IDSTATUS
				where true and ".preBD."order_staus_time.IDORDER = " . $idOrder;
		$q .= " order by ".preBD."order_staus_time.DATE_CHANGE desc";
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)){
			$data[] = $row;
		}
		return $data;
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
	public function insertRegMod($action = null, $ref = null, $idProductAssoc = null, $title = null, $cost = null, $idUser = null) {
		global $connectBD;
		$now = new DateTime();
		$q = "INSERT INTO `".preBD."order_change`(`DATE_CREATE`, `REF`,`IDPRODUCTASSOC`, `TITLE`, `ACTION`, `COST`, `IDUSER`, `TABLE_USER`) 
			VALUES 
			('".$now->format("Y-m-d H:i:s")."', '".$ref."','".$idProductAssoc."','".$title."', '".$action."', '".$cost."', '".$idUser."', 'user')";
		checkingQuery($connectBD, $q);
	}
	public function infoRegModByRef($ref = null) {
		global $connectBD;
		$q = "select ".preBD."order_change.*
		from ".preBD."order_change 
		where true and ".preBD."order_change.REF = '".$ref."' order by ".preBD."order_change.DATE_CREATE desc";
		$r = checkingQuery($connectBD, $q);
		$data = array();
		while($row = mysqli_fetch_object($r)){
			$data[] = $row;
		}
		return $data;
	}
	public function infoUserRegMod($idUser = null, $table) {
		global $connectBD;
		$q="select ";
		if($table=="user") {
			$q.= " ".preBD."users.Name as name, 
			".preBD."users.Login as email
			from ".preBD."users";
			$q .= " where ID = " . $idUser;
		}else if($table=="user_web") {
			$q.= " ".preBD."user_web.NAME as name, 
					".preBD."user_web.SURNAME as surname, 
					".preBD."user_web.LOGIN as email, 
					".preBD."user_web_typeuser.NAME as type
				from ".preBD."user_web
				inner join ".preBD."user_web_typeuser on ".preBD."user_web_typeuser.ID = ".preBD."user_web.IDTYPE";
			$q .= " where ".preBD."user_web.ID = " . $idUser;
		}
		$r = checkingQuery($connectBD, $q);
		$user = mysqli_fetch_object($r);

		$data = array();
		if(!$user) {
			$data["name"] = "No hay registro de usuario";
			$data["email"] = "";
			$data["type"] = "";
		}else{
			if($table=="user") {
				$data["name"] = $user->name;
				$data["email"] = $user->email;
				$data["type"] = "Administrador panel de control";
			}else if($table=="user_web") {
				$data["name"] = $user->name . " ".$user->surname;
				$data["email"] = $user->email;
				$data["type"] = $user->type;
			}
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
}