<?php 
	require_once ("../../../pdc-ihp/includes/database.php");
	$connectBD = connectdb();
	require_once ("../../../pdc-ihp/includes/config.inc.php");
	require_once ("../../../includes/functions.inc.php");
	
	$origin = intval($_GET["file"]);
	
	$q = "select TITLE, URL from ".preBD."paragraphs_file where ID = " . $origin;
	$result = checkingQuery($connectBD,$q);
	$download = mysqli_fetch_object($result);
	
	preg_match("|\.([a-z0-9]{2,4})$|i", $download->URL, $extension);
	
	if($download->TITLE != "") {
		$file = formatNameUrl(substr($download->TITLE, 0, 100));
		$file = $file.$extension[0];
		
	} else {
		$name_file = explode("-", $download->URL, 2);
		$file = $name_file[1];
	}
	$directory = "../../../files/articles/doc/";
	$root = "../../../temp/";
	$path = $root.$file;
	$old_path = $directory.$download->URL;

	copy($old_path, $path);
	
	switch(strtolower($extension[1])) {
		case "js":$type = "application/x-javascript";
		case "json":$type = "application/json";
		case "jpg":$type = "image/jpg";
		case "jpeg":$type = "image/jpg";
		case "jpe":$type = "image/jpg";
		case "png":$type = "image/".strtolower($extension[1]);
		case "gif":$type = "image/".strtolower($extension[1]);
		case "bmp":$type = "image/".strtolower($extension[1]);
		case "tiff":$type = "image/".strtolower($extension[1]);
		case "css":$type = "text/css";
		case "xml":$type = "application/xml";
		case "doc":$type = "application/msword";
		case "docx":$type = "application/msword";
		case "xls":$type = "application/vnd.ms-excel";
		case "xlt":$type = "application/vnd.ms-excel";
		case "xlm":$type = "application/vnd.ms-excel";
		case "xld":$type = "application/vnd.ms-excel";
		case "xla":$type = "application/vnd.ms-excel";
		case "xlc":$type = "application/vnd.ms-excel";
		case "xlw":$type = "application/vnd.ms-excel";
		case "xll":$type = "application/vnd.ms-excel";
		case "ppt":$type = "application/vnd.ms-powerpoint";
		case "pps":$type = "application/vnd.ms-powerpoint";
		case "rtf":$type = "application/rtf";
		case "pdf":$type = "application/pdf";
		case "html":$type = "text/html";
		case "htm":$type = "text/html";
		case "php":$type = "text/html";
		case "txt":$type = "text/plain";
		case "mpeg":$type = "video/mpeg";
		case "mpg":$type = "video/mpeg";
		case "mpe":$type = "video/mpeg";
		case "mp3":$type = "audio/mpeg3";
		case "wav":$type = "audio/wav";
		case "aiff":$type = "audio/aiff";
		case "aif":$type = "audio/aiff";
		case "avi":$type = "video/msvideo";
		case "wmv":$type = "video/x-ms-wmv";
		case "mov":$type = "video/quicktime";
		case "zip":$type = "application/zip";
		case "tar":$type = "application/x-tar";
		case "swf":$type = "application/x-shockwave-flash";
		default:$type = "application/octet-stream";
	}
	
    if (is_file($path)) {
        $size = filesize($path);
        if (function_exists('mime_content_type')) {
            $type = mime_content_type($path);
        } else if (function_exists('finfo_file')) {
            $info = finfo_open(FILEINFO_MIME);
            $type = finfo_file($info, $path);
            finfo_close($info);
        }
        // Definir headers
        header("Content-Type: ".$type);
        header("Content-Disposition: attachment; filename=".$file);
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ". $size);
        // Descargar archivo
        readfile($path);
		
		//Borrar archivo temporal
		if(file_exists($path)) {
			unlink($path);
		}
    } else {
        die("El archivo no existe.");
    }
	
	disconnectdb();

?>
<script type="text/javascript">
	history.back();
</script>