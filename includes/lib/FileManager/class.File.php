<?php
require_once "lib/Util/class.Util.php";

class File {
	
	protected $file;
	protected $size = 0;
	protected $name;
	
	public function __construct() {
	}
	
	public function setFile($file) {
		$this->file = $file;
		if (file_exists($this->file)) {
			$this->size = filesize($this->file);
		}
	}
	
	public function setName($name) {
		$this->name = Util::cleanName($name);
	}
	
	public function getFile() {
		return $this->file;
	}
	
	public function getSize() {
		return $this->size;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function move($to) {
		if ($this->size > 0) {
			$result = copy($this->file, $to . "/" . $this->name);
			//$result = copy($this->getFile(), $to . "/" . $this->getName());
			return $result;
			/*
			En lugar del return puedo lanzar un error fatal
			porque no haya podido escribir en $to.
			*/
		} else {
			trigger_error("No existe ningún archivo", E_USER_ERROR);
		}
	}
	
	public function delete() {
		if ($this->size > 0) {
			return unlink($this->file);
		} else {
			return false;
		}
	}
	
}














?>