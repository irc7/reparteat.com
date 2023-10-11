<?php
/*
Author: info@ismaelrc.com
Date: 2019-08-26

Usuario
*/


if(strpos($_SERVER["SCRIPT_NAME"], "modules")) {
	require_once "../../includes/classes/class.System.php";
}else {
	require_once "includes/classes/class.System.php";
}


class Image extends System {
	
	protected $dirbase;
	protected $dirView;
	protected $dirbasename;
	protected $path;
	protected $pathoriginal;
	protected $pathresize;
	protected $paththumb;
	protected $files;
	protected $postName;
	protected $sizes;
	protected $widthbase;
	
	
	public function __construct() {
		$this->dirbase = "../../../files/";
		$this->dirView = "../files/";
		$this->dirbasename = "files/";
		$this->widthbase = 1366;
	}
	
	public function upload() {
		$pathImg = array();
		$pathImg['msg'] = "";
		$pathImg['image'] = "";
		if ($this->files[$this->postName]["error"] == 0) {
			
			preg_match("|\.([a-z0-9]{2,4})$|i", $this->files[$this->postName]["name"], $ext);
			
			$file = checkingExtFile($ext[1]);
			if($file["upload"] == 1){
				if (($this->files[$this->postName]["type"] == "image/gif" || $this->files[$this->postName]["type"] == "image/jpeg" || $this->files[$this->postName]["type"] == "image/pjpeg" || $this->files[$this->postName]["type"] == "image/png")) {
					$pathImg['image'] = formatNameFile($this->files[$this->postName]["name"]);
					$dirImg = $this->dirbase . $this->path . "/" . $this->pathoriginal . "/";
					move_uploaded_file($this->files[$this->postName]["tmp_name"],$dirImg.$pathImg['image']);
					$dirImgResize = $this->dirbase . $this->path ."/".$this->pathresize."/";
					resizeImage($dirImg, $dirImgResize, $pathImg['image'], $this->widthbase, $ext[1], $del);
					$dirImgThumb = $this->dirbase . $this->path ."/". $this->paththumb ."/";
					
					for($i=0;$i< count($this->sizes);$i++) {
						customImageClass($dirImg, $dirImgThumb, $i+1, $pathImg['image'], $ext[1], $this->sizes[$i]['width'], $this->sizes[$i]['height']);
					}
				}else {
					$pathImg['image'] = "";
					$pathImg['msg'] = "Imágen no válida.";
				}
			}else{
				$pathImg['image'] = "";
				$pathImg['msg'] = $file["msg"];
			}
		} else {
			$pathImg['image'] = "";
			$pathImg['msg']= "No ha seleccionado ningúna imagen.";
		}
		
		return $pathImg;
	
	}
	public function uploadPublicity($hooks) {
		$pathImg = array();
		$pathImg['msg'] = "";
		$pathImg['image'] = "";
		if ($this->files[$this->postName]["error"] == 0) {
			
			preg_match("|\.([a-z0-9]{2,4})$|i", $this->files[$this->postName]["name"], $ext);
			
			$file = checkingExtFile($ext[1]);
			if($file["upload"] == 1){
				if (($this->files[$this->postName]["type"] == "image/gif" || $this->files[$this->postName]["type"] == "image/jpeg" || $this->files[$this->postName]["type"] == "image/pjpeg" || $this->files[$this->postName]["type"] == "image/png")) {
					$pathImg['image'] = formatNameFile($this->files[$this->postName]["name"]);
					$dirImg = $this->dirbase . $this->path . "/" . $this->pathoriginal . "/";
					move_uploaded_file($this->files[$this->postName]["tmp_name"],$dirImg.$pathImg['image']);
					//$dirImgResize = $this->dirbase . $this->path ."/".$this->pathresize."/";
					//resizeImage($dirImg, $dirImgResize, $pathImg['image'], $this->widthbase, $ext[1], $del);
					$dirImgThumb = $this->dirbase . $this->path ."/". $this->paththumb ."/";
					
					for($i=0;$i< count($this->sizes);$i++) {
						customImageClass($dirImg, $dirImgThumb, $hooks[$i]->ID, $pathImg['image'], $ext[1], $this->sizes[$i]['width'], $this->sizes[$i]['height']);
					}
				}else {
					$pathImg['image'] = "";
					$pathImg['msg'] = "Imágen no válida.";
				}
			}else{
				$pathImg['image'] = "";
				$pathImg['msg'] = $file["msg"];
			}
		} else {
			$pathImg['image'] = "";
			$pathImg['msg']= "No ha seleccionado ningúna imagen.";
		}
		
		return $pathImg;
	
	}
	public function uploadPoints() {
		$pathImg = array();
		$pathImg['msg'] = "";
		$pathImg['image'] = "";
		if ($this->files[$this->postName]["error"] == 0) {
			
			preg_match("|\.([a-z0-9]{2,4})$|i", $this->files[$this->postName]["name"], $ext);
			
			$file = checkingExtFile($ext[1]);
			if($file["upload"] == 1){
				if (($this->files[$this->postName]["type"] == "image/gif" || $this->files[$this->postName]["type"] == "image/jpeg" || $this->files[$this->postName]["type"] == "image/pjpeg" || $this->files[$this->postName]["type"] == "image/png")) {
					$pathImg['image'] = formatNameFile($this->files[$this->postName]["name"]);
					$dirImg = $this->dirbase . $this->path . "/" . $this->pathoriginal . "/";
					move_uploaded_file($this->files[$this->postName]["tmp_name"],$dirImg.$pathImg['image']);
					//$dirImgResize = $this->dirbase . $this->path ."/".$this->pathresize."/";
					//resizeImage($dirImg, $dirImgResize, $pathImg['image'], $this->widthbase, $ext[1], $del);
					$dirImgThumb = $this->dirbase . $this->path ."/". $this->paththumb ."/";
					
					customImageClass($dirImg, $dirImgThumb, "", $pathImg['image'], $ext[1], $this->sizes[0]['width'], $this->sizes[0]['height']);
					
				}else {
					$pathImg['image'] = "";
					$pathImg['msg'] = "Imágen no válida.";
				}
			}else{
				$pathImg['image'] = "";
				$pathImg['msg'] = $file["msg"];
			}
		} else {
			$pathImg['image'] = "";
			$pathImg['msg']= "No ha seleccionado ningúna imagen.";
		}
		
		return $pathImg;
	
	}
	public function uploadMultislide($hooks) {
		$pathImg = array();
		$pathImg['msg'] = "";
		$pathImg['image'] = "";
		if ($this->files[$this->postName]["error"] == 0) {
			
			preg_match("|\.([a-z0-9]{2,4})$|i", $this->files[$this->postName]["name"], $ext);
			
			$file = checkingExtFile($ext[1]);
			if($file["upload"] == 1){
				if (($this->files[$this->postName]["type"] == "image/gif" || $this->files[$this->postName]["type"] == "image/jpeg" || $this->files[$this->postName]["type"] == "image/pjpeg" || $this->files[$this->postName]["type"] == "image/png")) {
					$pathImg['image'] = formatNameFile($this->files[$this->postName]["name"]);
					$dirImg = $this->dirbase . $this->path . "/" . $this->pathoriginal . "/";
					move_uploaded_file($this->files[$this->postName]["tmp_name"],$dirImg.$pathImg['image']);
					//$dirImgResize = $this->dirbase . $this->path ."/".$this->pathresize."/";
					//resizeImage($dirImg, $dirImgResize, $pathImg['image'], $this->widthbase, $ext[1], $del);
					$dirImgThumb = $this->dirbase . $this->path ."/". $this->paththumb ."/";
					
					for($i=0;$i< count($this->sizes);$i++) {
						customImageClass($dirImg, $dirImgThumb, $hooks[$i]->ID, $pathImg['image'], $ext[1], $this->sizes[$i]['width'], $this->sizes[$i]['height']);
					}
				}else {
					$pathImg['image'] = "";
					$pathImg['msg'] = "Imágen no válida.";
				}
			}else{
				$pathImg['image'] = "";
				$pathImg['msg'] = $file["msg"];
			}
		} else {
			$pathImg['image'] = "";
			$pathImg['msg']= "No ha seleccionado ningúna imagen.";
		}
		
		return $pathImg;
	
	}
	public function uploadMultiple() {
		$pathImg = array();
		
		$totalFiles = count($this->files[$this->postName]["error"]);
		
		for($z=0;$z<$totalFiles;$z++) {
			$pathImg[$z]['msg'] = "";
			$pathImg[$z]['image'] = "";
			if ($this->files[$this->postName]["error"][$z] == 0) {
				
				preg_match("|\.([a-z0-9]{2,4})$|i", $this->files[$this->postName]["name"][$z], $ext);
				
				$file = checkingExtFile($ext[1]);
				if($file["upload"] == 1){
					if (($this->files[$this->postName]["type"][$z] == "image/gif" || $this->files[$this->postName]["type"][$z] == "image/jpeg" || $this->files[$this->postName]["type"][$z] == "image/pjpeg" || $this->files[$this->postName]["type"][$z] == "image/png")) {
						$pathImg[$z]['image'] = formatNameFile($this->files[$this->postName]["name"][$z]);
						$dirImg = $this->dirbase . $this->path . "/" . $this->pathoriginal . "/";
						move_uploaded_file($this->files[$this->postName]["tmp_name"][$z],$dirImg.$pathImg[$z]['image']);
						$dirImgResize = $this->dirbase . $this->path ."/".$this->pathresize."/";
						resizeImage($dirImg, $dirImgResize, $pathImg[$z]['image'], $this->widthbase, $ext[1], $del);
						$dirImgThumb = $this->dirbase . $this->path ."/". $this->paththumb ."/";
						
						for($i=0;$i< count($this->sizes);$i++) {
							customImageClass($dirImg, $dirImgThumb, $i+1, $pathImg[$z]['image'], $ext[1], $this->sizes[$i]['width'], $this->sizes[$i]['height']);
						}
					}else {
						$pathImg[$z]['image'] = "";
						$pathImg[$z]['msg'] = "Imágen no válida.";
					}
				}else{
					$pathImg[$z]['image'] = "";
					$pathImg[$z]['msg'] = $file["msg"];
				}
			} else {
				$pathImg[$z]['image'] = "";
				$pathImg[$z]['msg']= "Error al subir el archivo ".$this->files[$this->postName]["name"][$z].".";
			}
		}
		return $pathImg;
	
	}
	
