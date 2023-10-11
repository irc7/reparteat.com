<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	//pre($_FILES);die();
	if (allowed("blog")) {
		if ($_POST) {
		
			/*rescatamos los valores de ancho y alto por defecto que tiene la tabla en la BD*/
			$q = "show columns from ".preBD."articles_sections where Field = 'HEIGHT_IMAGE' or Field = 'WIDTH_IMAGE'";
			
			$result = checkingQuery($connectBD, $q);
			$i=0;
			while($row = mysqli_fetch_array($result)) {
				if($i == 0){
					$ancho = $row['Default'];
				}else{
					$alto = $row['Default'];
				}
				$i++;
			} 		
			
			$section = stripslashes(trim($_POST["section"]));		
			$Title_seo = stripslashes(trim($_POST["Title_seo"]));
			if($Title_seo == ""){
				$Title_seo = $section; 
			}
			$description = stripslashes(trim($_POST["description"]));
			
			$author = abs(intval($_POST["author"]));
			$val = $_POST["Validation"];
			$nombre_imagen = "";
			
			if ($_FILES["Doc"]["error"] == 0) {
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Doc"]["name"], $ext);
				$ext[0] = str_replace(".", "", $ext[0]);
				$file = checkingExtFile($ext[0]);
				//pre($file);die();
				if($file["upload"] == 1){
					if ($_FILES["Doc"]["type"] == "image/jpeg" || $_FILES["Doc"]["type"] == "image/pjpeg" || $_FILES["Doc"]["type"] == "image/gif" || $_FILES["Doc"]["type"] == "image/png") {
						$ext = explode("/", $_FILES["Doc"]["type"]);
						$nombre_imagen = formatNameFile($_FILES["Doc"]["name"]);
						$url = "../../../files/section/image/";
						$temp_url_name = $url .$nombre_imagen;
						if(file_exists($temp_url_name)) {
							unlink($temp_url_name);	
						}
						//pre($temp_url_name);
						//move_uploaded_file($_FILES["Doc"]["tmp_name"],$temp_url_name);
						//pre("pasa");
						$temp_url = "../../../temp/".$nombre_imagen;
						$temp = "../../../temp/";
						move_uploaded_file($_FILES['Doc']['tmp_name'],$temp_url);
						$url = "../../../files/section/image/";
						customImage($temp, $url, $nombre_imagen, $ext[1], $ancho, $alto);	
					} else {
						$msg .= "No es un archivo correcto. ";
						$error = "icono";
					}
				}else{
					$error = "Image Section";
					$msg .= $file["msg"];
				}
			}			
			$slug = formatNameUrl($Title_seo);
			$qS = "select count(*) as total from ".preBD."blog where `SLUG` = '" . $slug . "'";
			$rS = checkingQuery($connectBD, $qS);
			$tS = mysqli_fetch_object($rS);
			if($tS->total > 0) {
				$slug = $slug . "-r";
			}
			
			
			
				$q = "INSERT INTO ".preBD."articles_sections (TITLE, TITLE_SEO, DESCRIPTION, THUMBNAIL, TYPE) VALUES ('" . $section . "', '" . $Title_seo . "', '" . $description . "', '" . $nombre_imagen . "', 'blog')";
				checkingQuery($connectBD, $q);

				$newID = mysqli_insert_id($connectBD);
				$msg = "Sección ".$section." creada";
				$action = "new_section";
				$msg_alt = construcIndexSitemap($action);
			
				
				$qB = "INSERT INTO `".preBD."blog`(`SLUG`, `IDSECTION`, `AUTHOR`, `VALIDATION`) VALUES";
				$qB .= " ('".$slug."', '".$newID."', '".$author."', '".$val."')"; 
				checkingQuery($connectBD, $qB);
				
				$newBlog = mysqli_insert_id($connectBD);
				constructBlogRSS($newBlog);

							
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>