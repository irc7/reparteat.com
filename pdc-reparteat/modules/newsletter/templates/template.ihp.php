<?php
//#STATISTICS_PARAM# cadena que será sustituida por valores(id suscripcion y id boletin) para el registro estadístico
	$w = 600;
	$contentWidth = 550;
	$thumbWidth = 285;
	
	$bg = "#ededed";
	$white = "#ffffff";
	$black = "#000000";
	$orange = "#ee7d0c";
	$grayStrong = "#333333";
	$gray = "#666666";
	
//Menu redes sociales	
	$idMnuPrincipal = 5;
	$idMnuRedes = 1;
	
	$q_m = "select * from ".preBD."menu_item where LEVEL = 0 and PARENT = 0 and IDMENU = " . $idMnuPrincipal;
	$result_m = checkingQuery($connectBD, $q_m);
	$totalItemP = mysqli_num_rows($result_m);

//Redes sociales
	$rrss = array();
	//facebook
	$qf = "select IDVIEW from ".preBD."menu_item where ID = 5";
	$rf = checkingQuery($connectBD, $qf);
	$facebook = mysqli_fetch_object($rf);
	$rrss[0]['link'] = $facebook->IDVIEW;
	$rrss[0]['image'] = DOMAIN."template/modules/newsletter/images/facebook.png";
	$rrss[0]['image-footer'] = DOMAIN."template/modules/newsletter/images/facebook-footer.png";
	
	//twitter
	$qt = "select IDVIEW from ".preBD."menu_item where ID = 6";
	$rt = checkingQuery($connectBD, $qt);
	$twitter = mysqli_fetch_object($rt);
	$rrss[1]['link'] = $twitter->IDVIEW;
	$rrss[1]['image'] = DOMAIN."template/modules/newsletter/images/twitter.png";
	$rrss[1]['image-footer'] = DOMAIN."template/modules/newsletter/images/twitter-footer.png";
	//linkedin
	$ql = "select IDVIEW from ".preBD."menu_item where ID = 38";
	$rl = checkingQuery($connectBD, $ql);
	$linkedin = mysqli_fetch_object($rl);
	$rrss[2]['link'] = $linkedin->IDVIEW;
	$rrss[2]['image'] = DOMAIN."template/modules/newsletter/images/linked.png";
	$rrss[2]['image-footer'] = DOMAIN."template/modules/newsletter/images/linked-footer.png";

//Textos legales
	$tl = array();
	//facebook
	$qt = "select * from ".preBD."menu_item where ID = 29 or ID = 30";
	$rtl = checkingQuery($connectBD, $qt);
	$total_tl = mysqli_num_rows($rtl);
	
	$dateNow = new DateTime();
//CONSTRUCCION DEL BOLETIN
	$center = "margin-top:0px;margin-left:auto;margin-right:auto;margin-bottom:0px;";
