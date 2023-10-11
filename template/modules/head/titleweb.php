<?php 
	$titleWEB = "";
	if($view == "article" || $view == "blog") {
		if($id == 0 && $section != 0) {
			$q = "select ".preBD."articles_sections.TITLE as title, 
						".preBD."articles_sections.TITLE_SEO as title_seo,
						".preBD."url_web.SLUG as slug
					from ".preBD."articles_sections 
					left join ".preBD."url_web 
					on ".preBD."articles_sections.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = 'section' 
					where ".preBD."articles_sections.ID = " . $section;
			
			$result_title_seo = checkingQuery($connectBD,$q);
			$row_title_seo = mysqli_fetch_object($result_title_seo);
			if($row_title_seo->title_seo == "") {
				$titleWEB = stripslashes($row_title_seo->title);
			}else {
				$titleWEB = stripslashes($row_title_seo->title_seo);
			}
		} elseif($id == 0 && $section == 0) {
			$titleWEB = "Reparteat";
		} else {
			$q = "select ".preBD."articles.TITLE as title, 
					".preBD."articles.TITLE_SEO as title_seo, 
					".preBD."articles.THUMBNAIL, 
					".preBD."articles.SUMARY, 
					".preBD."articles.ID, 
					".preBD."articles.IDSECTION, 
					".preBD."url_web.SLUG as slug
					from ".preBD."articles 
					left join ".preBD."url_web 
					on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and (".preBD."url_web.TYPE = 'article' or ".preBD."url_web.TYPE = 'blog')
					where ".preBD."articles.ID = " . $id;
			
			$result_title_seo = checkingQuery($connectBD,$q);
			$row_title_seo = mysqli_fetch_object($result_title_seo);
			
			if($row_title_seo->title_seo == "") {
				$titleWEB = stripslashes($row_title_seo->title);
			}else {
				$titleWEB = stripslashes($row_title_seo->title_seo);
			}
			$q2 = "select TITLE as title from ".preBD."articles_sections where ID = " . $row_title_seo->IDSECTION;
			
			if(!$r2 = checkingQuery($connectBD,$q2)) {
				die("Error(section seo): " . mysqli_error());
			}
			$row = mysqli_fetch_object($r2);
			$titleWEB .= " | " . stripslashes($row->title);
		}
	}elseif($view == "product") {	
			$q = "select ".preBD."products.TITLE as title, 
					".preBD."products.TEXT as SUMARY, 
					".preBD."products.ID, 
					".preBD."product_images.URL as THUMBNAIL,
					".preBD."url_web.SLUG as slug
					from ".preBD."products 
					left join ".preBD."product_images 
					on ".preBD."products.ID = ".preBD."product_images.IDASSOC and ".preBD."product_images.FAV = 1 
					left join ".preBD."url_web 
					on ".preBD."products.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = 'product' 
					where ".preBD."products.ID = " . $id;
			
			$result_title_seo = checkingQuery($connectBD,$q);
			$row_title_seo = mysqli_fetch_object($result_title_seo);
			$row_title_seo->SUMARY = strip_tags($row_title_seo->SUMARY);
			$titleWEB = stripslashes($row_title_seo->title);
			
			$titleWEB .= " | " . TITLEWEB;
		
	}elseif($view == "order") {		
		if(isset($_GET["ref"])) {
			$titleWEB = "Confirmación del pedido";	
		}else {
			$titleWEB = "Detalles del pedido";
		}
	}elseif($view == "tpv") {		
		$titleWEB = "TPV Virtual | BBVA";
	}elseif($view == "home") {		
		$titleWEB = TITLEWEB;
//	}elseif($view == "newsletter") {		
//		$titleWEB = "Suscripción boletín digital El Consumidor Seguro";
//	}elseif($view == "search") {		
//		$titleWEB = "Buscador web El Consumidor Seguro";
	
	}elseif(isset($_GET["slugbd"])) {
		$titleWEB = strip_tags($titulo);	
	}else {
		$row_title_seo->title_seo = ucfirst($view);
		$titleWEB = ucfirst($view);
	}
?>