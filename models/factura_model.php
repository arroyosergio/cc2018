<?php
/*
 * Capa de persistencia del modulo de facturacion para el Administrador.
 */
class factura_Model extends Model {
    
    /*
     * Constructor de instancias
     */
    function __construct() {
          parent::__construct();
     }
    
    /*
     * Recupera el id del autor, dado el id del usuario.
     */
    public function get_id_autor($idUsuario) {
        $query = "SELECT ".
                    "autId ".
                "FROM ".
                    "tblAutores ".
                "WHERE ".
                    "usuId=:idUsuario";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute(array(
            ':idUsuario'=>$idUsuario));
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[0];
            $data = $data['autId'];
        } else {
            $data = FALSE;
        }
        return $data;
    }//Fin get_id_autor
    
    /*
     * REcupera los depositos validados
     */
    public function get_depositos_validados() {
        $query = "SELECT tblArticulos.artid as artid, artNombre,fac_rfc,dep_monto,dep_fecha, doc_factura_creada,doc_fact_correcta "
                ."FROM tblArticulos "
                ."INNER JOIN tbl_datos_facturacion "
                ."ON tblArticulos.artId = tbl_datos_facturacion.art_id "
                ."INNER JOIN tbl_depositos "
                ."ON tblArticulos.artId = tbl_depositos.art_id "
                ."INNER JOIN tblDocumentos "
                ."ON tblArticulos.artId = tblDocumentos.art_id "
                ."WHERE doc_validar_pago = 'si'";
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
    }//Fin get_depositos_validados
    
    /*
     * Recupera los datos del deposito.
     * $diArticulo, el identificador del articulo al que pertenece el deposito.
     */
    public function get_datos_deposito($idArticulo) {
          $query = "SELECT dep_id, dep_banco, dep_sucursal, dep_transaccion, dep_tipo, dep_info, dep_monto, dep_fecha, dep_hora ".
                    "FROM  tbl_depositos ".
                    "WHERE art_id=:idArticulo";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute(array(':idArticulo'=>$idArticulo));
          $count = $sth->rowCount();
          $data = NULL;
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_datos_deposito
    
    /*
     * Recupera los datos de facturacion
     * $idArticulo, identificador del articulo al que pertenecen los datos 
     * de facturacion.
     */
    public function get_datos_facturacion($idArticulo) {
          $query = "SELECT fac_razon_social, fac_correo, fac_rfc, fac_calle, fac_numero, fac_colonia, fac_municipio, fac_estado, fac_cp ".
                    "FROM  tbl_datos_facturacion ".
                    "WHERE art_id=:idArticulo";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute(array(':idArticulo'=>$idArticulo));
          $count = $sth->rowCount();
          $data = NULL;
          if ($count != 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_datos_facturacion
    
    /*
     * Recupera los documentos electronicos de facturacion
     * $idArticulo, identificador del articulo al que pertenecen los datos 
     * de facturacion.
     */
    public function get_documentos_facturacion($idArticulo) {
          $query = "SELECT  doc_factura_creada, doc_factura_pdf, doc_factura_xml "
                    ."FROM tblDocumentos "
                    ."WHERE art_id=:idArticulo";
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute(array(':idArticulo'=>$idArticulo));
          $count = $sth->rowCount();
          $data = NULL;
          if ($count != 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_datos_facturacion

 

	function fnc_fact_correcta($id,$campo,$estado){
        $data = FALSE;
        $sth=$this->db->prepare('UPDATE  tblDocumentos SET '.$campo.'=:estado WHERE art_id=:artId');
        try {
			$sth->execute(array(
			':artId'=>$id,
			':estado'=>$estado
			));
            $data = TRUE;
        } catch (PDOException $exc) {
            $data = FALSE;
        }
        return $data;
	}

    public function existe_factura_pdf($idArticulo) {
        $query = "SELECT ". 
                    "doc_factura_pdf ".
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

     public function registro_factura_pdf($idArticulo, $factura) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_factura_pdf='$factura' ".
                "WHERE ".
                    "art_id=$idArticulo";
        $data = NULL;
        $sth   = $this->db->prepare($query);
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

    public function existe_factura_xml($idArticulo) {
        $query = "SELECT ". 
                    "doc_factura_xml ".
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

     public function registro_factura_xml($idArticulo, $factura) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_factura_xml='$factura' ".
                "WHERE ".
                    "art_id=$idArticulo";
        $data = NULL;
        $sth   = $this->db->prepare($query);
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

}//Fin factura_Model