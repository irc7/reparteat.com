<?php 
	$q = "select TITLE, TEXT, VALUE, AUXILIARY from ".preBD."configuration where ID = 2;";
	if (!checkingQuery($connectBD,$q)){
		die('Error(google):'.mysqli_error());
	}
	$result = checkingQuery($connectBD,$q);
	$google = mysqli_fetch_object($result); 
	$idInfo = intval($google->VALUE);
	echo stripslashes($google->TITLE);
	if($idInfo != 0):
		$q = "select ".preBD."articles.TITLE, 
					".preBD."articles.TITLE_SEO, 
					".preBD."articles.SUMARY, 
					".preBD."url_web.SLUG 
				from ".preBD."articles 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."articles.ID and ".preBD."url_web.TYPE = 'article'
				where ".preBD."articles.ID = '" . $idInfo . "' and ".preBD."articles.TRASH = 0 and ".preBD."articles.STATUS = 1";
		
		$result = checkingQuery($connectBD,$q);
		$artCookie = mysqli_fetch_object($result);
		$sumaryInfo = stripslashes($artCookie->SUMARY); 
		
		if(trim($artCookie->TITLE_SEO) == "") {
			$titleInfo = stripslashes($artCookie->TITLE);
		} else {
			$titleInfo = stripslashes($artCookie->TITLE_SEO);
		} 
		$cookie = preBD . "cookie";
		$cookie = sha1($cookie);
		if($google->AUXILIARY != $cookie) {
			$q = "update `". preBD ."configuration` set `AUXILIARY` = '" . $cookie . "' where ID = 2"; 
			checkingQuery($connectBD,$q);
		}
		
?>   
		
		<script src="<?php echo DOMAIN; ?>includes/js/jquery.cookie.js"></script>		
		<script type="text/javascript">
			//script cookies	
			$(document).ready(function() {
				if (!$.cookie('<?php echo $cookie; ?>')){
					
					$("body").prepend("<div class='msgcookie'><p><?php echo $sumaryInfo; ?> <a class='yellow' href='<?php echo DOMAIN.$artCookie->SLUG; ?>' target='_self'>M&aacute;s informaci&oacute;n</a></p></div>");
				
					$("body").on("click", function(e) {
					
						$.cookie('<?php echo $cookie; ?>', 'aceptado', {expires: 132});
						$(".msgcookieSmall").fadeIn();
						$(".msgcookie").fadeOut();
						if (!$.cookie('<?php echo $cookie; ?>')){
							activateGoogleAnalitics();
						}
					});
					
				} else if ($.cookie("<?php echo $cookie; ?>") && $.cookie("<?php echo $cookie; ?>") == "aceptado"){
					activateGoogleAnalitics();
				
				}
			});
			function activateGoogleAnalitics() {
				<?php echo strip_tags(stripslashes($google->TITLE)); ?>
				<?php echo strip_tags(stripslashes($google->TEXT)); ?>
			}
			
		</script>
		<style type="text/css">
			.msgcookie{
				display:block;	
				position:fixed;
				bottom:0;
				height:auto;
				width:101%;
				background:transparent url(<?php echo DOMAIN; ?>template/modules/google/bg_white_80.png) repeat;
				color:#fff;
				font-size:12px;
				font-family: "Ubuntu", Arial, sans-serif;
				z-index:100000000;
			}
			.msgcookieSmall{
				cursor:pointer;
				right:1%;
				display:block;	
				position:fixed;
				bottom:0;
				height:25px;
				width:100px;
				line-height:25px;
				text-align:center;
				background:transparent url(<?php echo DOMAIN; ?>template/modules/google/bg_white_80.png) repeat;
				color:#ffffff;
				font-size:12px;
				font-family: "Ubuntu", Arial, sans-serif;
				z-index:100000000;
				/*para Firefox*/
				-moz-border-radius: 5px 5px 0px 0px;
				/*para Safari y Chrome*/
				-webkit-border-radius: 5px 5px 0px 0px;
				/* para Opera */
				border-radius: 5px 5px 0px 0px;
				/* para IE */
				
			}
			.msgcookie p{
				font-size:12px !important;
				width:70%;
				margin:0 auto;
				padding:10px 0 10px 30px;
				color:#ffffff;
				}
			.msgcookie p a:link,.msgcookie p a:active,.msgcookie p a:visited,.msgcookie p a:hover{
				color:#e8b400;
			}
			.msgcookie a.acceptGoogle, .msgcookie a.cancelGoogle{
				text-decoration:none;
				float:right;
				display:block;
				opacity:0.8;
				filter: alpha(opacity=80);
				margin:0 0 0 20px;
				background-color:#ee7d0c;
				color:#fff !important;
				padding:3px 5px 2px 5px;
				/*para Firefox*/
				-moz-border-radius: 3px 3px 3px 3px;
				/*para Safari y Chrome*/
				-webkit-border-radius: 3px 3px 3px 3px;
				/* para Opera */
				border-radius: 3px 3px 3px 3px;
				/* para IE */
				
			}
			a.acceptGoogle:hover, a.cancelGoogle:hover {
				opacity:1;
				filter: alpha(opacity=100);
			}
			
		</style>
	<?php endif; //cierre del if line 11 echo stripslashes($google->TEXT); ?>