<?php
/*
Author: info@ismaelrc.es
Date: 2022-10-04

Usuario
*/

class MultislideHook extends System {
	
	protected $id;
	protected $title;
	protected $width;
	protected $width_m;
	protected $height;
	protected $height_m;
	protected $pause;
	protected $speed;
	
	
	public function __construct() {
	}
	
	public function listHook() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."multislide_hook where true";
		$r = checkingQuery($connectBD, $q);
		
		$cats = array();
		while($row = mysqli_fetch_object($r)) {
			$cats[] = $row;
		}
		return $cats;
	}
	
	public function totalHook() {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."multislide_hook where true";
		$r = checkingQuery($connectBD, $q);
		
		$total = mysqli_num_rows($r);
		
		return $total;
	}
	
	public function infoHookById($id = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."multislide_hook where true and ID = '" . $id . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
	public function infoHookByIdChar($idchar = null) {
		global $connectBD;
		
		$q = "select * from ".preBD."multislide_hook where true and IDCHAR = '" . $idchar . "'";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
}