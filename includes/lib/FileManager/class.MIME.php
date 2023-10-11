<?php

Abstract Class MIME {
	
	private static $allowedImageTypes = array("image/jpeg", "image/jpg", "image/gif", "image/png");
	private static $allowedFileTypes = array("application/x-shockwave-flash");
	
	public function MIMEToExtension($mime) {
		switch ($mime) {
			case "image/jpeg":
			case "image/jpg":
				return "jpg";
				break;
			case "image/gif":
				return "gif";
				break;
			case "image/png":
				return "png";
				break;
			case "application/pdf":
				return "pdf";
				break;
			case "application/x-shockwave-flash":
				return "swf";
				break;
		}
	}
	
	public function isAllowedImage($mime) {
		return in_array($mime, MIME::$allowedImageTypes);
	}
	public function isAllowedFile($mime) {
		return in_array($mime, MIME::$allowedFileTypes);
	}
}

?>