<?php
/*
Author: info@ismaelrc.es
Date: 2019-08-26

Usuario
*/

class Article extends System {
	public function __construct() {
	}
	public function infoArticleById($id = null) {
		global $connectBD;
		
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
				where ".preBD."articles.ID = ".$id." ";
		$res = checkingQuery($connectBD, $q);
		
		if($data = mysqli_fetch_object($res)) {
			return $data;
		} else {
			return false;
		}
	}
}