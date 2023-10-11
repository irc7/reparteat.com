<?php

abstract class FileManager {
	public static function get($classname) {
		$d = dirname(__FILE__);
		if (file_exists($d . "/class." . $classname . ".php")) {
			require_once ($d . "/class." . $classname . ".php");
			return new $classname;
		} else {
			trigger_error("No puedo manejar objetos de tipo " . $classname, E_USER_ERROR);
		}
	}
}

?>