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
	$mnu = $_POST["mnu"];
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
	//pre($_POST);die();
	
	if ($_POST) {
		$Author = $_POST["Author"];
		
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
		
		
		$typeNewsletter = $_POST["typeNewsletter"];
		$Author = $_POST["Author"];
		$Subject = addslashes(trim($_POST["Subject"]));
		
		$mailerSend = 0; 
		
		/*Suscriptores*/
		$group = array();
		$group = $_POST["groups"];
		//Diferenciar los tipos y crear el body en funcion
		
		//Creacion del BOLETIN
		
		$q = "insert into ".preBD."newsletter (`AUTHOR`, `IDMAILER`, `DATE`, `SUBJECT`, `HTML`, `SEND_OK`, `SEND_OFF`)";
		$q .= " VALUES ('".$Author."', '".$mailerSend."', '".$Date->format('Y-m-d H:i:s')."', '".$Subject."', '', '0', '0')";
		
		checkingQuery($connectBD, $q);
		$id_new = mysqli_insert_id($connectBD);
		

		if($typeNewsletter == "article"){
			$listBlog = array();
			$listNews = array();
			$listNewsF = array();
			
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
			$bodyBD = addslashes($completeTemplate);
			$newsletterAux = "Se le esta intentando enviar información que no puede ser interpretada, por favor abra este correo en un lector que soporte HTML";
		}else{
			$newsletter = trim($_POST["freeCode"]);
			$newsletterAux = trim($_POST["freeText"]);
			$bodyBD = addslashes($newsletter);
		}
		
		
		
		
			$completeTemplate = $headerTemplate . $Template . $style;
		
			if($typeNewsletter == "article"){
				$body = str_replace(";}", ";}\r\n", $completeTemplate);
				$body = str_replace("</style>", "</style>\r\n", $body);
				$body = str_replace("</map>", "</map>\r\n", $body);
				$body = str_replace("</div>", "</div>\r\n", $body);
				$body = str_replace("</td>", "</td>\r\n", $body);
				$body = str_replace("</table>", "</table>\r\n", $body);
				$body = str_replace("</p>", "</p>\r\n", $body);
				
			}elseif($typeNewsletter == "free"){
				$body = "<div style='width:100%;background-color:#fff;'>".$newsletter."</div>";
			}
		
		

	//Actualizamos el cuerpo del boletín

		$q = "UPDATE ".preBD."newsletter SET"; 
		$q .= " HTML = '" . $bodyBD . "'";
		$q .= " where ID = " . $id_new;
		
		checkingQuery($connectBD, $q);
		
//Creamos su registro estadístico
		$q = "INSERT INTO `".preBD."statistics_newsletter`(`DATE`, `IDNEWSLETTER`, `CONT`) VALUES (NOW(), ".$id_new.", 0)";
		checkingQuery($connectBD, $q);
	
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="../../../favicon.png">
	<link rel="stylesheet" href="../../css/admin.css" type="text/css" />
	
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>

	<title>Previsualización del newsletter - <?php echo TITLEWEB; ?></title>
	
</head>	
<body style="background-color:#fff;">	
	<center  style="background-color:#ededed;padding:10px 0px;">
	<div id="boxMenutype">
		<form method="post" name="mainform" id="mainform" action="send_config.php">
			<input type="hidden" name="record" id="record" value="<?php echo $id_new; ?>" />
				<input type="hidden" id="mnu" name="mnu" value="<?php echo $mnu; ?>" />
				<?php for($i=0;$i<count($group);$i++): ?>
					<input type="hidden" id="groups-<?php echo $i; ?>" name="groups[]" value="<?php echo $group[$i]; ?>" />
				<?php endfor; ?>
				<ul class="list-record-fields">
					<li class="complete" style="padding-bottom:10px;border-bottom:1px solid #fff;">
						<p class="titlePage">Seleccionar método de envio</p>					
					</li>
					<?php 
						$typeSend = array("smtp", "mandrill");
						for($i=0;$i<count($typeSend);$i++):
							$cond = " and TYPESEND = '".$typeSend[$i]."'";
							if($i==0) {
								$cond .= " or TYPESEND = 'mail'";
							}
							$q = "select ID, NAMEFROM from ".preBD."newsletter_mailer where true".$cond." order by MAIL asc";
							$result = checkingQuery($connectBD, $q);
							$totalMailer = mysqli_num_rows($result);
							if($totalMailer>0):
					?>
						<li class="complete">
							<label class="label-field bold" for="<?php echo $typeSend[$i]; ?>-mailerSend">
							<?php if($i==0): ?>
								Envío <em>SMTP</em> tradicional: 
							<?php elseif($i == 1): ?>
								Envío cuenta <em>MANDRILL</em>: 
							<?php endif; ?>
							</label>
							<input type="radio" name="typeSend" id="<?php echo $typesend[$i]; ?>-typeSend" value="<?php echo $typeSend[$i]; ?>" />
						</li>
						<li class="complete type-send-level2" id="box-<?php echo $typeSend[$i]; ?>-mailerSend">
							<label class="label-field bold greenB" for="<?php echo $typeSend[$i]; ?>-mailerSend">
								Seleccione cuenta de correo
							</label>
							<select class="select-mailer-send" name="mailerSend" id="<?php echo $typeSend[$i]; ?>-mailerSend" style="margin-left:110px;width:300px;">
								<?php 
								while($mailer = mysqli_fetch_object($result)):
								?>
									<option value="<?php echo $mailer->ID; ?>"><?php echo $mailer->NAMEFROM; ?></option>
								<?php endwhile; ?>
							</select>
						</li>
					<?php	endif; 
						endfor; 
					?>
					<li class="complete" style="">
						<label class="label-field bold" for="<?php echo $typeSend[$i]; ?>-mailerSend">Generar código <em>HTML y CSV</em> para MAILCHIMP:</label>
						<input type="radio" name="typeSend" id="code-typeSend" value="code" />
					</li>
			
				</li>
				<li class="complete" style="border-top:1px solid #fff;padding-top:10px;">
					<div id="menuArticle" style="float:left">
						<a href="../../index.php?mnu=mailing&com=newsletter&tpl=create&action=deleteTemp&record=<?php echo $id_new; ?>" target="_self">Volver</a>
					</div> 
					<input type="button" class="corposativeButton" id="sendForm" value="Siguiente" />
				</li>
			</ul>
			</form>
		</div>
	</center>
	<div id="content_newsletter_view" style="width:100%;border-top:10px solid #000000;border-bottom:10px solid #000000;padding-top:20px;padding-bottom:20px;">
		<center><?php echo stripslashes($bodyBD); ?></center> 
	</div>
	<script type="text/javascript">
		var $pre = jQuery.noConflict();
		$pre(document).ready(function(){
			$pre('#content_newsletter_view a').attr('target','_BLANK');
			
			$pre('input:radio[name=typeSend]').click(function(){
				var v = $pre("input:radio[name=typeSend]:checked").val();
				if(v === "smtp") {
					$pre(".select-mailer-send").attr("disabled", true);
					$pre("#"+v+"-mailerSend").attr("disabled", false);
					$pre(".type-send-level2").css("display", "none");
					$pre("#box-"+v+"-mailerSend").fadeIn();
				}else if(v === "mandrill") {
				
					$pre(".select-mailer-send").attr("disabled", true);
					$pre("#"+v+"-mailerSend").attr("disabled", false);
					$pre(".type-send-level2").css("display", "none");
					$pre("#box-"+v+"-mailerSend").fadeIn();
					
				}else if(v === "code") {
					$pre(".select-mailer-send").attr("disabled", true);
					$pre(".type-send-level2").css("display", "none");
					
				}
			});
			$pre('#sendForm').click(function(){
				if($pre("input:radio[name=typeSend]").is(":checked")){
					$pre("#mainform").submit();
				}else{
					alert("Debe seleccionar un método de envío para el boletín digital");
				}
			});
		});
	</script>
	<style type="text/css">
			#boxMenutype {
				display:block;
				width:700px;
				font-family:Arial, sans-serif;
			}
			#boxMenutype * {
				font-family: Arial, sans-serif;
			}
			#boxMenutype .titlePage {
				font-size:16px;
				font-weight:bold;
				color:#000000;
			}
			#boxMenutype ul {
				list-style:none;
				
			}
			#boxMenutype li {
				font-size: 13px;
				font-weight:bold;
				text-align:left;
				margin-bottom:10px;
			}
			#boxMenutype li select {
				width:160px;
			}
			#boxMenutype li.type-send-level2 {
				margin-left:0px;
				display:none;
				width: 97%;
				background-color:#ffffff;
				padding:2%;
			}
			#boxMenutype li label {
				width:290px;
				display:block;
				float:left;
			}
			#boxMenutype li.type-send-level2 label{
				width:170px;
				display:block;
				float:left;
			}
			#boxMenutype ul li div {
				border-radius: 4px;
				-moz-border-radius: 4px;
				-webkit-border-radius: 4px;
				background-color: #000000;
				color:#fff;
				cursor:pointer;
				width:55px;
				height:25px;
				line-height:25px;
				text-align:center;
			}
			#boxMenutype ul li div a{
				color:#fff;
				width:55px;
				height:25px;
				line-height:25px;
				text-align:center;
			}
			#menuArticle {
				opacity:1;
				filter:alpha(opacity=100);
			}
			#boxMenutype ul li div:hover {
				opacity:1;
				filter:alpha(opacity=100);
				background-color:#ee984c;
				color:#fff;
			}
			#boxMenutype ul li input[type="button"] {
				border-radius: 4px;
				-moz-border-radius: 4px;
				-webkit-border-radius: 4px;
				background-color: #000000;
				color:#fff;
				cursor:pointer;
				width:auto;
				height:25px;
				float:right;
				line-height:25px;
				text-align:center;
				border:none;
				font-weight: bold;
				-webkit-transition: all 0.3s ease;
				-moz-transition: all 0.3s ease;
				-o-transition: all 0.3s ease;
				transition: all 0.3s ease;
			}
			#boxMenutype ul li input[type="button"]:hover {
				background-color: #ee984c;
				color:#fff;
			}
		</style>
</body>
<?php disconnectdb($connectBD); ?>
</html>

