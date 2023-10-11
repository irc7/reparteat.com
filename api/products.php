<?php
setlocale(LC_TIME, "es_ES");
date_default_timezone_set("Europe/Paris");
require_once ("api.php");
require_once ("../pdc-reparteat/includes/database.php");
$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
require_once ("../pdc-reparteat/includes/config.inc.php");
//require_once ("../includes/functions.inc.php");
require_once ("../pdc-reparteat/includes/functions/global.functions.php");
require_once ("../includes/checkSession.php");
require_once "../includes/class/class.System.php";
require "../includes/class/Zone/class.Zone.php";
require "../includes/class/Supplier/class.Supplier.php";
//require "../includes/class/Supplier/class.CategorySup.php";
//require "../includes/class/Supplier/class.TimeControl.php";
require "../includes/class/Product/class.CategoryPro.php";
require "../includes/class/Product/class.Product.php";
require_once ("../includes/class/Image/class.Image.php");
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
/*use Zone;
use Supplier;
use api;
use Product;
use CategoryPro;*/

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    /*$token = api::checkToken();
    if($token) {*/
        $ObjProduct = new Product();
        if(!isset($_GET["product_id"])) //no trae product_id es listado
        {
            $prod = (object)array();
            $id=$_GET["id"];
            //lista de productos con filtro
            $catFilter = CategoryPro::allCategoriesSupplier($id);
            $catsProdFilterSelect = array();
            foreach($catFilter as $cf){
                $catsProdFilterSelect[] = (object)array("value" => $cf->TITLE.",", "label" => $cf->TITLE);
            }
            if(!isset($_GET["all"]))
                $products_list = $ObjProduct->listProductBySupplier($id);
            else
                $products_list = $ObjProduct->listProductBySupplierAll($id);
            $products_complete = array();
            $p=0;
            foreach($products_list as $pr) {
                $catsPro = $ObjProduct->infoCategories($pr->ID);
                $icons = $ObjProduct->productIcon($pr->ID);
                $coms = $ObjProduct->productComs($pr->ID);
                $txtExtras = '';
                foreach($coms as $c){
                    if($c->TYPE=='optional')
                        $txtExtras.=$c->TITLE." | ";
                }
                $txtCat='';
                $catsProdSelect=array();
                foreach($catsPro as $cp){
                    $txtCat.=$cp->TITLE.",";
                    $catsProdSelect[] = (object)array("value" => $cp->TITLE.",", "label" => $cp->TITLE);
                }
                $products_complete[$p] = $pr;
                $products_complete[$p]->CATS = $catsProdSelect;
                $products_complete[$p]->TXTCATS = $txtCat;
                $products_complete[$p]->IMG = $ObjProduct->productImageFav($pr->ID);
                $products_complete[$p]->EXTRAS = $coms;
                $products_complete[$p]->TXTEXTRAS = $txtExtras;
                $products_complete[$p]->ICONS = $icons;
                $p++;
            }
            if(isset($_GET["category"]) && !empty($_GET["category"]))
            {
                
                $products_filtered=arraY();
                foreach($products_complete as $p)
                {
                    if(strpos($p->TXTCATS,$_GET["category"])>-1)
                    {
                        $products_filtered[]=$p;
                    }
                }
                $products_complete = $products_filtered;
                
            }
            
            $prod->CATS = $catsProdFilterSelect;
            $prod->PRODS = $products_complete;
            http_response_code(200);
            echo json_encode(array($prod));
        } else { //consulta id
            //var_dump($_GET);
            $prod = $ObjProduct->infoProductByIdNoStatus($_GET["product_id"]);
            $prod->IMG = $ObjProduct->productImageFav($_GET["product_id"]);
            $prod->IMAGES = $ObjProduct->productImages($_GET["product_id"]);
            //fix TEXT html tags
            $prod->TEXT = html_entity_decode($prod->TEXT);
            $catsPro = $ObjProduct->infoCategories($_GET["product_id"]);
            $icons = $ObjProduct->productIcon($_GET["product_id"]);
            $coms = $ObjProduct->productComs($_GET["product_id"]);
            $txtExtras = '';
            foreach($coms as $c){
                if($c->TYPE=='basic')
                    $txtExtras.=$c->TITLE.", ";
            }
            $txtCat='';
            $catsProdSelect=array();
            foreach($catsPro as $cp){
                $txtCat.=$cp->TITLE.",";
            }
            $prod->categories = $catsPro;
            $prod->TXTCAT = $txtCat;
            $prod->TXTEXTRAS = $txtExtras;
            $prod->ICONS = $icons;
            $prod->COMS = $coms;
            http_response_code(200);
            echo json_encode($prod);
        }
        
    /*} else {
        api::sendError("Sin acceso");
    }*/
}

//POST METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $product = new Product();
        $product->dateStart = $product->dateStart = new DateTime(date("Y-m-d H:i:s"));
        $product->status = intval($postdata->STATUS);
        $product->title = trim($postdata->TITLE);
        $product->idsupplier = intval($postdata->SUPPLIER);
        $product->cost = floatval($postdata->COST);
        $product->sumary = mysqli_real_escape_string($connectBD, trim($postdata->SUMARY));
        $product->text = mysqli_real_escape_string($connectBD, trim($postdata->TEXT));
        $idNew = $product->add();
        http_response_code(200);
        echo json_encode(array("id" => $idNew));
    } else {
        api::sendError("Sin acceso");
    }
}

//PUT METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $product = new Product();
        $idNew=0;
        $id = $postdata->ID;
        //var_dump($postdata);
        $q = "UPDATE `".preBD."products` SET 
				`IDSUPPLIER`='".$postdata->IDSUPPLIER."',
				`TITLE`='".$postdata->TITLE."',
				`SUMARY`='".$postdata->SUMARY."', 
				`TEXT`='".$postdata->TEXT."', 
				`POSITION`='0',
				`STATUS`='".$postdata->STATUS."',
				`COST`='".$postdata->COST."'
			WHERE ID = " . $id;
		
		if(!checkingQuery($connectBD, $q)) {
			return false;	
		}else {
			//asociaciones
			updateCategories($id, $postdata);
			
			$slug = formatNameUrl($postdata->TITLE);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id . " and TYPE = 'product'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			$q = "UPDATE `".preBD."url_web` SET 
				`SLUG`='".$slug."',
				`SEC_VIEW`='".$postdata->IDSUPPLIER."',
				`TITLE`='".mysqli_real_escape_string($connectBD,$postdata->TITLE)."' 
				WHERE ID_VIEW = '" . $id . "' and TYPE = 'product'";
			checkingQuery($connectBD, $q);
			
			return $id;					
		}
        http_response_code(200);
        echo json_encode(array("id" => $idNew));
    } else {
        api::sendError("Sin acceso");
    }
}

function updateCategories($id = null, $postdata) {
    global $connectBD;
    
    $q = "DELETE FROM `".preBD."products_cat_assoc` WHERE IDPRODUCT = " . $id;
    checkingQuery($connectBD, $q);
    
    for($i=0;$i<count($postdata->categories);$i++) {
        $q = "INSERT INTO `".preBD."products_cat_assoc`(`IDCAT`, `IDPRODUCT`) 
                VALUES 
                (".$postdata->categories[$i]->ID.",".$id.")";
        checkingQuery($connectBD, $q);			
    }
}

//DELETE METHOD: Product images
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $id = $_GET["id"];
        $product = new Product();
        $product->delete($id);
    }
}


?>