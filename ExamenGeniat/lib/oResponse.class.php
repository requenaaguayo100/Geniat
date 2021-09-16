<?php
namespace oResponse;
use DBConnection\DBConnection;

     class oResponse extends DBConnection{
          private $sMessage = '';
          private $nCode =1;
          private $oResponse;
          private $nRecords;
          private $nRowId;

          public function __construct(){
               parent::__construct();
          }

          function getSMessage() { 
               return $this->sMessage; 
          } 

          function setSMessage($sMessage) {  
               $this->sMessage = $sMessage; 
          }

          function getNCode()
          {
               return $this->nCode;
          }

          function setNCode($nCode)
          {
               $this->nCode = $nCode;
          } 
          
          function getNRecords() { 
                    return $this->nRecords; 
          } 
     
          function setNRecords($nRecords) {  
               $this->nRecords = $nRecords; 
          } 
          
          function getOResponse() { 
                return $this->oResponse; 
          } 
     
          function setOResponse($oResponse) {  
               $this->oResponse = $oResponse; 
          } 
     }
?>


	
