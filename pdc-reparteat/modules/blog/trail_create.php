<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	if (!allowed("blog")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
	$id = abs(intval($_GET["id"]));
	if($id > 0) {	
		//consulta al post
		$qA = "SELECT * FROM ".preBD."articles where ID = ".$id;
		$rA = checkingQuery($connectBD, $qA);
		$post = mysqli_fetch_object($rA);
		if($post->SEND == 1) {
			$q = "select count(*) as TOTAL from ".preBD."blog_subscriptors_trail  where IDPOST = " . $id;
			$q.= " and STATUS = 1 and RESULT = 0";
			$result = checkingQuery($connectBD, $q);
			$process = mysqli_fetch_object($result);
			if($process->TOTAL > 0) {
				$location = "Location: trail_send.php?id=".$id."&action=none";
			} else {
				$location = "Location: trail_send.php?id=".$id."&action=none";
			}
			header($location);
		} else {
			//seleccionamos los datos de la seccion y del blog
			$qSec = "SELECT * FROM ".preBD."articles_sections where ID = ".$post->IDSECTION;
			$resultSec = checkingQuery($connectBD, $qSec);
			$section = mysqli_fetch_object($resultSec);
			
			$blog = whatBlogByPost($id);
			
			$qS = "SELECT * FROM ".preBD."blog_subscriptions where STATUS = 1 and IDBLOG = " . $blog->ID;
			$resultS = checkingQuery($connectBD, $qS);
			$mails = array();
			$cont = 0;
			$cont_off = 0;
			while($row=mysqli_fetch_object($resultS)) {
				$mails[$cont]["mail"] = $row->MAIL;
				$mails[$cont]["id"] = $row->ID;
				$mails[$cont]["NOPOP"] = DOMAIN."public/blog/public/modules/suscription/suscription.delete.php?susc=".$row->ID."&pass=".sha1($row->MAIL);
				$cont++;
			}
					
			$Template = '<center>
							<div style="width:700px;font-size:12px;color:#333;margin-top:20px;margin-bottom:30px;text-align:left;">
								Se ha creado una nueva entrada en el blog <em>'.$section->TITLE.'</em>:
								<br/>
								<a href="'.DOMAIN.'blog/'.$blog->SLUG.'/'.formatNameUrl($post->TITLE_SEO).'_aa'.$post->ID.'.html" title="'.$post->TITLE.'" style="color:#bd111d;font-size:14px;text-decoration:none;">
									'.$post->TITLE.'
								</a>
							</div>
						</center>';
			
			
			
			
			$Unsuscriber = '<center>
								<div style="width:700px;font-size:10px;color:#666;">
									Si no desea recibir más notificaciones, <a href="#NOPOP#">pinche aquí</a>.
								</div>
							</center>';
			
			
			$completeTemplate = $Template . $Unsuscriber;
			
			$total_send = count($mails);
				
			$totalBash = 0;
			$cont_ok = 0;
			$now = date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s');
			$Subject = "Nueva entrada en el blog " . $section->TITLE; 
			for($i=0;$i<count($mails);$i++) {
				$body = str_replace("#NOPOP#", $mails[$i]["NOPOP"], $completeTemplate);
				
				$body = str_replace("</map>", "</map>\r\n", $body);
				$body = str_replace("</div>", "</div>\r\n", $body);
				$body = str_replace("</td>", "</td>\r\n", $body);
				$body = str_replace("</table>", "</table>\r\n", $body);
				$body = str_replace("</a>", "</a>\r\n", $body);
				
				
				$q = "INSERT INTO `".preBD."blog_subscriptors_trail`";
				$q.= " (`IDSUBSCRIPTION`, `MAIL`, `SUBJECT`, `BODY`, `DATE`, `IDPOST`, `MAILER`, `INFOERROR`, `RESULT`, `STATUS`) ";
				$q.= " VALUES"; 
				$q.= " ('".$mails[$i]["id"]."', '".$mails[$i]["mail"]."', '".$Subject."', '".addslashes($body)."', '".$now."', '".$post->ID."', '".MAILSEND."','','0', '1')";
				if(!checkingQuery($connectBD, $q)) {
					$mails_off[$cont_off]["mail"] = $mails[$i]["mail"];
					$mails_off[$cont_off]["id"] = $mails[$i]["id"];
					$mails_off[$cont_off]["error"] = $mails[$i]["id"] . ".- " . $mails[$i]["mail"] . " - <span style=\"color:#B4505A\">(Error: Insercción de suscriptor en la cola del newsletter.)</span><br />";
				}
			}
			
			for($i=0;$i<count($mails_off);$i++) {
				$q = "INSERT INTO `".preBD."blog_subscriptors_trail`";
				$q.= " (`IDSUBSCRIPTION`, `MAIL`, `SUBJECT`, `BODY`, `DATE`, `IDPOST`, `MAILER`, `INFOERROR`, `RESULT`, `STATUS`) ";
				$q.= " VALUES";  
				$q.= " ('".$mails_off[$i]["id"]."', '".$mails_off[$i]["mail"]."', '', '', '".$now."', '".$post->ID."', '".MAILSEND."','".$mails_off[$i]["error"]."','2', '0')";
				checkingQuery($connectBD, $q)
			}
	
			$location = "Location: trail_send.php?id=".$post->ID."&action=start";
			header($location);
		}
	}else{
		disconnectdb($connectBD);
		$msg = "El post que intenta enviar no esta disponible.";
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&msg=".utf8_decode($msg);
		header($location);
	}	
?>
