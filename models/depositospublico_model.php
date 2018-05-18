<?php

class Depositospublico_Model extends Model {

     function __construct() {
          parent::__construct();
     }
     
     public function get_depositos() 
     {
          $query = 'SELECT '.
                         'tblreg_publico.reg_id as id,'.
                         "DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fecha,".
                         'dep_banco as banco,'.
                         'dep_tipo as tipo,'.
                         'dep_monto as monto,'.
                         'reg_validar_dep as validar,'.
                         'reg_fact_enviada as enviada '.
                    'FROM '.
                         'tblreg_publico inner join tblreg_pub_depositos '.
                         'on tblreg_publico.reg_id=tblreg_pub_depositos.reg_id '.
                         "order by tblreg_pub_depositos.dep_fecha desc";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          
          if ($count > 0) {
               $data = $sth->fetchAll();
          } else {
               $data = FALSE;
          }
          return $data;
     }
      
     public function get_datos_deposito($reg_id) {
          $query = "SELECT ".
                         "dep_id,".
                         "dep_banco,".
                         "dep_sucursal,".
                         "dep_transaccion,".                         
                         "dep_tipo,".
                         "dep_info,".
                         "dep_monto,".
                         "DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fecha,".
                         "dep_hora,".
                         "dep_comprobante ".
                    "FROM ".
                         "tblreg_pub_depositos ".
                    "WHERE ".
                         "reg_id=$reg_id";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }
     
     public function get_datos_facturacion($reg_id) {
          $query = "SELECT ".
                         "fac_razon_social,".
                         "fac_correo,".
                         "fac_rfc,".
                         "fac_calle,".
                         "fac_numero,".
                         "fac_colonia,".
                         "fac_municipio,".
                         "fac_estado,".
                         "fac_cp ".
                    "FROM ".
                         "tblreg_datos_factura ".
                    "WHERE ".
                         "reg_id=$reg_id";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }
     
    
    public function get_correo_contacto($reg_id) {
        $query = "SELECT ".
                    "fac_correo ".
                "FROM ".
                    "tblreg_datos_factura ".
                "WHERE ".
                    "reg_id=$reg_id";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[0];
            $data = $data['fac_correo'];
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
    public function update_estatus_deposito($reg_id, $estatus) {
        $query = "UPDATE ".
                    "tblreg_publico ".
                "SET ".
                    "reg_validar_dep='$estatus' ".
                "WHERE ".
                    "reg_id=$reg_id";
        $sth = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = TRUE;
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
    
    public function update_estatus_facturacion($reg_id, $estatus) {
        $query = "UPDATE ".
                    "tblreg_publico ".
                "SET ".
                    "reg_fact_enviada='$estatus' ".
                "WHERE ".
                    "reg_id=$reg_id";
        $sth = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = TRUE;
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
      
     
     function fncGetRegistroExportar(){

     	$sth=$this->db->prepare("SELECT tblreg_publico.reg_id as id,".
     			                "reg_validar_dep as validar,".
     			                " reg_fact_enviada as enviada,".
     			                "reg_nombre as nombre,".
     			                "reg_tipo as tipo,".
     			                "dep_banco as banco,".
     							"dep_sucursal as sucursal,".
     			                "dep_transaccion as transaccion,".
     			                "dep_tipo as tipo_deposito,".
     			                "dep_info as informacion,".
     			                "dep_fecha as fechadeposito,".
     							"dep_hr as hrdeposito,".
     			                "dep_monto as monto,".
     			                "fac_razon_social as razon_social,".
     			                "fac_correo as correo,".
     			                "fac_rfc as rfc,".
     			                "CONCAT(fac_calle,' ',fac_numero) as calle,".
     			                "fac_colonia as colonia,".
     			                "fac_municipio as municipio,".
     			                "fac_estado as estado,".
     			                "fac_cp as cp ".
     			                "from tblreg_publico ".
     			                "inner join tblreg_asis_pub ".
     			                "on tblreg_publico.reg_id=tblreg_asis_pub.reg_id ".
     			                "inner join tblreg_pub_depositos ".
     			                "on tblreg_pub_depositos.reg_id=tblreg_publico.reg_id ".
     			                "inner join tblreg_datos_factura ".
     			                "on tblreg_datos_factura.reg_id= tblreg_publico.reg_id ".
     			                "order by tblreg_publico.reg_id desc");
     	$sth->setFetchMode(PDO::FETCH_ASSOC);
     	$sth->execute();
     	$data=$sth->fetchall();
     	return $data;
     }
      
}


