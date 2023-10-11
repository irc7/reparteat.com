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
require "../includes/class/UserWeb/class.UserWeb.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
/*use Zone;
use Supplier;
use api;*/

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        if(empty($_GET)) //no trae id
        {
            $userObj = new UserWeb();
            $suppliersUser = $userObj->getUserWebInfoSupplier($user->ID);
            
            http_response_code(200);
            echo json_encode($suppliersUser);
        } else { //consulta id
            
            http_response_code(200);
            echo json_encode(array());
        }
        
    } else {
        api::sendError("Sin acceso");
    }
}

//POST METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $id = update_all($postdata);
        http_response_code(200);
        echo json_encode(array($id));
    } else {
        api::sendError("Sin acceso");
    }
}

function update_all($postdata){
    global $connectBD;
	$id = $postdata->ID;
    $q = "UPDATE `".preBD."suppliers` SET 
            `TITLE`='".$postdata->TITLE."',
            `PHONE`='".$postdata->PHONE."',
            `TIME`='".$postdata->TIME."',
            `STATUS`='".$postdata->STATUS."'
        WHERE ID = " . $postdata->ID;
    
    if(!checkingQuery($connectBD, $q)) {
        return false;	
    }else {
        //asociaciones
        updateCategories($id, $postdata);
        $slug = formatNameUrl($postdata->TITLE);
        $che = true;
        while($che) {
            $q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id . " and TYPE = 'supplier'";
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
            `TITLE`='".mysqli_real_escape_string($connectBD,$postdata->TITLE)."' 
            WHERE ID_VIEW = '" . $id . "' and TYPE = 'supplier'";
        checkingQuery($connectBD, $q);
        
        return $id;					
    }
}
function updateCategories($id = null, $postdata) {
    global $connectBD;
    
    $q = "DELETE FROM `".preBD."supplier_cat_assoc` WHERE IDSUPPLIER = " . $id;
    checkingQuery($connectBD, $q);
    
    for($i=0;$i<count($postdata->CATSUP);$i++) {
        $q = "INSERT INTO `".preBD."supplier_cat_assoc`(`IDCAT`, `IDSUPPLIER`) 
                VALUES 
                (".$postdata->CATSUP[$i]->IDCAT.",".$id.")";
        checkingQuery($connectBD, $q);			
    }
}

//PUT METHOD: Product images
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $product = new Product();
        $postName = "Image";
        $path = "product";
        $pathoriginal = "original";
        $pathresize = "image";
        $paththumb = "thumb";
        $widthbase = 1366;
        $sizes = array(
            '0' => array ('width' => 900, 'height' => 500),
            '1' => array ('width' => 500, 'height' => 400),
            '2' => array ('width' => 400, 'height' => 400)
        );
        $dirImg = "../files/".$path."/".$pathoriginal."/";
        $name=formatNameFile($postdata->name.".jpg");
        $imgs = array(0 => array("image" => $name));
        $file = fopen($dirImg.$name, "wb");
        //$data = explode(',', $postdata->image);
        //fwrite($file, base64_decode($data[1]));
        fwrite($file, base64_decode($postdata->image));
        fclose($file);
        $dirImgResize = "../files/".$path."/".$pathresize."/";
        resizeImage($dirImg, $dirImgResize, $name, $widthbase, "jpg", 0);
        $dirImgThumb = "../files/" . $path ."/". $paththumb ."/";
        for($i=0;$i< count($sizes);$i++) {
            customImageClass($dirImg, $dirImgThumb, $i+1, $name, "jpg", $sizes[$i]['width'], $sizes[$i]['height']);
        }
        $product->addImages($postdata->id, $imgs);
        http_response_code(200);
        echo json_encode(array("id" => $idNew));
    } else {
        api::sendError("Sin acceso");
    }
}

//DELETE METHOD: Product images
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $id = $_GET["id"];
        //borro las imágenes
        $product = new Product();
		$imgObj = new Image();
		$imgObj->path = "product";
		$imgObj->pathoriginal = "original";
		$imgObj->pathresize = "image";
		$imgObj->paththumb = "thumb";
		$imgObj->sizes = array(
					'0' => array ('width' => 900,
									'height' => 500
					),
					'1' => array ('width' => 500,
									'height' => 400
					),
					'2' => array ('width' => 400,
									'height' => 400
					));
		$imagesBD = $product->productImages($id);
		
		foreach($imagesBD as $imgBD) {
			if($imgBD->URL != "") {
				$url = "../files/".$imgObj->path."/".$imgObj->pathoriginal."/".$imgBD->URL;
				deleteFile($url);
				$url = "../files/".$imgObj->path."/".$imgObj->pathresize."/".$imgBD->URL;
				deleteFile($url);
				for($i=0;$i<count($imgObj->sizes);$i++) {
					$url = "../files/".$imgObj->path."/".$imgObj->paththumb."/".($i+1)."-".$imgBD->URL;
					deleteFile($url);
				}
			}
			$product->deleteImgBD($imgBD->ID);
		}
    }
}

?>