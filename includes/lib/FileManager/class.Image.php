<?php
/*
TODO:

- Find a way to stablish a desired name to the thumb, cut or whatever.
*/

require_once "class.File.php";

class Image extends File {
	
	private $thumb_name;
	private $width;
	private $height;
	private $type;
	
	private $allowedTypes = array("image/jpeg", "image/gif", "image/png");
	
	public function __construct() {
	}
	
	public function getThumbName() {
		return $this->thumb_name;
	}
	
	public function setFile($file) {
		parent::setFile($file);
		if ($this->size > 0) {
			$info = getimagesize($this->file);
			$this->width = $info[0];
			$this->height = $info[1];
			$this->type = $info["mime"];
			if (!in_array($this->type, $this->allowedTypes)) {
				trigger_error("Tipo no soportado: " . $this->type, E_USER_ERROR);
			}
		}
	}
	
	public function thumb($to, $new_width, $include_height = false) {
		$r = $this->width / $this->height;
		
		$new_height = $new_width / $r;
		
		switch ($this->type) {
			case "image/jpeg":
				$old = imagecreatefromjpeg($this->file);
				break;
			case "image/png":
				$old = imagecreatefrompng($this->file);
				break;
			case "image/gif":
				$old = imagecreatefromgif($this->file);
				break;
		}
		
		$new = imagecreatetruecolor($new_width, $new_height);
		
		imagecopyresampled(
			$new,
			$old,
			0, 0,
			0, 0,
			$new_width,
			$new_height,
			$this->width,
			$this->height
		);
		
		if ($include_height) {
			$this->thumb_name = "thumb-" . $new_width . "x" . intval($new_height) . "-" . $this->name;
		} else {
			$this->thumb_name = "thumb-" . $new_width . "-" . $this->name;
		}
		
		switch ($this->type) {
			case "image/jpeg":
				imagejpeg($new, $to . "/" . $this->thumb_name, 100);
				break;
			case "image/png":
				imagepng($new, $to . "/" . $this->thumb_name, 9);
				break;
			case "image/gif":
				imagegif($new, $to . "/" . $this->thumb_name);
				break;
		}
	}
	
	/*
	Creates a thumb centered or not from an image.
	
	$size is the new required width
	(and height, cause it's exact $size x $size thumb).
	
	1.	Check if image width < $size
		T -> expand image to width = $size
		F -> (width >= $size) do nothing.
		
	Now we have an image which width is, at least, equal to $size.
	
	2. Check if width > height (this is a Landscape image)
		T -> resample to make height = width
		F -> (this is a Portrair image)
			2.1.	cut (centered or not)
			2.2.	Create a thumb with width = $size
	
	Known problems:
		1. Set a custom name for the result image is needed.
		2. Calling Image::exactThumb disallow Image::move and Image::thumb.
	*/
	public function exactThumb($to, $size, $center_thumb = true) {
		
		/*
		we have to fill this array with the intermediate
		files created through the process.
		*/
		$toRemoveQueue = array();
		
		if ($this->width > $this->height) {
			// is landscape?
			
			// ratio
			$r = $this->width / $this->height;
			
			// increase a bit the ratio to prevent values lower than $size
			$new_width = ceil($this->width * (1.01 * $r));
			$new_height = ceil($this->width);
			
			$this->thumb($to, $new_width);
			$toRemoveQueue[] = $to . $this->thumb_name;
			
			$this->setFile($to . $this->thumb_name);
			
			// TODO: see if should be centered
			// cutted to the shorter dimension
			if ($center_thumb) {
				$x = ($this->width - $new_height) / 2;
				$y = ($this->height - $new_height) / 2;
			} else {
				$x = 0;
				$y = 0;
			}
			$this->cutAt($to, $x, $y, $this->height, $this->height);
			$toRemoveQueue[] = $to . $this->thumb_name;
			
			$this->setFile($to . $this->thumb_name);
			
			/*
			this is the call that makes the final cut
			when is a portrait.
			*/
			$this->thumb($to, $size);
			
		} else {
			// is portrait?
			
			$this->thumb($to, $size);
			
			/*
			commented this adition to queue because
			in Image::cutAt has been set that if
			w = h -> uset "thumb-" as name prefix.
			*/
			//$toRemoveQueue[] = $to . $this->thumb_name;
			$this->setFile($to . $this->thumb_name);
			
			if ($center_thumb) {
				$x = ($this->width - $size) / 2;
				$y = ($this->height - $size) / 2;
			} else {
				$x = 0;
				$y = 0;
			}
			
			/*
			this is the call that makes the final cut
			when is a portrait.
			*/
			$this->cutAt($to, $x, $y, $size, $size);
			
		}
		
		/*
		delete the temporal files (intermediate images)
		*/
		foreach ($toRemoveQueue as $item) {
			$f = FileManager::get("Image");
			$f->setFile($item);
			$f->delete();
		}
		
		$toRemoveQueue = null;
		
	}
	
	public function cutAt($to, $x, $y, $w, $h) {
		switch ($this->type) {
			case "image/jpeg":
				$old = imagecreatefromjpeg($this->file);
				break;
			case "image/png":
				$old = imagecreatefrompng($this->file);
				break;
			case "image/gif":
				$old = imagecreatefromgif($this->file);
				break;
		}
		
		$new = imagecreatetruecolor($w, $h);
		
		imagecopy($new, $old, 0, 0, $x, $y, $this->width, $this->height);
		
		if ($w == $h) {
			$this->thumb_name = "thumb-" . $w . "-" . $this->name;
		} else {
			$this->thumb_name = "cut-" . $w . "x" . $h . "-" . $this->name;
		}
		
		switch ($this->type) {
			case "image/jpeg":
				imagejpeg($new, $to . "/" . $this->thumb_name, 100);
				break;
			case "image/png":
				imagepng($new, $to . "/" . $this->thumb_name, 9);
				break;
			case "image/gif":
				imagegif($new, $to . "/" . $this->thumb_name);
				break;
		}
	}
	
	public function cut($to, $w, $h) {
		$this->cutAt($to, 0, 0, $w, $h);
	}
	
//
}







?>