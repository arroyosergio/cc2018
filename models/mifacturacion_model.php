<?php
/*
 * Capa de persistencia del modulo de facturacion para el autor.
 */
class mifacturacion_Model extends Model {
    
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
                    "usuId=$idUsuario";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
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
    public function get_depositos_validados($idAutor) {
        $query = "SELECT tblArticulos.artid as artid, artNombre, fac_razon_social, fac_rfc,  dep_monto, doc_validar_pago "
                ."FROM tblArticulos "
                ."INNER JOIN tbl_datos_facturacion "
                ."ON tblArticulos.artId = tbl_datos_facturacion.art_id "
                ."INNER JOIN tbl_depositos "
                ."ON tblArticulos.artId = tbl_depositos.art_id "
                ."INNER JOIN tblDocumentos "
                ."ON tblArticulos.artId = tblDocumentos.art_id "
                ."INNER JOIN tblAutoresArticulos "
                ."ON tblArticulos.artId = tblAutoresArticulos.artId "
                ."INNER JOIN tblAutores "
                ."ON tblAutoresArticulos.autId = tblAutores.autId "
                ."WHERE doc_validar_pago = 'si' AND  "
                ."tblAutores.autId = $idAutor";
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
                    "WHERE art_id=$idArticulo";
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
     }//Fin get_datos_deposito
    
    /*
     * Recupera los datos de facturacion
     * $idArticulo, identificador del articulo al que pertenecen los datos 
     * de facturacion.
     */
    public function get_datos_facturacion($idArticulo) {
          $query = "SELECT fac_razon_social, fac_correo, fac_rfc, fac_calle, fac_numero, fac_colonia, fac_municipio, fac_estado, fac_cp ".
                    "FROM  tbl_datos_facturacion ".
                    "WHERE art_id=$idArticulo";
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
     }//Fin get_datos_facturacion
    
    /*
     * Recupera los asistentes de un articulo.
     */
     public function get_asistentes_articulo($idArticulo) {
         //Armando de la sentencia sql
          $query = "SELECT ".
                         "asi_id,".
                         "asi_nombre,".
                         "asi_institucion,".
                         "asi_tipo ".
                    "FROM ".
                         "tbl_asistentes ".
                    "WHERE ".
                         "art_id=".$idArticulo;
          
         //Preparacion y ejecucion de la sentencia
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
     }//Fin get_asistentes_articulo
    
    
    /*
     * Recupera los documentos electronicos de facturacion
     * $idArticulo, identificador del articulo al que pertenecen los datos 
     * de facturacion.
     */
    public function get_documentos_facturacion($idArticulo) {
          $query = "SELECT  doc_factura_creada, doc_factura_pdf, doc_factura_xml "
                    ."FROM tblDocumentos "
                    ."WHERE art_id=$idArticulo";
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
     }//Fin get_datos_facturacion

    /*
     * Actualiza la bandera de creacion de documentos de facturacion a generada.
     */
    public function registro_documentos_factura($idArticulo, $archivopdf, $archivoxml) {
        $query = "UPDATE tblDocumentos ".
                 " SET doc_factura_creada = 'si', ". 
                 "doc_factura_pdf = '$archivopdf', ".
                 "doc_factura_xml = '$archivoxml' ".
                 "WHERE art_Id=$idArticulo";
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
    }//Fin registro_documentos_factura

}//Fin mifacturacion_Model