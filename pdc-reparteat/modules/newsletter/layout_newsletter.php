<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	
	
	$mnu = trim($_POST["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
	if($_POST["typeNewsletter"] == "article") {	
		$listBlog = array();
		$listNews = array();
		$listNewsF = array();
		
		$Date_hh = abs(intval($_POST["Date_hh"]));
		if ($Date_hh == NULL) {
			$Date_hh = "00";
		} elseif(strlen($Date_hh) == 1) {
			$Date_hh = '0' . $Date_hh;
		} elseif($Date_hh > 23) {
			$Date_hh = 23;
			$msg .= "El número de horas no puede ser mayor a 23.<br/>";
		}
		
		$Date_ii = abs(intval($_POST["Date_ii"]));
		if ($Date_ii == NULL) {
			$Date_ii = "00";
		} elseif(strlen($Date_ii) == 1) {
			$Date_ii = '0' . $Date_ii;
		} elseif($Date_ii > 59) {
			$Date_ii = 59;
			$msg .= "El número de minutos no puede ser mayor a 59.<br/>";
		}
		
		$DateAux = $_POST["date_day"]." ".$Date_hh.":".$Date_ii.":00";
		$Date = new DateTime($DateAux);
//Blog	
		if(isset($_POST["Blog"]) && count($_POST["Blog"]) > 0){
			for($i=0;$i<count($_POST["Blog"]);$i++) {
				$listBlog[] = newsletterInfoArticle($_POST["Blog"][$i], "blog");
			}
			$viewBlog = true;//paramentro de control para mostrarla o no 
		}else{
			$viewBlog = false;
		}
		
//News	
		if(isset($_POST["News"]) && count($_POST["News"]) > 0){
			for($i=0;$i<count($_POST["News"]);$i++) {
				$listNews[] = newsletterInfoArticle($_POST["News"][$i], "article");
			}
			$viewNews = true;//paramentro de control para mostrarla o no 
		}else{
			$viewNews = false;
		}
//NewsF
		if(isset($_POST["NewsF"]) && count($_POST["NewsF"]) > 0){
			for($i=0;$i<count($_POST["NewsF"]);$i++) {
				$listNewsF[] = newsletterInfoArticle($_POST["NewsF"][$i], "article");
			}
			$viewNewsF = true;//paramentro de control para mostrarla o no 
		}else{
			$viewNewsF = false;
		}
	
		require_once("templates/template.ihp.php");
		
	} elseif($_POST["typeNewsletter"] == "free") {
		$completeTemplate = trim($_POST["freeCode"]);
	}
	
	$bodyBD = addslashes($completeTemplate);
?>

<?php echo stripslashes($bodyBD); ?></center> 
	
<?php disconnectdb($connectBD); ?>
