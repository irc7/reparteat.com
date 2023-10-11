<?php
	define ("ROOT",				str_replace("includes", "", dirname(__FILE__)));
	define ("DOMAIN_GLOBAL",	str_replace("usr/", "", str_replace($_SERVER["DOCUMENT_ROOT"], "", str_replace("\\", "/", "http://" . str_replace("//", "/", $_SERVER["SERVER_NAME"] . "/" . ROOT)))));
	define ("DOMAIN_AUX", 		str_replace("/pdc-reparteat", "", DOMAIN_GLOBAL));
	define ("DOMAIN", 			"https://".$_SERVER["HTTP_HOST"]."/");
	//define ("DOMAIN", 			"http://localhost/web/reparteat.com/");
	define ("DOMAINZP", 			DOMAIN."perfil/");
	define ("INCLUDES",			ROOT . "includes/");
	define ("preBD", 			"ree_");
	define ("PDCLOG", 			preBD."pdcLOG");
	define ("SLUGSUP", 			"restaurantes");
	define ("nameSessionZP", 		sha1(preBD."userRee"));
	define ("msgError", 		sha1(preBD."userRee_alert"));
	define ("nameCartReparteat", 		sha1(preBD."cart"));
	define ("nameCookie", 		sha1("#reparteat%"));
	define ("timeCookie", 2592000);//30 dias
	ini_set("include_path",
		"." . PATH_SEPARATOR . 
		ini_get("include_path") . PATH_SEPARATOR .
		ROOT . PATH_SEPARATOR . 
		INCLUDES . PATH_SEPARATOR
	);

	$provincesHTML = array('&Aacute;lava', 'Albacete', 'Alicante','Almer&iacute;a','Asturias','&Aacute;vila','Badajoz','Barcelona','Burgos','C&aacute;ceres','C&aacute;diz','Cantabria','Castell&oacute;n','Ceuta','Ciudad Real','C&oacute;rdoba','Cuenca','Gerona','Las Palmas','Granada','Guadalajara','Guip&uacute;zcoa','Huelva','Huesca','Islas Baleares','Ja&eacute;n','La Coru&ntilde;a','La Rioja','Le&oacute;n','L&eacute;rida','Lugo','Madrid','M&aacute;laga','Melilla','Murcia','Navarra','Ourense','Palencia','Pontevedra','Salamanca','Segovia','Sevilla','Soria','Tarragona','Santa Cruz de Tenerife','Teruel','Toledo','Valencia','Valladolid','Vizcaya','Zamora','Zaragoza');
	$provinces = array('Álava', 'Albacete', 'Alicante','Almería','Asturias','Ávila','Badajoz','Barcelona','Burgos','Cáceres','Cádiz','Cantabria','Castellón','Ceuta','Ciudad Real','Córdoba','Cuenca','Gerona','Las Palmas','Granada','Guadalajara','Guipúzcoa','Huelva','Huesca','Islas Baleares','Jaén','La Coruña','La Rioja','León','Lérida','Lugo','Madrid','Málaga','Melilla','Murcia','Navarra','Ourense','Palencia','Pontevedra','Salamanca','Segovia','Sevilla','Soria','Tarragona','Santa Cruz de Tenerife','Teruel','Toledo','Valencia','Valladolid','Vizcaya','Zamora','Zaragoza');

	$days = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
	$daysNews = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
	$months = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	$monthSmall = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
	
	$q = "select * from ".preBD."configuration where ID = 10";
	$result = checkingQuery($connectBD, $q);
	$configMAILER = mysqli_fetch_object($result);
	
	$mailHOST = explode("#-HOST-#", $configMAILER->TEXT);
	$userHOST = explode("#-USER-#", $configMAILER->TEXT);
	$passHOST = explode("#-PASS-#", $configMAILER->TEXT);
	$portHOST = explode("#-PORT-#", $configMAILER->TEXT);
	$securityHOST = explode("#-SECURITY-#", $configMAILER->TEXT);	
	
	define ("NAMESEND", $configMAILER->TITLE);
	define ("MAILSEND", $configMAILER->AUXILIARY);
	define ("MAILHOST", $mailHOST[1]);
	define ("USERHOST", $userHOST[1]);
	define ("PASSHOST", $passHOST[1]);
	define ("PORTHOST", $portHOST[1]);
	define ("SECURITYHOST", $securityHOST[1]);	
	
	$q = "select ID, VALUE from ".preBD."configuration where ID = 14 or ID = 15";
	$r = checkingQuery($connectBD, $q);
	
	while($config = mysqli_fetch_object($r)) {
		if($config->ID == 14) {
			define ("ESCALE_IMG", $config->VALUE);	
		}elseif($config->ID == 15){
			define ("ESCALE_THUMB", $config->VALUE);	
		}
	}
	
	$q = "select TEXT as title_seo, AUXILIARY as title from ".preBD."configuration where ID = 3";
	$result_title_seo = checkingQuery($connectBD, $q);
	$row_title_seo = mysqli_fetch_object($result_title_seo);	
	define ("TITLEWEB", $row_title_seo->title_seo);
	define ("SHORTTITLE", $row_title_seo->title);
	
	
	
	/*array donde guardamos los modulos globales activos (content, design, blog,...)*/
	$q_cont = "SELECT COUNT(DISTINCT IDMENU) AS cantidad FROM ".preBD."configuration_modules";
	$result_cont = checkingQuery($connectBD, $q_cont);
	$row_cont = mysqli_fetch_array($result_cont);
	$variable_cantidad = $row_cont['cantidad'];
		
		
	$array_header = array();
	for($i=1;$i<=$variable_cantidad;$i++){
		$q = "SELECT COUNT(*) as total FROM ".preBD."configuration_modules WHERE IDMENU = ".$i." and PERMISSION > 0";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$count = $row['total'];
		
		/*array donde guardamos los módulos que están activos y los que no (1 = activo, 0 = inactivo) */
		if($count > 0){
			$array_header[] = 1;
		}else{
			$array_header[] = 0;
		}
		/*array con todos los valores a 1 para comparar con el array anterior y saber qué módulos hay que mostrar */		
		$array_modules[] = 1;
	}
	
	$q = "select AUXILIARY as code from ".preBD."configuration where ID = 16";
	$r=checkingQuery($connectBD, $q);
	
	$corporativeColor = mysqli_fetch_object($r);
	define ("CORPORATIVE_COLOR", $corporativeColor->code);
	
	$q = "select AUXILIARY as token from ".preBD."configuration where ID = 19";
	$r=checkingQuery($connectBD, $q);
	
	$telegram = mysqli_fetch_object($r);
	
	define("TELEGRAMTOKEN", $telegram->token);

	$traducccion = array("Contenidos", "Blog", "Diseño", "Emailing", "Zona Privada", "Estadísticas", "SEO", "Configuración");
	
	if(isset($_SESSION[sha1("zone")])) {
		//pre($_SESSION[sha1("zone")]));
		$q = "select TIME_DELIVERY as timeRepartidor,
					TIME_CHECK_ORDER as timeOrder,
					TIME_ORDERS_ZONES as timeFranjas
				from ".preBD."zone where ID = " .$_SESSION[sha1("zone")];
		$r=checkingQuery($connectBD, $q);
		$zoneControl = mysqli_fetch_object($r);
		define ("timeRe", $zoneControl->timeRepartidor);
		define ("timeOrder", $zoneControl->timeOrder);
		define ("timeFranjas", $zoneControl->timeFranjas);
	}else {
		//tiempo del repartidor
		$q = "select VALUE as timeRepartidor from ".preBD."configuration where ID = 20";
		$r=checkingQuery($connectBD, $q);
		
		$timeRe = mysqli_fetch_object($r);
		define ("timeRe", $timeRe->timeRepartidor);

		//tiempo del pedido
		$q = "select VALUE as timeOrder from ".preBD."configuration where ID = 21";
		$r=checkingQuery($connectBD, $q);
		
		$timeO = mysqli_fetch_object($r);
		define ("timeOrder", $timeO->timeOrder);
		
		//franjas horarias en seg
		$q = "select VALUE as timeFranjas from ".preBD."configuration where ID = 22";
		$r=checkingQuery($connectBD, $q);
		
		$timeF = mysqli_fetch_object($r);
		define ("timeFranjas", $timeF->timeFranjas);
	}
	
	//tiempo del repartidor pedanias
	$q = "select VALUE as timeRepartidorPedanias from ".preBD."configuration where ID = 25";
	$r=checkingQuery($connectBD, $q);
	
	$timeRe = mysqli_fetch_object($r);
	define ("timeRePedanias", $timeRe->timeRepartidorPedanias);
	
	//horarios para distinguir repartos dia y noche
	$q = "select AUXILIARY as timer from ".preBD."configuration where ID = 23";
	$r=checkingQuery($connectBD, $q);
	
	$row = mysqli_fetch_object($r);
	$aux = explode("-", $row->timer);
	define ("REPDAYS", $aux[0]);
	define ("REPDAYF", $aux[1]);

	$q = "select AUXILIARY as timer from ".preBD."configuration where ID = 24";
	$r=checkingQuery($connectBD, $q);
	
	$row = mysqli_fetch_object($r);
	$aux = explode("-", $row->timer);
	define ("REPNIGHTS", $aux[0]);
	define ("REPNIGHTF", $aux[1]);

?>