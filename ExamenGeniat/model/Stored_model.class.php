<?php
use oResponse\oResponse;
	
	class Stored_model extends oResponse
	{
	    private $sNombre;
	    private $sApellidoPaterno;
	    private $sApellidoMaterno;
	    private $sCorreo;
	    private $sPassword;
	    private $sRol;

	    private $nIdUsuario;
	    private $sDescripcion;
	    private $sTitulo;

	    private $nIdPublicacion;

		
		public function __construct(){
			parent::__construct();
		}

		public function setsNombre($sNombre){
			$this->sNombre=$sNombre;
		}
		public function getsNombre(){
			return $this->sNombre;
		}
		public function setsApellidoPaterno($sApellidoPaterno){
			$this->sApellidoPaterno=$sApellidoPaterno;
		}
		public function getsApellidoPaterno(){
			return $this->sApellidoPaterno;
		}
		public function setsApellidoMaterno($sApellidoMaterno){
			$this->sApellidoMaterno=$sApellidoMaterno;
		}
		public function getsApellidoMaterno(){
			return $this->sApellidoMaterno;
		}    
		public function setsCorreo($sCorreo){
			$this->sCorreo=$sCorreo;
		}
		public function getsCorreo(){
			return $this->sCorreo;
		}    
		public function setsPassword($sPassword){
			$this->sPassword=$sPassword;
		}
		public function getsPassword(){
			return $this->sPassword;
		}    
		public function setsRol($sRol){
			$this->sRol=$sRol;
		}
		public function getsRol(){
			return $this->sRol;
		}
    

		public function setnIdUsuario($nIdUsuario){
			$this->nIdUsuario=$nIdUsuario;
		}
		public function getnIdUsuario(){
			return $this->nIdUsuario;
		}		
		public function setsDescripcion($sDescripcion){
			$this->sDescripcion=$sDescripcion;
		}
		public function getsDescripcion(){
			return $this->sDescripcion;
		}
		public function setsTitulo($sTitulo){
			$this->sTitulo=$sTitulo;
		}
		public function getsTitulo(){
			return $this->sTitulo;
		}

		public function setnIdPublicacion($nIdPublicacion){
			$this->nIdPublicacion=$nIdPublicacion;
		}
		public function getnIdPublicacion(){
			return $this->nIdPublicacion;
		}
		



		public function registrarUsuario()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CksNombre',
	                'value'   => self::getsNombre(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksApellidoPaterno',
	                'value'   => self::getsApellidoPaterno(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksApellidoMaterno',
	                'value'   => self::getsApellidoMaterno(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksCorreo',
	                'value'   => self::getsCorreo(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksPassword',
	                'value'   => self::getsPassword(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksRol',
	                'value'   => self::getsRol(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setSDatabase('data_publish');
	        $this->oRdb->setSStoredProcedure('sp_insert_usuario');
	        $this->oRdb->setParams($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['bExito'] || $oResult['nCodigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($nRecords);
	    }

		public function loginUsuario()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CksCorreo',
	                'value'   => self::getsCorreo(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksPassword',
	                'value'   => self::getsPassword(),
	                'type'    => 's'
	            )	            
	        );
	        $this->oRdb->setSDatabase('data_publish');
	        $this->oRdb->setSStoredProcedure('sp_select_login');
	        $this->oRdb->setParams($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['bExito'] || $oResult['nCodigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($nRecords);
	    }
		public function registrarPublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CknIdUsuario',
	                'value'   => self::getnIdUsuario(),
	                'type'    => 'i'
	            ),	            
	            array(
	                'name'    => 'CksTitulo',
	                'value'   => self::getsTitulo(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksDescripcion',
	                'value'   => self::getsDescripcion(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setSDatabase('data_publish');
	        $this->oRdb->setSStoredProcedure('sp_insert_publicacion');
	        $this->oRdb->setParams($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['bExito'] || $oResult['nCodigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($nRecords);
	    }
		public function actualizarPublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CknIdPublicacion',
	                'value'   => self::getnIdPublicacion(),
	                'type'    => 'i'
	            ),
	            array(
	                'name'    => 'CknIdUsuario',
	                'value'   => self::getnIdUsuario(),
	                'type'    => 's'
	            ),            
	            array(
	                'name'    => 'CksTitulo',
	                'value'   => self::getsTitulo(),
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksDescripcion',
	                'value'   => self::getsDescripcion(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setSDatabase('data_publish');
	        $this->oRdb->setSStoredProcedure('sp_update_publicacion');
	        $this->oRdb->setParams($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['bExito'] || $oResult['nCodigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($nRecords);
	    }
	    public function eliminarPublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CknIdPublicacion',
	                'value'   => self::getnIdPublicacion(),
	                'type'    => 'i'
	            ),
	            array(
	                'name'    => 'CknIdUsuario',
	                'value'   => self::getnIdUsuario(),
	                'type'    => 's'
	            )
	        );
	        $this->oRdb->setSDatabase('data_publish');
	        $this->oRdb->setSStoredProcedure('sp_delete_publicacion');
	        $this->oRdb->setParams($array_params);
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['bExito'] || $oResult['nCodigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($nRecords);
	    } 
	    public function consultarPublicaciones()
	    {
	        $this->oRdb->setSDatabase('data_publish');
	        $this->oRdb->setSStoredProcedure('sp_select_publicacion');
	        $oResult = $this->oRdb->execute();
	        $this->setsMessage($oResult['sMensaje']);
	        if (!$oResult['bExito'] || $oResult['nCodigo'] != 0) {
	            return $oResult;
	        }
	        $data = $this->oRdb->fetchAll();
	        $this->oRdb->closeStmt();

	        $nRecords = $this->oRdb->foundRows();
	        $this->oRdb->closeStmt();
	        if (COUNT($data) == 1) {
	            $data = $data[0];
	        }
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($nRecords);
	    } 
	}
?>
