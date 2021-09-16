<?php 
namespace DBConnection;

     class DBConnection 
     {
          public $oRdb;
          public $oWdb;

          public function __construct(){}

          function getORdb()
          {
               return $this->oRdb;
          }

          function setORdb($oRdb)
          {
               $this->oRdb = $oRdb;
          }

          function getOWdb()
          {
               return $this->oWdb;
          }

          function setOWdb($oWdb)
          {
               $this->oWdb = $oWdb;
          } 
     }
     

?>