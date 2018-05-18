<?php

class Depositos_Model extends Model {

     function __construct() {
          parent::__construct();
     }
    
    public function existe_doc_pago($idArticulo) {
        $query = "SELECT ". 
                    "doc_pago ".
                "FROM ".
                    "tblDocumentos ".
                "WHERE ".
                    "art_id=$idArticulo";
          $data = NULL;
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          try {
               $sth->execute();
               $data = $sth->fetchAll();
               $data = $data[0];
          } catch (PDOException $exc) {
               error_log($query);
               error_log($exc);
               $data = FALSE;
          }
          return $data;
     }

     
     public function get_depositos() {
        $query = 'SELECT artId, artNombre, artTipo, art_cambios_asistencia, doc_validar_pago, art_factura_enviada' 
                 .' FROM tblArticulos '
                 .' INNER JOIN  tbl_depositos '
				 .' ON tblArticulos.artId=tbl_depositos.art_id '
                 .' INNER JOIN tblDocumentos '
                 .' ON tblDocumentos.art_id = tblArticulos.artid '
                .' WHERE  art_estatus_asistencia="si"' ;
        
//                 error_log($query);
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
      
     public function get_datos_deposito($idArticulo) {
          $query = "SELECT ".
                         "dep_id,".
                         "dep_banco,".
                         "dep_sucursal,".
                         "dep_transaccion,".
                         "dep_tipo,".
                         "dep_info,".
                         "dep_monto,".
                         "dep_fecha,".
                         "dep_hora ".
                    "FROM ".
                         "tbl_depositos ".
                    "WHERE ".
                         "art_id=$idArticulo";
//                 error_log($query);
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
     
     public function get_datos_facturacion($idArticulo) {
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
                         "tbl_datos_facturacion ".
                    "WHERE ".
                         "art_id=$idArticulo";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          if ($count != 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }
     
    public function update_estatus_cambios($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "art_cambios_asistencia='$estatus' ".
                "WHERE ".
                    "artId=$idArticulo";
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
    
    public function get_correo_contacto($idArticulo) {
        $query = "SELECT ".
                    "fac_correo ".
                "FROM ".
                    "tbl_datos_facturacion ".
                "WHERE ".
                    "art_id=$idArticulo";
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
    
    public function update_estatus_deposito($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_validar_pago='$estatus' ".
                "WHERE ".
                    "art_Id=$idArticulo";
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
    
    public function update_estatus_facturacion($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "art_factura_enviada='$estatus' ".
                "WHERE ".
                    "artId=$idArticulo";
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
     	$sth=$this->db->prepare("SELECT artId as articulo, artNombre as nombre, artTipo as modalidad,".
     			                "asi_nombre as asistente,asi_institucion as institucion,".
     			                "asi_tipo as tipo_asist,fac_razon_social as razonsocial,".
     			                "fac_correo as correo,fac_rfc as rfc,".
     			                "fac_calle as calle,fac_numero as numero,".
     			                "fac_colonia as colonia,fac_municipio as municipio,".
     			                "fac_estado as estado,fac_cp as codpos,".
     			                "dep_banco as banco,dep_sucursal as sucursal,".
     			                "dep_transaccion as transaccion,dep_tipo as tipo_deposito,".
     			                "dep_info as informacion_dep,dep_fecha as fecha_dep,".
     			                "dep_hora as hr_dep,dep_monto as monto_pago,".
     			                "art_factura_enviada as fact_enviada, ".
     			                "tbl_depositos.fecha_registro as fecha_registro ".
     			                "FROM  tbl_asistentes ".
     			                "left join  tbl_datos_facturacion on  tbl_asistentes.art_id=tbl_datos_facturacion.art_id ".
     			                "inner join tblArticulos on tblArticulos.artid=tbl_asistentes.art_id ".
     			                "inner join tbl_depositos on tblArticulos.artid=tbl_depositos.art_id ".
     			                "ORDER BY tblArticulos.artId");
/*     	error_log("SELECT tblArticulos.artId AS ID,tblArticulos.artNombre as ARTICULO,tblArticulos.artAreaTematica as AREA,tblArticulos.artTipo AS TIPO,CONCAT(tblAutores.autNombre,' ',tblAutores.autApellidoPaterno,' ',tblAutores.autApellidoMaterno) AS AUTOR, tblAutores.autCorreo AS CORREO,tblArticulos.artRecibido AS RECIBIDO".
     			",tblArticulos.artDictaminado AS DICTAMINADO,tblArticulos.artAvisoCambio AS AVISOCAMBIO,tblAutores.autAsistenciaCica AS ASISTENCIACICA ,tblAutores.autCiudad AS CIUDAD,tblAutores.autEstado AS ESTADO,tblAutores.autGradoAcademico AS GRADOACADEMICO,tblAutores.autInstitucionProcedencia AS PROCEDENCIA,".
     			"tblPaises.pais_nombre AS PAIS,tblAutores.autTipoInstitucion AS TIPOINSTITUCION  FROM tblAutores,tblArticulos,tblAutoresArticulos,tblPaises WHERE tblArticulos.artId=tblAutoresArticulos.artId AND tblAutores.autId= tblAutoresArticulos.autId AND tblPaises.pais_id=tblAutores.autPais ");*/
     	$sth->setFetchMode(PDO::FETCH_ASSOC);
     	$sth->execute();
     	$data=$sth->fetchall();
     	return $data;
     }
      
}
