<?php 
	$idAvisos = 2;
	$idPolitica = 1;
	
	$idInfo = intval($googleBDinfo->VALUE);
	
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
		$q = "select ".preBD."articles.TITLE, 
					".preBD."articles.TITLE_SEO, 
					".preBD."articles.SUMARY, 
					".preBD."url_web.SLUG 
				from ".preBD."articles 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."articles.ID and ".preBD."url_web.TYPE = 'article'
				where ".preBD."articles.ID = '" . $idAvisos . "' and ".preBD."articles.TRASH = 0 and ".preBD."articles.STATUS = 1";
		
		$result = checkingQuery($connectBD,$q);
		$artAvisos = mysqli_fetch_object($result);
		
		$q = "select ".preBD."articles.TITLE, 
					".preBD."articles.TITLE_SEO, 
					".preBD."articles.SUMARY, 
					".preBD."url_web.SLUG 
				from ".preBD."articles 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."articles.ID and ".preBD."url_web.TYPE = 'article'
				where ".preBD."articles.ID = '" . $idPolitica . "' and ".preBD."articles.TRASH = 0 and ".preBD."articles.STATUS = 1";
		
		$result = checkingQuery($connectBD,$q);
		$artPolitica = mysqli_fetch_object($result);
		
		
		$sumaryInfo = stripslashes($artCookie->SUMARY); 
		
		if(trim($artCookie->TITLE_SEO) == "") {
			$titleInfo = stripslashes($artCookie->TITLE);
		} else {
			$titleInfo = stripslashes($artCookie->TITLE_SEO);
		} 
		$cookie = preBD . "cookie";
		$cookie = sha1($cookie);
		if($googleBDinfo->AUXILIARY != $cookie) {
			$q = "update `". preBD ."configuration` set `AUXILIARY` = '" . $cookie . "' where ID = 2"; 
			checkingQuery($connectBD,$q);
		}
		
?>   
<div id="btn-div-cookies" class="btn btn-primary transition">
	Gestionar cookies
</div>
<div id="div-cookies">
		<div class="header-cookiebanner">
			<h4 class="cookiebanner-title title" id="">Gestionar el consentimiento de las cookies</h4>
			<div class="cookiebanner-close transition">
				<i class="fa fa-close"></i>
			</div>
		</div>
		<div class="cookiebanner-body">
			<div class="cookiebanner-message"><?php echo $sumaryInfo; ?></div>
			<!-- categories start -->
			<div class="cookiebanner-categories">
				<details class="cookiebanner-category cookiebanner-functional">
					<summary>
						<div class="cookiebanner-category-header">
							<span class="cookiebanner-category-title">Funcional</span>
							<span class="cookiebanner-icon cookiebanner-open">
								<i class="fa fa-chevron-down"></i>
							</span>
							<span class="cookiebanner-always-active">
								Siempre activo							
							</span>
						</div>
					</summary>
					<div class="cookiebanner-description transition">
						<span class="cookiebanner-description-functional">El almacenamiento o acceso técnico es estrictamente necesario para el propósito legítimo de permitir el uso de un servicio específico explícitamente solicitado por el abonado o usuario, o con el único propósito de llevar a cabo la transmisión de una comunicación a través de una red de comunicaciones electrónicas.</span>
					</div>
				</details>

				<details class="cookiebanner-category cookiebanner-statistics">
					<summary>
							<div class="cookiebanner-category-header">
								<span class="cookiebanner-category-title">Estadísticas</span>
								<span class="cookiebanner-icon cookiebanner-open">
									<i class="fa fa-chevron-down"></i>
								</span>
								<span class="cookiebanner-banner-checkbox">
									<input type="checkbox" id="cookiebanner-statistics-optin" class="cookiebanner-consent-checkbox cookiebanner-statistics" checked="" />
								</span>
							</div>
					</summary>
					<div class="cookiebanner-description transition">
						<span class="cookiebanner-description-statistics">El almacenamiento o acceso técnico que es utilizado exclusivamente con fines estadísticos.</span>
						<span class="cookiebanner-description-statistics-anonymous">El almacenamiento o acceso técnico que se utiliza exclusivamente con fines estadísticos anónimos. Sin un requerimiento, el cumplimiento voluntario por parte de tu Proveedor de servicios de Internet, o los registros adicionales de un tercero, la información almacenada o recuperada sólo para este propósito no se puede utilizar para identificarte.</span>
					</div>
				</details>
