<?php

abstract class Util {
	
	const URL_FRIENDLY_FULL_MODE = true;
	
	public static function pre($s) {
		echo "<pre style='background: #fff; border: 1px solid #c0c0c0; padding: 10px;
				-moz-box-shadow: 1px 1px 5px 1px #ccc;
				-webkit-box-shadow: 1px 1px 5px 1px #ccc;
				box-shadow: 1px 1px 5px 1px #ccc;
				font-size: 20px;
				color: #535353;
				'>";
		print_r($s);
		echo "</pre>";
	}
	
	public static function cript($string) {
		return sha1($string);
	}
	
	public static function randomText($amount = 5) {
		$c = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$t = "";
		for ($i = 1; $i <= $amount; $i++) {
			$n = rand(0, strlen($c) - 1);
			$t .= $c[$n];
		}
		return $t;
	}
	
	public static function cleanName($str, $remove_dots = false, $insertTimestamp = true) {
		$str = utf8_decode($str);
		$str = trim($str);
		$str = strtolower($str);
		$str = str_replace(" ", "-", $str);
		$str = str_replace("/", "-", $str);
		$str = str_replace("+", "", $str);
		$str = str_replace("*", "", $str);
		$str = str_replace("ß", "ss", $str);
		$str = strtr(
			$str,
			utf8_decode("áéíóúàèìòùäëïöüýâêîôûãñç"),
			"aeiouaeiouaeiouyaeiouanc"
		);
		if ($remove_dots) {
			$regexp = '/[^A-Za-z0-9\-]/';
		} else {
			$regexp = '/[^A-Za-z0-9\-.]/';
		}
		$str = preg_replace($regexp, '', $str);
		$str = str_replace("--", "", $str);
		
		if ($insertTimestamp) {
			return time() . "-" . $str;
		} else {
			return $str;
		}
	}
	
	/*
	URLFriendlyName always removes dots and never insert timestamp.
	
	fullmode allows removing single character parts of the string.
	*/
	public static function URLFriendlyName($str, $fullmode = Util::URL_FRIENDLY_FULL_MODE) {
		$friendly_name = Util::cleanName($str, true, false);
		
		if (!$fullmode) {
			$pattern = "[-a-|-e-|-i-|-o-|-u-|-y-]i";
			$friendly_name = preg_replace($pattern, "-", $friendly_name);
		}
		
		return $friendly_name;
	}
	
	public static function cut($text, $amount, $include_points = true) {
		if (strlen($text) > $amount) {
			$text = substr($text, 0, $amount);
			if ($include_points) {
				$text = $text . "...";
			}
		}
		return $text;
	}
	
	public static function getIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			//check ip from share internet
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	public function BytesTo($bytes, $m = "b") {
		switch ($m) {
			case "kb":
				return printf("%.1f", round(($bytes / 1024), 2));
				break;
			case "mb":
				return printf("%.1f", round(($bytes / (1024 * 1024)), 2));
				break;
			case "b":
				return printf("%.1f", $bytes);
				break;
		}
	}
	public function convertSize($bytes) {
		if ($bytes > (1024 * 1024)) {
			return Util::BytesTo($bytes, "mb") . " Mb";
		} else if ($bytes > 1024 && $bytes < (1024 * 1024)) {
			return Util::BytesTo($bytes, "kb") . " Kb";
		} else {
			return Util::BytesTo($bytes, "b") . " bytes";
		}
	}
	
	public function isValidEmail($email){
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email);
	}
	
	public static function getUserLanguage() { 
		$lang =substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
		return $lang;
	}
	
	public function prefix_thumb($name) {
		return "thumb-" . THUMB_WIDTH . "-" . $name;
	}
	
	public function arrayToString($a) {
		$t = count($a);
		$string = "(";
		for ($i = 0; $i < $t; $i++) {
			$string .= $a[$i];
			if ($i < $t - 1) {
				$string .= ",";
			}
		}
		$string .= ")";
		return $string;
	}
	
	/*
	Author: ismael@jumpersoluciones.com
	Date: 2013-07-29
	
	@param $link String
	@return $formatted_link String
	
	Returns a shortened link using suspension points in the middle of the link.
	*/
	public function format_link($link, $max = 30) {
		//return $link;
		$formatted_link = "";
		$t = strlen($link);
		if ($t > $max) {
			$part1 = substr($link, 0, $t / 4);
			
			$next = 3 * ceil($t / 4);
			$part2 = substr($link, $next);
			
			$formatted_link = $part1 . "..." . $part2;
		}
		return $formatted_link;
	}
	
	
//
}













