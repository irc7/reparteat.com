<?php
setlocale(LC_TIME, "es_ES");
date_default_timezone_set("Europe/Paris");
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class api {

	public function __construct() {

    }
    public function generateToken($user) {
        $secret_key = "22842CB5811E8"; //128bits
        $issuer_claim = "reparteat.com"; // this can be the servername
        $audience_claim = "app";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 0; //not before in seconds
        $expire_claim = $issuedat_claim + 31557600; // expire time in seconds 1 yearaprox
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $user->ID,
                "name" => $user->NAME,
                "surname" => $user->SURNAME,
                "email" => $user->LOGIN
        ));
        return JWT::encode($token, $secret_key);
    }
    public function sendError($message) {
        http_response_code(401);
        echo json_encode(array("message" => $message));
    }
    public function checkToken() {
        $secret_key = "22842CB5811E8";
        $jwt = null;
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if($authHeader) {
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1];
            if($jwt){
                try {
                    $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
                    return $decoded;
                }catch (Exception $e){
                    echo $e;
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}
?>