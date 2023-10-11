<?php

Class FileAccess {
	
	private $file;
	
	public function __construct($archivo) {
		if (file_exists($archivo)) {
			$this->file = $archivo;
		} else {
			$this->file = null;
		}
	}
	
	public function read() {
		if ($f = $this->open()) {
			$text = "";
			while (!feof($f)) {
				$text .= fgets($f);
			}
			$this->close($f);
			return $text;
		} else {
			return false;
		}
	}
	
	public function write($texto) {
		$f = $this->open("w");
		fwrite($f, $texto);
		$this->close($f);
	}
	
	private function open($mode = "r") {
		if ($this->file) {
			return fopen($this->file, $mode);
		} else {
			return false;
		}
	}
	
	private function close($f) {
		fclose($f);
	}
//	
}

?>