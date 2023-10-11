<?php

	function listArticlesHome() {
		global $connectBD;
		$q = "select ".preBD."articles.ID as idA,
					".preBD."articles.TITLE as tsA,
					".preBD."articles.TITLE_SEO as tA,
					".preBD."articles_sections.ID as idS,
					".preBD."articles_sections.TITLE as tS,
					".preBD."url_web.SLUG as slug
					from ".preBD."articles 
					left join ".preBD."articles_sections 
					on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
					left join ".preBD."paragraphs 
					on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and ".preBD."paragraphs.POSITION = 1 
					left join ".preBD."url_web 
					on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = 'article' 
					where true 
					and ".preBD."articles.STATUS = 1 
					and ".preBD."articles.TRASH = 0 
					and ".preBD."articles.DATE_START <= NOW() 
					and (".preBD."articles.DATE_END = '00-00-00 00:00:00' or ".preBD."articles.DATE_END >= NOW())
					and ".preBD."articles.IDSECTION != 9
					and ".preBD."articles.IDSECTION != 10
					and ".preBD."articles.IDSECTION != 11
					order by ".preBD."articles.DATE_START desc";
						
			$r = checkingQuery($connectBD, $q);
			$news = array();
			while($row = mysqli_fetch_object($r)) {
				$news[] = $row;
			}
			return $news;
	}
	
?>