	public function uploadThumb() {
		$pathImg = array();
		$pathImg['image'] = "";
		$pathImg['msg'] = "";
		
		if ($this->files[$this->postName]["error"] == 0) {
			
			preg_match("|\.([a-z0-9]{2,4})$|i", $this->files[$this->postName]["name"], $ext);
			
			$file = checkingExtFile($ext[1]);
			if($file["upload"] == 1){
				if (($this->files[$this->postName]["type"] == "image/gif" || $this->files[$this->postName]["type"] == "image/jpeg" || $this->files[$this->postName]["type"] == "image/pjpeg" || $this->files[$this->postName]["type"] == "image/png")) {
					$pathImg['image'] = formatNameFile($this->files[$this->postName]["name"]);
					$dirImg = $this->dirbase . $this->path . "/" . $this->pathoriginal . "/";
					move_uploaded_file($this->files[$this->postName]["tmp_name"],$dirImg.$pathImg['image']);
					
					$dirImgThumb = $this->dirbase . $this->path ."/". $this->paththumb ."/";
					
					for($i=0;$i< count($this->sizes);$i++) {
						customImageClass($dirImg, $dirImgThumb, $i+1, $pathImg['image'], $ext[1], $this->sizes[$i]['width'], $this->sizes[$i]['height']);
					}
				}else {
					$pathImg['image'] = "";
					$pathImg['msg'] = "Imágen no válida.";
				}
			}else{
				$pathImg['image'] = "";
				$pathImg['msg'] = $file["msg"];
			}
		} else {
			$pathImg['image'] = "";
			$pathImg['msg']= "No ha seleccionado ningúna imagen.";
		}
		
		return $pathImg;
	
	}
	
}