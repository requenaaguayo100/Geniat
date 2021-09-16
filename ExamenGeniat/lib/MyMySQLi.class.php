<?php

class MyMySQLi{

	public $sHost;
	public $sUser;
	public $sPassword;
	public $sDatabase;
	public $sPort;
	public $CONNECTION;
	public $sObjectType;
	public $LOG;
	public $bDebug = 0;
	public $stmt;
	public $result;
	public $bConectado=false;

	public function setSHost($sHost){
		$this->sHost = $sHost;
	}

	public function getSHost(){
		return $this->sHost;
	}

	public function setSUser($sUser){
		$this->sUser = $sUser;
	}

	public function getSUser(){
		return $this->sUser;
	}

	public function setSPassword($sPassword){
		$this->sPassword = $sPassword;
	}

	public function getSPassword(){
		return $this->sPassword;
	}

	public function setSDatabase($sDatabase){
		$this->sDatabase = $sDatabase;
	}

	public function getSDatabase(){
		return $this->sDatabase;
	}

	public function setSPort($sPort){
		$this->sPort = $sPort;
	}

	public function getSPort(){
		return $this->sPort;
	}

	public function setCONNECTION($CONNECTION){
		$this->LINK = $CONNECTION;
	}

	public function getCONNECTION(){
		return $this->LINK;
	}

	public function setSObjectType($sObjectType){
		$this->sObjectType = $sObjectType;
	}

	public function getSObjectType(){
		return $this->sObjectType;
	}

	public function setLOG($LOG){
		$this->LOG = $LOG;
	}

	public function getLOG(){
		return $this->LOG;
	}

	public function setSStoredProcedure($sStoredProcedure){
		$this->sStoredProcedure = $sStoredProcedure;
	}

	public function getSStoredProcedure(){
		return $this->sStoredProcedure;
	}

	public function setParams($arrParams){
		$this->arrParams = $arrParams;
	}

	public function getParams(){
		return $this->arrParams;
	}

	public function setBDebug($bDebug){
		$this->bDebug = $bDebug;
	}

	public function getBDebug(){
		return $this->bDebug;
	}

	public function setResult($result){
		$this->result = $result;
	}

	public function getResult(){
		return $this->result;
	}


	public function __construct($array_config){
		self::setShost($array_config['sHost']);
		self::setSUser($array_config['sUser']);
		self::setSPassword($array_config['sPassword']);
		self::setSDatabase($array_config['sDatabase']);
		self::setSPort($array_config['sPort']);
		self::setSObjectType($array_config['sObjectType']);
		self::setLOG($array_config['oLog']);

		self::_connectme();
	}

	/*
		Realiza la conexion a la base de datos, en caso de error se guarda el mensaje en el log
	*/
    private function _connectme(){
    	try{
			$this->LINK = new mysqli($this->sHost, $this->sUser, $this->sPassword, $this->sDatabase, $this->sPort);
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

			$this->bConectado = true;
		}
		catch(mysqli_sql_exception $e){
			$this->LOG->error('Connect Error (' . $e->getCode() . ') '. $e->getMessage());
			$this->bConectado = false;
		}
	}

	/*
		Por cada parametro encontrado lo concatena
		Devuelve un string con el siguiente formato : ?,?,?,?,?
	*/
	private function _getParamsString(){
		$arrParams		= self::getParams();
		$length			= count($arrParams);
		$paramsString	= "";

		for($i=0; $i<$length; $i++){
			$paramsString .= "?,";
		}

		$paramsString = trim($paramsString, ',');

		return $paramsString;
	} # getParamsString

	/*
		Recorre arrParams y devuelve sus valores concatenados.
		Ejemplo '1', 'Hola', '16.02', '2016-09-08'
	*/
	private function _concatenateParams(){
		$arrParams = self::getParams();

		if(count($arrParams) >= 1){
			$length = count($arrParams);


			for($i=0; $i < $length; $i++){
				$arrValues[] = $arrParams[$i]['value'];
			}
			$sParams	= implode("','", $arrValues);
		}
		else{
			$sParams = "";
		}

		return "'".$sParams."'";
	} # concatenateParams

	/*
		Guarda en el log el query ejecutado
		Ejemplo CALL `redefectiva`.`SP_SELECT_TIPOOPERACION`('-1', '0');
	*/
	private function _debugQuery(){
		$sParams		= self::_concatenateParams();
		$sDebugQuery	= "CALL `".self::getSDatabase()."`.`".self::getSStoredProcedure()."`(".$sParams.");";

		$this->LOG->error($sDebugQuery);
	} # debugQuery

