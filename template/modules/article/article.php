<?php	
	if ($id != 0) {
		//Consulta del Articulo 
		
			$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tA,
				".preBD."articles.TITLE_SEO as tsA,
				".preBD."articles.FIRM as firm,
				".preBD."articles.SUBTITLE as sbA,
				".preBD."articles.INTRO as iA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as imageA,
				".preBD."articles_sections.ID as idS,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."articles_sections.THUMBNAIL as imageS,
				".preBD."articles_sections.IMAGE_LR as size_LR,
				".preBD."articles_sections.IMAGE_C as size_C,
				".preBD."url_web.SLUG as slug
				from ".preBD."articles 
				left join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
				left join ".preBD."url_web 
				on ".preBD."url_web.ID_VIEW = ".preBD."articles.ID and ".preBD."url_web.TYPE = 'article'
				where ".preBD."articles.ID = ".$id." 
				and (".preBD."articles.STATUS = 1 or ".preBD."articles.STATUS = 2)
				and ".preBD."articles.TRASH = 0 
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
			
			$rA = checkingQuery($connectBD,$q);
			$view_article = mysqli_fetch_object($rA);
			//PARRAFO	
			$q_p = "select * from ".preBD."paragraphs where IDARTICLE = " . $id . " order by POSITION asc";
			if (!checkingQuery($connectBD,$q_p)) {
				die('Error: '.mysqli_error());
			}
			$result_p = checkingQuery($connectBD,$q_p);
			$totalParagraphs = mysqli_num_rows($result_p);
		
			$dateArt = new DateTime($view_article->dateA);
			require_once ("template/modules/article/template.article.php");
	} else {
		//Consulta de la Sección
	
		$q_sec = "select ".preBD."articles_sections.*,
					".preBD."url_web.SLUG
					from ".preBD."articles_sections
					left join ".preBD."url_web on ".preBD."articles_sections.ID = ".preBD."url_web.SEC_VIEW 
					where ".preBD."articles_sections.ID = " . $section;
		if (!checkingQuery($connectBD,$q_sec)) {
			die('Error BD1: '.mysqli_error());
		}
		$result_sec = checkingQuery($connectBD,$q_sec);
		$sectionBD = mysqli_fetch_object($result_sec);
		$view_articles = $sectionBD->VIEW_ARTICLES;
		
		if(isset($_GET["filter"])) {
			$filter = trim($_GET["filter"]);
		}else{
			$filter = "datestart";
		}
		if (!isset($_GET["page"])){
			$page = 1;
		} else {
			$page = $_GET["page"];
		}
	
		$start = ($page - 1) * $view_articles;
		$finish = $view_articles;
		
		$q_total = "select count(*) as Total from ".preBD."articles where true 
					and IDSECTION = " . $section . " 
					and STATUS = 1 
					and TRASH = 0 
					and TYPE = 'article'
					and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
		$result_total = checkingQuery($connectBD,$q_total);
		$row_total = mysqli_fetch_object($result_total);
		
		$total_articles = $row_total->Total;
	
		$num_pages = ceil($total_articles / $view_articles);
		
		$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tsA,
				".preBD."articles.TITLE_SEO as tA,
				".preBD."articles.FIRM as fmA,
				".preBD."articles.SUMARY as resA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as image,
				".preBD."articles_sections.ID as idS,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."articles_sections.DESCRIPTION as textS,
				".preBD."articles_sections.THUMB_WIDTH as image_W,
				".preBD."articles_sections.THUMB_HEIGHT as image_H,";
			if($filter == "statistics"){				
				$q .= " COALESCE(SUM(".preBD."statistics_content.VISITS), 0) as suma,";
			}
			$q.= preBD."paragraphs.TYPE as icon,
				".preBD."paragraphs.TEXT as text,
				".preBD."url_web.SLUG as slug
				from ".preBD."articles 
				left join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
				left join ".preBD."paragraphs 
				on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and ".preBD."paragraphs.POSITION = 1 
				left join ".preBD."url_web 
				on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = '".$sectionBD->TYPE."'"; 
			$whereStart = " where true and ".preBD."articles.TYPE = '" . $sectionBD->TYPE . "'
							and ".preBD."articles.IDSECTION = " . $section . "
							and ".preBD."articles.STATUS = 1 and ".preBD."articles.TRASH = 0 
							and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
			if($filter == "datestart"){
				$q .= $whereStart . " order by DATE_START desc";
			}else if($filter == "statistics"){
				$q .= " left join ".preBD."statistics_content ON ".preBD."articles.ID = ".preBD."statistics_content.IDCONTENT ";
				$q .= $whereStart . " and ".preBD."statistics_content.TYPE = 'article' GROUP BY ".preBD."articles.ID HAVING suma > 0 order by suma desc, DATE_START desc";
			}	
			$q .= " limit ".$start.", ". $finish;
		$r = checkingQuery($connectBD,$q);
		
		if($section == 8) {
			require_once ("template/modules/article/template.tecnicas.php");
		}else {
			require_once ("template/modules/article/template.section.php");
		}
		
	}
	
?>