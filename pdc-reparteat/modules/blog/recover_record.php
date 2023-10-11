
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
	if(isset($_GET["record"])) {
		$article = intval($_GET["record"]);
		
		if (allowed("blog") != 1) {
			$msg = "Usuario sin permisos para realizar esta operación";
		} else {
			$q = "UPDATE ".preBD."articles SET TRASH='0' WHERE ID = '" . $article . "'";
			checkingQuery($connectBD, $q);
  			
			$msg = "Post ".$article." recuperado";
		
		//GESTION DE SITEMAP
			$q1 = "SELECT IDSECTION FROM ".preBD."articles WHERE ID = '" . $article . "'";
			
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			$section = $row1['IDSECTION'];
			
			$q_sec = "select TITLE from ".preBD."articles_sections where ID = '" . $section . "'";
			
			$result_sec = checkingQuery($connectBD, $q_sec);
			$row_sec = mysqli_fetch_assoc($result_sec);
			$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
			
			$msg_alt = construcSitemapArticles($section, $sitemap);
		//FIN
			//GESTION DE RSS
			if($section != 0) {
				$qI = "select ID from ".preBD."blog where IDSECTION = '" . $section . "'";
				$r = checkingQuery($connectBD, $qI);
				$blog = mysqli_fetch_object($r);
				constructBlogRSS($blog->ID);
			}
			//FIN
		}
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=trash&msg=".utf8_decode($msg);
	header($location);
?>