<?php
require_once ("lib/FileManager/class.File.php");

class PDF extends File {
	
	private $allowedTypes = array("application/pdf", "application/pdf; charset=binary", "application/pdf; charset=utf-8", "PDF Document");
	
	public function __construct() {
	}
	
	public function setFile($file) {
		parent::setFile($file);
		if ($this->size > 0) {
			$finfo = new finfo(FILEINFO_MIME);
			$this->type = $finfo->file($this->file);
			if (!in_array($this->type, $this->allowedTypes)) {
				trigger_error("Tipo no soportado: " . $this->type, E_USER_ERROR);
			}
		}
	}
	
	public function display() {
		header("Content-type: application/pdf");
		echo file_get_contents($this->file);
		die();
	}
	
	public function download($name) {
		$file = file_get_contents($this->file);
		header("Content-Description: File Transfer");
		header("Content-Type: application/pdf");
		header("Content-Disposition: attachment; filename=\"". $name ."\"");
		header("Content-Transfer-Encoding: binary");
		header("Connection: Keep-Alive");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		header("Content-Length: " . $this->getSize());
		echo $file;
		die();
	}
	
}

?>