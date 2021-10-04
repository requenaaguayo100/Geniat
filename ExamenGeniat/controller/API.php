<?php
include "../inc/config.inc.php";
require_once '../vendor/autoload.php';


$oStored_model = new Stored_model();
 

$contErrorIni = 0;
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    $contErrorIni++;
    $mensajegel['Content_typeInvalid'] = 'Content type must be: application/json';
}
$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);
if(!is_array($decoded)){
    $contErrorIni ++;
    $mensajegel['JsonInvalid'] = 'Received content contained invalid JSON!';
}
if ($contErrorIni > 0) {
echo json_encode($mensajegel);
}
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $opcPOST = $decoded['accion'];
        switch ($opcPOST) {
            
            case 'registrarUsuario':
                $bearerToken = getBearerToken();
                if ($bearerToken == null) {
                    $res['msjError'] = 'No se encontro información del token';
                }else{
                    $oAuthJWT = new AuthJWT();
                    try{
                        $dataToken = $oAuthJWT->GetData($bearerToken);
                        $res['msjError'] = "No tienes permisos para registrar usuarios";
                    }catch(Exception $e){
                        $res['msjError'] = "Token invalido, alterado o expirado"; 
                    }
                    if ($dataToken->sRol == 5 || 
                        $dataToken->sRol == 4 || 
                        $dataToken->sRol == 3) {
                            $oStored_model->setORdb($oRdb);
                            $oStored_model->setsNombre($decoded['sNombre']);
                            $oStored_model->setsApellidoPaterno($decoded['sApellidoPaterno']);
                            $oStored_model->setsApellidoMaterno($decoded['sApellidoMaterno']);
                            $oStored_model->setsCorreo($decoded['sCorreo']);
                            $oStored_model->setsPassword($decoded['sPassword']);
                            $oStored_model->setsRol($decoded['sRol']);
                            $oStored_model->registrarUsuario();
                            
                            $res = $oStored_model->getOResponse();
                            $oStored_model->getNRecords();
                            unset($res['msjError']);
                    }
                }
                echo json_encode($res);
                
            break;
            case 'registrarPublicacion':

                $bearerToken = getBearerToken();
                if ($bearerToken == null) {
                    $res['msjError'] = 'No se encontro información del token';
                }else{
                    $oAuthJWT = new AuthJWT();
                    try{
                        $dataToken = $oAuthJWT->GetData($bearerToken);
                        $res['msjError'] = "No tienes permisos para registrar publicaciones";
                    }catch(Exception $e){
                        $res['msjError'] = "Token invalido, alterado o expirado"; 
                    }
                    if ($dataToken->sRol == 5 || 
                        $dataToken->sRol == 4 || 
                        $dataToken->sRol == 3) {
                            $oStored_model->setORdb($oRdb);
                            $oStored_model->setnIdUsuario($dataToken->nIdUsuario);
                            $oStored_model->setsTitulo($decoded['sTitulo']);
                            $oStored_model->setsDescripcion($decoded['sDescripcion']);
                            
                            $oStored_model->registrarPublicacion();
                            
                            $res = $oStored_model->getOResponse();
                            $oStored_model->getNRecords();
                            unset($res['msjError']);
                    }

                }

                echo json_encode($res);        
            break;
            default:
                $arrayAccion['msjAccion'] = 'Allow: registrarUsuario, registrarPublicacion';
                header('HTTP/1.1 405 Accion Not Allowed');
                echo json_encode($arrayAccion);
            break;
        }
    break;
    case 'DELETE':
            $opcionDELETE = $decoded['accion'];
                switch ($opcionDELETE) {
                    case 'eliminarPublicacion':
                        $bearerToken = getBearerToken();
                        if ($bearerToken == null) {
                            $res['msjError'] = 'No se encontro información del token';
                        }else{
                            $oAuthJWT = new AuthJWT();
                            try{
                                $dataToken = $oAuthJWT->GetData($bearerToken);
                                $res['msjError'] = "No tienes permisos para eliminar publicaciones";
                            }catch(Exception $e){
                                $res['msjError'] = "Token invalido, alterado o expirado"; 
                            }
                            if ($dataToken->sRol == 5) {
                                    $oStored_model->setORdb($oRdb);
                                    $oStored_model->setnIdPublicacion($decoded['nIdPublicacion']);
                                    $oStored_model->setnIdUsuario($dataToken->nIdUsuario);
                                    
                                    $oStored_model->eliminarPublicacion();
                                    
                                    $res = $oStored_model->getOResponse();
                                    $oStored_model->getNRecords();
                                    unset($res['msjError']);
                            }

                        }
                        echo json_encode($res);
                    break;
                    default:
                        $arrayAccion['msjAccion'] = 'Allow: eliminarPublicacion';
                        header('HTTP/1.1 405 Accion Not Allowed');
                        echo json_encode($arrayAccion);
                    break;
                }
    break;
    case 'GET':
           $opcionGET = $decoded['accion'];
            switch ($opcionGET) {
                case 'Login':
                    $oStored_model->setORdb($oRdb);
                    $oStored_model->setsCorreo($decoded['sCorreo']);
                    $oStored_model->setsPassword($decoded['sPassword']);
                    $oStored_model->loginUsuario();

                    $resLogin = $oStored_model->getOResponse();
                    $oStored_model->getNRecords();

                    if($resLogin['nCodigo'] == 0){
                        $oAuthJWT = new AuthJWT();

                        $token = $oAuthJWT->SignIn([
                                                    'nIdUsuario' => $resLogin[nIdUsuario],
                                                    'sNombre' => $resLogin[sNombre],
                                                    'sRol' => $resLogin[nIdRol]
                                                   ]);
                        $resLogin[token] = $token;
                    }
                    unset($resLogin[nIdUsuario],$resLogin[nIdRol],$resLogin[sNombre]);
                    echo json_encode($resLogin);
                break;
                case 'consultarPublicaciones':
                        $bearerToken = getBearerToken();
                        if ($bearerToken == null) {
                            $res['msjError'] = 'No se encontro información del token';
                        }else{
                            $oAuthJWT = new AuthJWT();
                            try{
                                $dataToken = $oAuthJWT->GetData($bearerToken);
                                $res['msjError'] = "No tienes permisos para consultar publicaciones";
                            }catch(Exception $e){
                                $res['msjError'] = "Token invalido, alterado o expirado"; 
                            }

                            if ($dataToken->sRol == 2 ||
                                $dataToken->sRol == 4 ||
                                $dataToken->sRol == 5) {
                                    $oStored_model->setORdb($oRdb);
                                    
                                    $oStored_model->consultarPublicaciones();
                                    
                                    $res = $oStored_model->getOResponse();
                                    $oStored_model->getNRecords();
                                    unset($res['msjError']);
                            }

                        }

                        echo json_encode($res);
                    break;
                    default:
                        $arrayAccion['msjAccion'] = 'Allow: Login, consultarPublicaciones';
                        header('HTTP/1.1 405 Accion Not Allowed');
                        echo json_encode($arrayAccion);
                    break;
            }
        break;

    break;

    case 'PUT':
            $opcionPUT = $decoded['accion'];
            switch ($opcionPUT) {
                case 'actualizarPublicacion':
                    $bearerToken = getBearerToken();
                    if ($bearerToken == null) {
                        $res['msjError'] = 'No se encontro información del token';
                    }else{
                        $oAuthJWT = new AuthJWT();
                        try{
                            $dataToken = $oAuthJWT->GetData($bearerToken);
                            $res['msjError'] = "No tienes permisos para registrar publicaciones";
                            }catch(Exception $e){
                                $res['msjError'] = "Token invalido, alterado o expirado"; 
                            }
                        
                        if ($dataToken->sRol == 5 || 
                            $dataToken->sRol == 4) {
                                $oStored_model->setORdb($oRdb);
                                $oStored_model->setnIdPublicacion($decoded['nIdPublicacion']);
                                $oStored_model->setnIdUsuario($dataToken->nIdUsuario);
                                $oStored_model->setsTitulo($decoded['sTitulo']);
                                $oStored_model->setsDescripcion($decoded['sDescripcion']);
                                
                                $oStored_model->actualizarPublicacion();
                                
                                $res = $oStored_model->getOResponse();
                                $oStored_model->getNRecords();
                                unset($res['msjError']);
                        }

                    }
                    echo json_encode($res);
                break;
                default:
                        $arrayAccion['msjAccion'] = 'Allow: actualizarPublicacion';
                        header('HTTP/1.1 405 Accion Not Allowed');
                        echo json_encode($arrayAccion);
                break;
            }
    break;

    default:
            $arrayAccion['msjAccion'] = 'Allow: POST, PUT, GET, DELETE';
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode($arrayAccion);
    break;
}