	/*
		Esta funcion asigna los valores a los parametros definidos, utilizando la funcion bind_param de mysqli.
		Crea el string de tipo de parametros ('iissd')
		Llena un arreglo con los valores de los parámetros recibidos
	*/
	private function _bindParams(){
		$arrParams		= self::getParams();
		$stringTypes	= "";
		$stringValues	= "";

		if(count($arrParams) >= 1){
			$length			= count($arrParams);
			$array_params	= array();
			$param_type		= "";

			for($i = 0; $i < $length; $i++) {
				$param_type .= $arrParams[$i]['type'];
			}

			$array_params[] =& $param_type;

			for($i = 0; $i < $length; $i++) {
				$array_params[] =& $arrParams[$i]['value'];
			}

			call_user_func_array(array($this->stmt, 'bind_param'), $array_params);
		}
	} # bindParams

	/*
		Ejecuta la consulta, en caso de error guarda un mensaje en el log, con el número de error, mensaje de error y consulta ejecutada (sin parámetros)
	*/
	public function execute(){
		if($this->bDebug == 1){
			self::_debugQuery();
		}

		$paramsString	= self::_getParamsString();
		$queryString	= "CALL `".self::getSDatabase()."`.`".self::getSStoredProcedure()."`(".$paramsString.");";

		try{
			$this->stmt = $this->LINK->prepare($queryString);
			self::_bindParams();
			$this->stmt->execute();
			$this->result = $this->stmt->get_result();

			return array(
				'bExito'			=> true,
				'nCodigo'			=> 0,
				'sMensaje'			=> 'Ok',
				'sMensajeDetallado'	=> 'Ok'
			);
		}
		catch(mysqli_sql_exception $e){
			$this->LOG->error("Error al ejecutar ".$queryString." (".$e->getCode().") : ".$e->getMessage()." L ".$e->getLine()." FILE ".$e->getFile());

			return array(
				'bExito'			=> false,
				'nCodigo'			=> $e->getCode(),
				'sMensaje'			=> 'Ha ocurrido un error al realizar la operacion ('.$e->getCode().')',
				'sMensajeDetallado'	=> "Error al ejecutar ".$queryString." (".$e->getCode().") : ".$e->getMessage()." L ".$e->getLine()." FILE ".$e->getFile()
			);
		}
	} # execute

	/*
		Hace una llamada "fetch_all" de mysqli, enviando como parametro MYSQLI_ASSOC
		Retorna un arreglo con una lista de arreglos con los valores retornados por la consulta.
		array(
			array('sNombre' => 'Fulanita', 'nEdad' => '24', 'sEstado' => 'Nuevo Leon'),
			array('sNombre' => 'Fulanito', 'nEdad' => '27', 'sEstado' => 'Tamaulipas'),
			...
		);
	*/
	public function fetchAll(){
		$array = $this->result->fetch_all(MYSQLI_ASSOC);

		return $array;
	} # fetchAll

	public function fetchObject($className = 'StdClass'){
		$array = array();

		while($obj = $this->result->fetch_object($className)){
			$array[] = $obj;
		}

		return $array;
	} # fetchAll

	/*
		Retorna el numero de filas encontradas
	*/
	public function numRows(){
		return $this->result->num_rows;
	} # numRows

	/*
		Cierra la sentencia preparada
	*/
	public function closeStmt(){
		$this->stmt->close();
	} # closeStmt

	/*
		Libera la memoria del resultado
	*/
	public function freeResult(){
		$this->result->free_result();
	} # freeResult

	public function closeConnection(){
		$this->LINK->close();
	}

	public function lastInsertId(){
		$this->stmt->prepare("SELECT LAST_INSERT_ID() AS last_insert_id");
		$this->stmt->execute();
		$this->result = $this->stmt->get_result();

		$array = self::fetchAll();

		return $array[0]['last_insert_id'];
	}

	public function foundRows(){
		$this->stmt = $this->LINK->prepare("SELECT FOUND_ROWS() AS found_rows");
		$this->stmt->execute();
		$this->result = $this->stmt->get_result();

		$array = self::fetchAll();

		return $array[0]['found_rows'];
	}
} #MyMySQLi


?>