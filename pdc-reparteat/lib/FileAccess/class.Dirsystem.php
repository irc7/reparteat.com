<?php

Class Dirsystem {
	
	public $path_info;
	public $handler;
	public $dircontent = array();
	
	/**
	 * Stablish a set of items to ignore when walking through a directory
	 */
	public $ignoreDir = array(
				'.',
				'..',
				'.svn',
				'_svn'
	);
	
	public function __construct() {
	}
	
	public function isIgnoreDir($dir) {
		return in_array($dir, $this->ignoreDir);
	}
	
	public function Open($path) {
		$this->handler = opendir($path);
	}
	
	public function Close() {
		$h = $this->handler;
		if ($h) {
			closedir($h);
		}
	}
	
	public function isEmpty($dir) {
		$dir = str_replace("//", "/", $dir);
		if (count(scandir($dir)) == 2) {
			return true;
		} else {
			return false;
		}
	}
	
	public function create($dir) {
		if (!$this->Exists($dir)) {
			$oldumask = umask(0);
			mkdir($dir, 0777);
			umask($oldumask);
			return true;
		} else {
			return false;
		}
	}
	
	public function delete($dir) {
		if ($this->Exists($dir) && $this->isEmpty($dir)) {
			return rmdir($dir);
		} else {
			return false;
		}
	}
	
	function deltree($path) {
		if (is_dir($path)) {
			$entries = scandir($path);
			foreach ($entries as $entry) {
				if ($entry != '.' && $entry != '..') {
					$this->deltree($path . "/" . $entry);
				}
			}
			rmdir($path);
		} else {
			unlink($path);
		}
	}
	
	/**
	 * read $path contents and store on $dircontent
	 * mode (files, directories, all)
	 */
	public function ReadAndStore($path, $mode = 'all') {
		$d = dir($path);
		while (false !== ($item = $d->read())) {
			if (!$this->isIgnoreDir($item)) {
				switch ($mode) {
					case 'files':
					case 'f':
						if (is_file($path . $item)) {
							$this->dircontent[] = $item;
						}
						break;
						
					case 'directories':
					case 'd':
						if (is_dir($path . $item)) {
							$this->dircontent[] = $item;
						}
						break;
						
					case 'all':
					default:
						$this->dircontent[] = $item;
						break;
				}
			}
		}
		$d->close();
	}
	
	public function getContents() {
		return $this->dircontent;
	}
	
	/**
	 * returns true if $dirname is a directory
	 */
	public function Exists($dirname) {
		return is_dir($dirname);
	}
	
	public function DisplayInfo($path) {
		echo "<pre>";
		print_r(pathinfo($path));
		echo "</pre>";
	}
	
	public function GetInfo($path) {
		$this->path_info = pathinfo($path);
	}
	
	/**
	 * returns absolute absolute path of $path
	 * @param String $path
	 * @return String
	 */
	public function GetAbsolutePath($path) {
		return realpath($path);
	}
	
	/**
	 * returns path to the last element on $path
	 */
	public function GetPathTo($path) {
		return dirname($path);
	}
	
	public function count() {
		return count($this->dircontent);
	}
	
	public function flush() {
		$this->dircontent = null;
	}
	
	/**
	 * returns part of the $path corresponding to file.
	 * If there's no file returns the last directory of $path
	 */
	public function GetFileName($path) {
		return basename($path);
	}
	
//
}

?>