<?php /*
				<details class="cookiebanner-category cookiebanner-marketing">
					<summary>
							<div class="cookiebanner-category-header">
								<span class="cookiebanner-category-title">Marketing</span>
								<span class="cookiebanner-icon cookiebanner-open">
									<i class="fa fa-chevron-down"></i>
								</span>
								<span class="cookiebanner-banner-checkbox">
									<input type="checkbox" id="cookiebanner-marketing-optin" data-category="cookiebanner_marketing" class="cookiebanner-consent-checkbox cookiebanner-marketing" size="40" value="1">
								</span>
							</div>
					</summary>
					<div class="cookiebanner-description transition">
						<span class="cookiebanner-description-marketing">El almacenamiento o acceso técnico es necesario para crear perfiles de usuario para enviar publicidad, o para rastrear al usuario en una web o en varias web con fines de marketing similares.</span>
					</div>
				</details>
*/ ?>
			</div><!-- categories end -->
		</div>
		<div class="cookiebanner-buttons">
			<button class="btn btn-primary transition cookiebanner-accept">Aceptar</button>
			<button class="btn btn-primary transition cookiebanner-deny">Denegar</button>
			<button class="btn btn-primary transition cookiebanner-view-preferences">Ver preferencias</button>
			<button class="btn btn-primary transition cookiebanner-save-preferences">Guardar preferencias</button>	
		</div>

		<div class="cookiebanner-links cookiebanner-documents">
			<a class="cookiebanner-link cookie-statement" href="<?php echo DOMAIN.$artCookie->SLUG; ?>" data-relative_url="">Política de cookies </a>
			&nbsp;|&nbsp;
			<a class="cookiebanner-link privacy-statement" href="<?php echo DOMAIN.$artPolitica->SLUG; ?>" data-relative_url="">Política de privacidad</a>
			&nbsp;|&nbsp;
			<a class="cookiebanner-link impressum" href="<?php echo DOMAIN.$artAvisos->SLUG; ?>" data-relative_url="">Avisos Legales</a>
		</div>