//	$leerMas = '<span style="font-weight:bold;color:'.$colorTitle.';font-family:Arial;">&nbsp;[+]</span>';
$style = '<style type="text/css">
			@import url("https://fonts.googleapis.com/css?family=Oswald|Arimo|Fredoka+One");
			* {text-decoration:none;}
			body {margin:0;padding:0;min-width:100%;background-color:'.$bg.';font-size:14px;}
			table {border-spacing: 0;font-family: sans-serif;}
			td {padding: 0;}
			img {border: 0;}
			.separator {display:block;clear:both;width:100%;height: 0px;}
			.separator5 {display:block;clear:both;width:100%;height: 5px;}
			.separator10 {display:block;clear:both;width:100%;height: 10px;}
			.separator20 {display:block;clear:both;width:100%;height: 20px;}
			.wrapper {background-color:'.$bg.';width: 100%;table-layout: fixed;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;}
			.webkit {max-width: '.$w.'px;background-color:#ffffff;}
			.title{font-family: "Oswald", Arial, Verdana, sans-serif;}
			.text{font-family: "Fredoka One", Tahoma, "Trebuchet MS", sans-serif;}
			.textN{font-family: "Arimo", Tahoma, "Trebuchet MS", sans-serif;}
			.textF{font-family: "Oswald", Tahoma, "Trebuchet MS", sans-serif;}
			p {margin: 0;}
			a {color: #ee7d0c;text-decoration: none;}
			.h1 {font-size: 21px;font-weight: bold;margin: 0px;}
			.h2 {font-size: 18px;font-weight: bold;margin: 0px;text-align:center;}
			/*column layout */
			.one-column .contents {text-align: left;}
			.two-column {text-align: center;font-size: 0;}
			.two-column .column {width: 100%;max-width: 285px;display: inline-block;vertical-align: top;text-align:left;}
		/* Windows Phone Viewport Fix */
			@-ms-viewport { 
				width: device-width; 
			}
			.outer {margin: 0 auto;width: 100%;max-width: '.$w.'px;}
			.outer-padding {margin: 0 auto;width: 100%;padding:30px 15px;}
			.outer-padding-footer {margin: 0 auto;width: 100%;padding:15px 15px;}
			.wrap-header {text-align:right;padding-right:20px;padding-top:15px;background-color:#ffffff;max-width:'.$w.'px;width:100%;height:245px;display:block;background-image:url('.DOMAIN.'template/modules/newsletter/images/header.png);background-repeat:no-repeat;background-size:cover;}
			.title-newsletter {margin:0px;font-family:"Fredoka One", Tahoma, "Trebuchet MS", sans-serif;color:#ee7d0c;font-size:35px;font-weight:400;}
			.logo-header{width:100px;height:auto;}
			.wrap-mnu{background-color:'.$orange.';width:100%;height:40px;clear:both;line-height:40px;}
			.link-mnu{padding-left:5px;padding-right:5px;text-decoration:none !important;color:#ffffff;font-family: "Oswald", Arial, Verdana, sans-serif;font-weight:400;font-size:15px;line-height:40px;}
			.img-blog{width:100%;max-width:285px;}
			.ii a[href] {color:#ffffff !important;text-decoration:none!important;}
			.info-post{width:275px;height:auto;'.$padding.'margin:0 auto;}
			@media only screen and (min-device-width: 400px){}
		</style>';	
$codeStart = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta http-equiv="X-UA-Compatible" content="IE=edge" />
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						<title></title>
						<!--[if (gte mso 9)|(IE)]>
						<style type="text/css">
							table {border-collapse: collapse;}
						</style>
						<![endif]-->
						'.$style.'
					</head>
					<body style="margin:0;padding:0;min-width:100%;background-color:'.$bg.';">
						<center class="wrapper" style="background-color:'.$bg.';width: 100%;table-layout: fixed;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
							<div class="webkit" style="max-width: '.$w.'px;background-color:#ffffff;">
								<!--[if (gte mso 9)|(IE)]>
								<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
								<tr>
								<td>
								<![endif]-->';
				$headerTemplate	= '	<table class="outer" align="center" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;width: 100%;max-width: '.$w.'px;">
										<tr>
											<td class="one-column">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td>
															<div class="wrap-header" style="text-align:right;padding-right:20px;padding-top:15px;background-color:#ffffff;width:580px;height:245px;display:block;background-image:url('.DOMAIN.'template/modules/newsletter/images/header.png);background-repeat:no-repeat;background-size:cover;">
																<a href="'.DOMAIN.'#STATISTICS_PARAM#" title="Ir a ihppediatria.com" style="text-decoration:none;">
																	<img class="logo-header" src="'.DOMAIN.'template/images/logo-header.png" style="width:100px;height:auto;"/>
																</a>
																<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
																<p class="title-newsletter text orange" style="margin:0px;font-family:\'Fredoka One\', Tahoma, \'Trebuchet MS\', sans-serif;color:'.$orange.';font-size:35px;font-weight:400;">boletín digital</p>
																<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>';
															for($i=0;$i<count($rrss);$i++)	 {
			$headerTemplate .= '								<a href="'.$rrss[$i]['link'].'" style="text-decoration:none;">
																	<img src="'.$rrss[$i]['image'].'" style="width:25px;height:25px;" />
																</a>';
															}
			$headerTemplate .= '								<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
																<p class="title" style="font-family: \'Oswald\', Arial, Verdana, sans-serif;color:'.$orange.';font-size:12px;margin:0px;">
																'.$days[($Date->format('N')-1)].', '.$Date->format('d').' de '.$months[(intval($Date->format('m'))-1)].' de '.$Date->format('Y').'
																</p>
															</div>
															<div class="wrap-mnu" style="background-color:'.$orange.';width:100%;height:40px;">';
																while($item = mysqli_fetch_object($result_m)) {
																	$titleItem = manualUpper(utf8_encode(strtoupper(utf8_decode($item->TITLE))));
																	//$headerTemplate .= '<div width="50" height="40" style="width:50px;height:40px;vertical-align:middle;text-align:center;padding:0px;color:'.$white.'">';
																		$headerTemplate .= constructItemMenu($titleItem, $item->TYPE, $item->IDVIEW, $item->TARGET, $item->DISPLAY, $item->THUMBNAIL, "header"). "\r\n";
																	//$headerTemplate .= '</div>';
																}
				$headerTemplate .= '						</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->';
				$Template = '';
			if(count($listNews)> 0){
				$Template .= '<!--[if (gte mso 9)|(IE)]>
								<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
								<tr>
								<td>
								<![endif]-->
									<table class="outer" align="center" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;width: 100%;max-width: '.$w.'px;background-color:#ffffff;">
										<tr>
											<td class="one-column">
												<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
												<p class="h2 title" style="font-family: \'Oswald\', Arial, Verdana, sans-serif;font-size: 18px;font-weight: bold;margin: 0px;text-align:center;color:'.$grayStrong.';">NOTICIAS <span style="color:'.$orange.'">IHP</span></p>
											</td>
										</tr>
										<tr><td class="two-column" style="text-align: center;font-size: 0;"><div class="separator20" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div></td></tr>';
				for($i=0;$i<count($listNews);$i++) {	
					$dateNews = new Datetime($listNews[$i]["dateStart"]);
					$link1 = '<a href="'.DOMAIN.$listNews[$i]["slug"].'#STATISTICS_PARAM#" alt="'.$listNews[$i]["title"].'" title="'.$listNews[$i]["title"].'" style="text-decoration:none;">';
					$link2 = '</a>';
					$image = '<img class="img-blog" src="'.DOMAIN.'files/articles/thumb/'.$listNews[$i]["image"].'" title="'.$listNews[$i]["title"].'" style="width:100%;max-width:275px;margin:0 auto;" />';
					if($i%2==0){
						$padding = "padding-right:5px;";
					}else{
						$padding = "padding-left:5px;";
					}
					$info = '<div class="info-post" style="width:275px;height:auto;'.$padding.'margin:0 auto;">
									'.$link1.$image.$link2.'
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="date-post" style="line-height:20px;">
										<span class="title" style="color:#666666;font-size:12px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">
										'.$dateNews->format("d/m/Y").'
										</span>
									</div>
									<div class="title-post title" style="display:block;clear:both;color:#333333;line-height:20px;font-size:17px;">
										'.$link1.'
										<span class="title" style="color:#333333;font-size:17px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">'.$listNews[$i]["title"].'</span>
										'.$link2.'
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="text-post textN" style="display:block;clear:both;color:#666666;line-height:13px;font-size:11px;">
										<span class="textN" style="color:#666666;font-size:11px;">'.$listNews[$i]["sumary"].'</span>
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="read-more" style="text-align:right;font-family: \'Oswald\', Arial, Verdana, sans-serif;">
									'.$link1.'
										<span class="title" style="color:'.$orange.';font-size:12px;">LEER</span>&nbsp;<img src="'.DOMAIN.'template/images/leer-mas.png" style="vertical-align:bottom;width:17px;margin-left:5px;">
									'.$link2.'
									</div>
								</div>
								<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>';
					if($i%2==0){
					$Template .= '		<tr>
											<td class="two-column" style="text-align: center;font-size: 0;">
												<!--[if (gte mso 9)|(IE)]>
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
												<tr>
												<td width="50%" valign="top">
												<![endif]-->';
					}						
											
					$Template .= '					<div class="column" style="width: 100%;max-width: 285px;display: inline-block;vertical-align: top;text-align:left;margin:0 auto;">
														<table width="100%" cellpadding="0" cellspacing="0" border="0">
															<tr>
																<td class="inner">
																	'.$info.'
																</td>
															</tr>
														</table>
													</div>';
													
					if($i%2==0){								
					$Template .= '				<!--[if (gte mso 9)|(IE)]>
												</td>
												
												<td width="50%" valign="top">
												<![endif]-->';
					}								
					if($i%2!=0){								
					$Template .= '				<!--[if (gte mso 9)|(IE)]>
												</td>
												</tr>
												</table>
												<![endif]-->';
												
												
					$Template .= '			</td>
										</tr>';
					}							
				}
				$Template .= '			<tr>
											<td class="one-column">
												<div class="separator20" style="width:100%;height:20px;display:block;clear:both;">&nbsp;</div>
													<center>
													<div class="button title" style="font-family: \'Oswald\', Arial, Verdana, sans-serif;width:200px;line-height:16px;text-align:center;color:#ffffff;background-color:#333333;font-size:14px;margin:0 auto;padding:10px 30px;">
														<a href="'.DOMAIN.$listNews[0]["section"]["slug"].'#STATISTICS_PARAM#" title="Blog IHP" style="text-decoration:none;font-family: \'Oswald\', Arial, Verdana, sans-serif;line-height:16px;text-align:center;color:#ffffff;font-size:14px;">
															IR A '.manualUpper(utf8_encode(strtoupper(utf8_decode($listNews[0]["section"]["title"])))).'
														</a>
													</div>
													</center>
												<div class="separator20" style="width:100%;height:20px;display:block;clear:both;">&nbsp;</div>
											</td>
										</tr>
									</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->';
				$Template .= '<div class="separator" style="padding:0px 20px;max-width:520px;margin:0 auto;width:100%;height:0px;clear:both;display:block;border-top:1px solid '.$orange.';">&nbsp;</div>';
			}
			if(count($listBlog)> 0){
				$Template .= '<!--[if (gte mso 9)|(IE)]>
								<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
								<tr>
								<td>
								<![endif]-->
									<table class="outer" align="center" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;width: 100%;max-width: '.$w.'px;background-color:#ffffff;">
										<tr>
											<td class="one-column">
												<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
												<p class="h2 title" style="font-family: \'Oswald\', Arial, Verdana, sans-serif;font-size: 18px;font-weight: bold;margin: 0px;text-align:center;color:'.$grayStrong.';">BLOG <span style="color:'.$orange.'">IHP</span></p>
											</td>
										</tr>
										<tr><td class="two-column" style="text-align: center;font-size: 0;"><div class="separator20" style="display:block;clear:both;width:100%;height:20px;">&nbsp;</div></td></tr>';
				for($i=0;$i<count($listBlog);$i++) {	
					$datePost = new Datetime($listBlog[$i]["dateStart"]);
					$link1 = '<a href="'.$listBlog[$i]["slug"].'#STATISTICS_PARAM#" alt="'.$listBlog[$i]["title"].'" title="'.$listBlog[$i]["title"].'" style="text-decoration:none;">';
					$link2 = '</a>';
					$image = $link1 . '<img class="img-blog" src="'.DOMAIN.'files/articles/thumb/'.$listBlog[$i]["image"].'" title="'.$listBlog[$i]["title"].'" style="width:100%;max-width:285px;margin:0 auto;" />'.$link2;
					$info = '<div class="info-post" style="position:relative;background-color:#ededed;width:265px;height:138px;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;margin:0 auto;">
									<div class="date-post" style="width:50%;float:left;line-height:20px;">
										<span class="title" style="color:#666666;font-size:12px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">'.$datePost->format("d/m/Y").'</span>
									</div>
									<div class="setcion-post" style="width:50%;float:right;color:#666666;line-height:20px;text-align:right;">
										<span class="title" style="color:#666666;font-size:12px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">'.manualUpper(utf8_encode(strtoupper(utf8_decode($listBlog[$i]["section"]["title"])))).'</span>
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="title-post title" style="display:block;clear:both;color:#333333;line-height:20px;font-size:17px;">
										'.$link1.'
										<span class="title" style="color:#333333;font-size:17px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">'.$listBlog[$i]["title"].'</span>
										'.$link1.'
									</div>
									<div class="separator5" style="display:block;clear:both;width:100%;height:5px;">&nbsp;</div>
									<div class="text-post textN" style="display:block;clear:both;color:#666666;line-height:13px;font-size:11px;">
										<span class="textN" style="color:#666666;font-size:11px;">'.$listBlog[$i]["sumary"].'</span>
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="read-more" style="text-align:right;font-family: \'Oswald\', Arial, Verdana, sans-serif;">
									'.$link1.'
										<span class="title" style="color:'.$orange.';font-size:12px;">LEER</span>&nbsp;<img src="'.DOMAIN.'template/images/leer-mas.png" style="vertical-align:bottom;width:17px;margin-left:5px;">
									'.$link2.'
									</div>
								</div>
								<div class="separator10" style="width:100%;height:10px;display:block;clear:both;">&nbsp;</div>';
					if($i%2==0){
						$left = $image;
						$right = $info;
					}else {
						$left = $info;
						$right = $image;
					}
					
					
					
					$Template .= '		<tr>
											<td class="two-column" style="text-align: center;font-size: 0;">
												<!--[if (gte mso 9)|(IE)]>
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
												<tr>
												<td width="50%" valign="top">
												<![endif]-->
													<div class="column" style="width: 100%;max-width: 285px;display: inline-block;vertical-align: top;text-align:left;margin:0 auto;">
														<table width="100%" cellpadding="0" cellspacing="0" border="0">
															<tr>
																<td class="inner">
																	'.$left.'
																</td>
															</tr>
														</table>
													</div>
												<!--[if (gte mso 9)|(IE)]>
												</td>
												<td width="50%" valign="top">
												<![endif]-->
													<div class="column" style="width: 100%;max-width: 285px;display: inline-block;vertical-align: top;text-align:left;margin:0 auto;">
														<table width="100%" cellpadding="0" cellspacing="0" border="0">
															<tr>
																<td class="inner">
																	'.$right.'
																</td>
															</tr>
														</table>
													</div>												
												<!--[if (gte mso 9)|(IE)]>
												</td>
												</tr>
												</table>
												<![endif]-->
											</td>
										</tr>';
				}
				$Template .= '			<tr>
											<td class="one-column">
												<div class="separator10" style="width:100%;height:10px;display:block;clear:both;">&nbsp;</div>
												<center>
													<div class="button title" style="font-family: \'Oswald\', Arial, Verdana, sans-serif;width:200px;line-height:16px;text-align:center;color:#ffffff;background-color:#333333;font-size:14px;margin:0 auto;padding:10px 30px;text-decoration:none;">
														<a href="'.DOMAINBLOG.'#STATISTICS_PARAM#" title="Blog IHP" style="text-decoration:none;font-family: \'Oswald\', Arial, Verdana, sans-serif;line-height:16px;text-align:center;color:#ffffff;font-size:14px;">
															IR A BLOG IHP
														</a>
													</div>
												</center>
												<div class="separator20" style="width:100%;height:20px;display:block;clear:both;">&nbsp;</div>
											</td>
										</tr>
									</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->';
				$Template .= '<div class="separator" style="padding:0px 20px;max-width:520px;margin:0 auto;width:100%;height:0px;clear:both;display:block;border-top:1px solid '.$orange.';">&nbsp;</div>';
			}
			if(count($listNewsF)> 0){
				$Template .= '<!--[if (gte mso 9)|(IE)]>
								<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
								<tr>
								<td>
								<![endif]-->
									<table class="outer" align="center" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;width: 100%;max-width: '.$w.'px;background-color:#ffffff;">
										<tr>
											<td class="one-column">
												<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
												<p class="h2 title"  style="font-family: \'Oswald\', Arial, Verdana, sans-serif;font-size: 18px;font-weight: bold;margin: 0px;text-align:center;color:'.$grayStrong.';">FUNDACIÓN <span style="color:'.$orange.';">IHP</span></p>
											</td>
										</tr>
										<tr><td class="two-column" style="text-align: center;font-size: 0;"><div class="separator20" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div></td></tr>';
				for($i=0;$i<count($listNewsF);$i++) {	
					$dateNewsF = new Datetime($listNewsF[$i]["dateStart"]);
					$link1 = '<a href="'.DOMAINFUNDACION.$listNewsF[$i]["slug"].'#STATISTICS_PARAM#" alt="'.$listNewsF[$i]["title"].'" title="'.$listNewsF[$i]["title"].'" style="text-decoration:none;">';
					$link2 = '</a>';
					$image ='<img class="img-blog" src="'.DOMAIN.'files/articles/thumb/'.$listNewsF[$i]["image"].'" title="'.$listNewsF[$i]["title"].'" style="width:100%;max-width:265px;margin:0 auto;" />';
					$info = '<div class="info-post" style="width:265px;height:auto;padding:5px;margin:0 auto;border:1px solid #ededed;">
									<div class="date-post" style="line-height:20px;">
										<span class="title" style="color:#666666;font-size:12px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">
										'.$dateNewsF->format("d/m/Y").'
										</span>
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="title-post title" style="display:block;clear:both;color:#333333;line-height:20px;font-size:17px;">
										'.$link1.'<span class="title" style="color:#333333;font-size:17px;font-family: \'Oswald\', Arial, Verdana, sans-serif;">'.$listNewsF[$i]["title"].'</span>'.$link2.'
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									'. $link1.$image. $link2.'
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="text-post textN" style="display:block;clear:both;color:#666666;line-height:13px;font-size:11px;">
										<span class="textN" style="color:#666666;font-size:11px;">'.$listNewsF[$i]["sumary"].'</span>
									</div>
									<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
									<div class="read-more" style="text-align:right;font-family: \'Oswald\', Arial, Verdana, sans-serif;">
										'. $link1.'
											<span class="title" style="color:'.$orange.';font-size:12px;">LEER</span>&nbsp;<img src="'.DOMAIN.'template/images/leer-mas.png" style="vertical-align:bottom;width:17px;margin-left:5px;">
										'. $link2.'
									</div>
								</div>';
					if($i%2==0){
					$Template .= '		<tr>
											<td class="two-column" style="text-align: center;font-size: 0;">
												<!--[if (gte mso 9)|(IE)]>
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
												<tr>
												<td width="50%" valign="top">
												<![endif]-->';
					}						
											
					$Template .= '					<div class="column" style="width: 100%;max-width: 285px;display: inline-block;vertical-align: top;text-align:left;margin: 0 auto;">
														<table width="100%" cellpadding="0" cellspacing="0" border="0">
															<tr>
																<td class="inner">
																	'.$info.'
																</td>
															</tr>
														</table>
													</div>';
													
					if($i%2==0){								
					$Template .= '				<!--[if (gte mso 9)|(IE)]>
												</td>
												
												<td width="50%" valign="top">
												<![endif]-->';
					}								
					if($i%2!=0){								
					$Template .= '				<!--[if (gte mso 9)|(IE)]>
												</td>
												</tr>
												</table>
												<![endif]-->';
												
												
					$Template .= '			</td>
										</tr>';
					}							
				}
				$Template .= '			<tr>
											<td class="one-column">
												<div class="separator20" style="width:100%;height:20px;display:block;clear:both;">&nbsp;</div>
													<div class="button title" style="font-family: \'Oswald\', Arial, Verdana, sans-serif;text-decoration:none;width:250px;line-height:16px;text-align:center;color:#ffffff;background-color:#333333;font-size:14px;margin:0 auto;padding:10px 30px;">
														<a href="'.DOMAINFUNDACION.$listNewsF[0]["section"]["slug"].'#STATISTICS_PARAM#" title="Blog IHP" style="text-decoration:none;font-family: \'Oswald\', Arial, Verdana, sans-serif;text-decoration:none;line-height:16px;text-align:center;color:#ffffff;font-size:14px;">
															IR A NOTICIAS FUNDACIÓN IHP
														</a>
													</div>
												<div class="separator20" style="width:100%;height:20px;display:block;clear:both;">&nbsp;</div>
											</td>
										</tr>
									</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->';
			}
			
				$Template .= '<!--[if (gte mso 9)|(IE)]>
								<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
								<tr>
								<td>
								<![endif]-->
									<table class="outer-padding-footer" align="center" cellpadding="0" cellspacing="0" border="0" style="background-color:#333333;">
										
										<tr>
											<td class="one-column">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td>
															<div class="footer-info" style="width:100%;height:auto;text-align:center;">
																<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
																<center>
																	<a href="'.DOMAIN.'#STATISTICS_PARAM#" title="Ir a ihppediatria.com" style="text-decoration:none;">
																		<img src="'.DOMAIN.'template/images/logo-header.png" style="width:100px;height:auto;margin:0px auto;" />
																	</a>
																	<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
																	<div class="footer-title" style="color:'.$orange.';font-size:13px;">
																		
																		<span style="font-family: "Oswald", Arial, Verdana, sans-serif;letter-spacing: 0;">CENTRO DE ESPECIALIDADES PEDIÁTRICAS</span>
																	</div>
																	<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
																	<div class="footer-info" style="color:#ffffff !important;font-size:12px;font-family: \'Arimo\', Tahoma, \'Trebuchet MS\', sans-serif;text-decoration:none !important;">
																		<a href="https://maps.google.com/?q=Calle+Jard%C3%ADn+de+la+Isla,+6&entry=gmail&source=g" style="color:#ffffff !important;text-decoration:none !important;">
																			Calle Jardín de la Isla, 6 Edificio Expolocal, Sevilla - ESPAÑA
																		</a>
																		<br>
																		tel. <a href="tel:tel:+34954610022" style="color:#ffffff !important;text-decoration:none !important;">(+34) 954 610 022 - 30 líneas</a> | fax. 954 690 155
																		<br>
																		<br>
																	</div>
																	<div style="margin-bottom:10px;">';
																	for($i=0;$i<count($rrss);$i++)	 {
						$Template .= '									<a href="'.$rrss[$i]['link'].'" style="text-decoration:none;">
																			<img src="'.$rrss[$i]['image-footer'].'" style="width:25px;height:25px;" />
																		</a>';
																	}
						$Template .= '								</div>
																	<div style="font-size:12px;font-family: \'Arimo\', Tahoma, \'Trebuchet MS\', sans-serif;margin-top:10px;margin-bottom:10px;">
																		<a href="mailto:atencionalpaciente@ihppediatria.com" style="color:'.$orange.' !important;text-decoration:none !important;">
																			atencionalpaciente@ihppediatria.com
																		</a>
																	</div>
																	<div style="color:#ffffff;font-size:12px;font-family: \'Arimo\', Tahoma, \'Trebuchet MS\', sans-serif;text-decoration:none;">
																		Usted recibe este email porque está suscrito al Boletín Digital IHP. Por favor, incluya la dirección <a href="#" style="color:#ffffff !important;text-decoration:none !important;pointer-events: none;cursor: default;">noresponder@ihppediatria.com</a> en su agenda de contactos para evitar que el boletín sea identificado como correo no deseado.
																	</div>
																	<div style="margin-top:10px;color:#ffffff;">';
																	$cont=1;
																	while($tl = mysqli_fetch_object($rtl)) {
																		$Template .= constructItemMenu($tl->TITLE, $tl->TYPE, $tl->IDVIEW, $tl->TARGET, $tl->DISPLAY, $tl->THUMBNAIL, "footer");
																		if($cont<$total_tl) {
																			$Template .= ' | ';
																		}
																		$cont++;
																	}
						$Template .= '								</div>
																</center>
																<div class="separator10" style="display:block;clear:both;width:100%;height:10px;">&nbsp;</div>
															</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								<!--[if (gte mso 9)|(IE)]>
								</td>
								</tr>
								</table>
								<![endif]-->';
$codeFinish = '				</div>
						</center>
					</body>
				</html>';
	

$completeTemplate = $codeStart . $headerTemplate . $Template . $style . $codeFinish;
		
?>				