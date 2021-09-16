<?php

##########################control error##############################################

#Descripcion : Reportar Errores, 0 Omitir errores, 1 Reportar Errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
//var_dump($_SERVER['DOCUMENT_ROOT']);
######################## RUTAS #######################################################

$CARPETA_BASE		= 'ExamenGeniat';
$LIB		= $_SERVER['DOCUMENT_ROOT']."/".$CARPETA_BASE."/lib/*.class.php";
$MODEL		= $_SERVER['DOCUMENT_ROOT']."/".$CARPETA_BASE."/model/*.class.php";
$CONTROLLER	= $_SERVER['DOCUMENT_ROOT']."/".$CARPETA_BASE."/controller/*.class.php";
$RUTA_IMAGENES = $_SERVER['DOCUMENT_ROOT']."/".$CARPETA_BASE."/img";

################ Librerias, Objetos y Funciones ##############################


if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            define('PROTOCOLO', 'https');    
}
else{
            define('PROTOCOLO', 'https');
}

$BASE_URL_ = $BASE_URL = PROTOCOLO.'://'.$_SERVER['HTTP_HOST'].'/'.$CARPETA_BASE;


$STORAGE = $_SERVER['DOCUMENT_ROOT'] . '/files/';

foreach(glob($LIB) as $filename){ 
	include $filename;
}

foreach(glob($CONTROLLER) as $filename){
	include $filename;
}
foreach(glob($MODEL) as $filename){
    include $filename;
}

//include "functions.inc.php";

define('TIEMPO_SESION', 150000);

define('URL_LOGOUT','https://'.$_SERVER['HTTP_HOST'].'/one/logout');
define('URL_LOGIN','https://'.$_SERVER['HTTP_HOST'].'/one/login');
define('URL_LINK', 'https://'.$_SERVER['HTTP_HOST'].'/one/Link');

##############################DB Conection #################################

$IP		= getIP();
$oLog	= new Log($IP, 'data_publish');


$CONFIG_READ = array(
	'sHost'			=> '127.0.0.1',
	'sUser'			=> 'root',
	'sPassword'		=> '',
	'sDatabase'		=> 'data_publish',
	'sObjectType'	=> '',
	'oLog'			=> $oLog,
	'sPort'			=> '3306'
);

$CONFIG_WRITE = array(
	'sHost'			=> '127.0.0.1',
	'sUser'			=> 'root',
	'sPassword'		=> '',
	'sDatabase'		=> '',
	'sObjectType'	=> '',
	'oLog'			=> $oLog,
	'sPort'			=> '3306'
);


$oRdb = new MyMySQLi($CONFIG_READ);
$oWdb = new MyMySQLi($CONFIG_WRITE);


$oRdb->setBDebug(0);
$oWdb->setBDebug(0);

if(!$oRdb->bConectado || !$oWdb->bConectado){
	echo "ERROR AL CONECTAR A BASE DE DATOS";exit();
}


function getIP(){
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip_array = explode(",", $ip);
    $ip = trim($ip_array[0]);
    return $ip;
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
}
/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
?>