</div>	
		
		<style>
		#btn-div-cookies {
			position: fixed;
			right:40px;
			bottom: -25px;
			width: auto;
			background-color: white;
			border:1px solid #333;
			padding: 10px 20px;
			text-align: left;
			z-index: 9999;
			border-radius:15px;
			font-size:12px;
			color:#333;
			cursor:pointer;
			display: none;
		}
		#btn-div-cookies:hover {
			bottom:3px;
			border:1px solid #ee7d0c;
			color:#ee7d0c;
		}
		@media(max-width:767px) {
			#btn-div-cookies::before {
				content:"\f077";
				display: inline-block;
				font: normal normal normal 14px/1 FontAwesome;
				font-size: inherit;
				text-rendering: auto;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				transform: translate(0, 0);
				position:absolute;
				top:-10px;
				left:45%;
				background-color:#fff;
				border:1px solid #333;
				border-radius:50%;
				width:20px;
				height:20px;
				padding-top:2px;
				text-align:center;
			}
			
		}
		#div-cookies {
			position: fixed;
			right:10px;
			bottom: 10px;
			background-color: white;
			box-shadow: 0px -5px 15px gray;
			padding: 15px;
			text-align: left;
			z-index: 9999;
			border-radius:15px;
			width:90%;
			max-width:450px;
			font-size:12px;
			display: none;
		}
		.cookiebanner-close {
			position: absolute;
			right: 20px;
			top: 20px;
			cursor:pointer;
		}
		.cookiebanner-close:hover {
			color:#ee7d0c;
		}
		.cookiebanner-message {
			margin-bottom:10px;
		}
		.cookiebanner-categories {
			display:none;
		}
		.cookiebanner-category-header {
			width:100%;
			padding:5px 10px;
			background-color:#ededed;
			margin-bottom:3px;
		}
		.cookiebanner-always-active {
			float:right;
			color:green;
		}
		.cookiebanner-icon {
			float:right;
			margin-left:15px;
			margin-top:3px;
		}
		.cookiebanner-banner-checkbox {
			float:right;
		}
		cookiebanner-description{
			padding:10px 0px;
		}
		.cookiebanner-buttons {
			margin: 15px 0px;
			display: flex;
			justify-content: space-evenly;
		}
		.cookiebanner-buttons .btn {
			border:none !important;
		}
		@media(max-width:767px) {
			.cookiebanner-buttons .btn {
				font-size:10px;
			}
		}
		.cookiebanner-buttons .btn:hover {
			background-color:#333;
		}
		.cookiebanner-documents {
			text-align:center;
		}
		.cookiebanner-save-preferences {
			display:none;
		}
		</style>
		<script>
			function checkAcceptCookies() {
				if(localStorage.getItem('acceptCookies_<?php echo sha1(TITLEWEB); ?>') == undefined) {
					$('#div-cookies').show();
					$('#btn-div-cookies').hide();
				}else if (localStorage.getItem('acceptCookies_<?php echo sha1(TITLEWEB); ?>') == 'true') {
					gtag('consent', 'update',{
						'ad_storage': 'granted',
						'analytics_storage': 'granted'
					});
					$('#cookiebanner-statistics-optin').prop("checked", true);
					$('#btn-div-cookies').show();
					$('#div-cookies').hide();
				}else if (localStorage.getItem('acceptCookies_<?php echo sha1(TITLEWEB); ?>') == 'false') {
					gtag('consent', 'default', {
						'ad_storage': 'denied',
						'analytics_storage': 'denied'
					});
					$('#cookiebanner-statistics-optin').prop("checked", false);
					$('#btn-div-cookies').show();
					$('#div-cookies').hide();
				} else {
					$('#btn-div-cookies').show();
					$('#div-cookies').hide();
				}
			}
			function acceptCookies() {
				localStorage.acceptCookies_<?php echo sha1(TITLEWEB); ?> = 'true';
				$('#div-cookies').hide();
				$('#btn-div-cookies').show();
				$('#cookiebanner-statistics-optin').prop("checked", true);
				checkAcceptCookies();
				location.reload();
			}
			function cancelCookies() {
				localStorage.acceptCookies_<?php echo sha1(TITLEWEB); ?> = 'false';
				$('#div-cookies').hide();
				$('#btn-div-cookies').show();
				$('#cookiebanner-statistics-optin').prop("checked", false);
				checkAcceptCookies();
			}
			
			$(document).ready(function() {
				$("#btn-div-cookies").click(function(){
					$(this).hide();
					$('#div-cookies').show();
				});
				$(".cookiebanner-close").click(function(){
					$("#btn-div-cookies").show();
					$('#div-cookies').hide();
				});
				$(".cookiebanner-accept").click(function(){
					acceptCookies();
				});
				$(".cookiebanner-deny").click(function(){
					cancelCookies();
				});
				$(".cookiebanner-save-preferences").click(function(){
					if($('#cookiebanner-statistics-optin').is(":checked")) {
						acceptCookies();
					}else {
						cancelCookies();
					}
				});
				$(".cookiebanner-view-preferences").click(function(){
					$(this).css("display","none");
					$(".cookiebanner-categories").fadeIn();
					$(".cookiebanner-save-preferences").css("display","block");
				});
				checkAcceptCookies();
				
			});
		</script>
	<?php endif; //cierre del if line 11 echo stripslashes($google->TEXT); ?>