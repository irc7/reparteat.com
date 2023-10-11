<?php

/*
Author: irodriguez@aomcomunicacion.com
Date: 2013-07-04
*/

abstract class System {
	
	protected $total = 0;
	
	public function __set($k, $v) {
		$this->$k = $v;
	}
	
	public function __get($k) {
		return $this->$k;
	}
	
	public function getTotal() {
		return $this->total;
	}
	
	/*
	public function listData($q) {
		$res = checkingQuery($connectBD, $q);
		$this->total = mysqli_num_rows($res);
		$data = array();
		while ($rowObj = mysqli_fetch_object($res)) {
			$data[] = $rowObj;
		}
		return $data;
	}
	*/
	
}