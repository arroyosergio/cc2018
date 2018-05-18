<?php

/*
 * Capa de persistencia de asistencia de colaboradores de articulos.
 */
class Registroasistencia_Model extends Model {

    /*
     * Crea instnaica de la capa de servicio.
     */
     function __construct() {
          parent::__construct();
     }//Fin __construct
     
    /*
     * Recupera la informacion de un artículo
     */
     public function get_datos_articulo($idArticulo) {
          
         //Armado de la sentencia sql
          $query = "SELECT ".
                         "artId,".
                         "artNombre,".
                         "artTipo ".
                    "FROM ".
                         "tblArticulos ".
                    "WHERE ".
                         "artId=$idArticulo";
          
          //Prepara y ejecuta la sentencia.
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          //Verifiamos el resultado y devolvemos respuesta.
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_datos_articulo
     
     /*
      * Recupera la bandera de cambios en la asitencia.
      */
     public function get_estatus_cambios($idArticulo) {
         
         //Se arma la sentecia sql
          $query = "SELECT ".
                         "art_cambios_asistencia ".
                    "FROM ".
                         "tblArticulos ".
                    "WHERE ".
                         "artId=$idArticulo";
         
         //Prepara y ejecuta la sentencia
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
         
         //Verifica el retorno de datos y retorno de respuesta.
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0]['art_cambios_asistencia'];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_estatus_cambios
     
    
    /*
     * Recupera la bandera de registro de asistencia.
     */
     public function get_estatus_registro($idArticulo) {
         //Armado de la sentencia sql
          $query = "SELECT ".
                         "art_estatus_asistencia ".
                    "FROM ".
                         "tblArticulos ".
                    "WHERE ".
                         "artId=$idArticulo";
         
         //Preparado y ejecucion de la setencia
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
         
         //Verificamos de datos y respuesta.
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0]['art_estatus_asistencia'];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_estatus_registro
     
    /*
     * Actualiza el estado de la bandera de asistencia.
     */
     public function update_estatus_asitencia($idArticulo, $estatus) {
         //Armando de la sentencia sql
          $query = "UPDATE ".
                         "tblArticulos ".
                    "SET ".
                         "art_estatus_asistencia='$estatus' ".
                    "WHERE ".
                         "artId=$idArticulo";
         
         //PReprado y ejecucion de la sentencia
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
     }//Fin update_estatus_asitencia

    /*
     * Persistencia de un nuevo asistente.
     */
     public function regitro_asistente($asistente) {
         //Armando de la sentencia sql
          $query = "INSERT INTO ".
                         "tbl_asistentes".
                              "(".
                                   "asi_nombre,".
                                   "asi_institucion,".
                                   "asi_tipo,".
                                   "art_id".
                              ") ".
                         "VALUES ".
                              "(".
                                   "'$asistente[nombre]',".
                                   "'$asistente[institucion]',".
                                   "'$asistente[tipoAsistente]',".
                                   Session::get('idArticulo').
                              ")";
          
         //Preparacion y ejecucion de la sentencia
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
     }//Fin regitro_asistente
     
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
     * Recupera la informacion de un asistente.
     */
     public function get_datos_asistente($idAsistente) {
         //Armando de la sentencia sql
          $query = "SELECT ".
                         "asi_id,".
                         "asi_nombre,".
                         "asi_institucion,".
                         "asi_tipo ".
                    "FROM ".
                         "tbl_asistentes ".
                    "WHERE ".
                         "asi_id=$idAsistente";
         
         //Preparacion y ejecucion de la sentencia
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
     }//get_datos_asistente
     
    /*
     * Elimina los datos persistidos de un asistente.
     */
     public function borrar_asistente($idAsistente) {
         //Armando de la sentencia sql
          $query = "DELETE FROM ".
                         "tbl_asistentes ".
                    "WHERE ".
                         "asi_id=$idAsistente";
         
         //Preprado y ejecuion de la sentencia
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
     }//Fin borrar_asistente
     
    
    /*
     * Actualiza los datos de un asistente.
     */
     public function update_asistente($asistente) {
         //Armando de la sentencia sql
          $query = "UPDATE ".
                         "tbl_asistentes ".
                    "SET ".
                         "asi_nombre='$asistente[nombre]',".
                         "asi_institucion='$asistente[institucion]',".
                         "asi_tipo='$asistente[tipo]' ".
                    "WHERE ".
                    "asi_id=$asistente[id]";
         //Preparacion y ejecuion de la sentencia
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
     }//Fin update_asistente
     
    /*
     * Guarda un nuevo registro de un deposito.
     */
     public function registro_datos_deposito($deposito) {
         //Armando de la sentencia sql
          $query = "INSERT ".
                         "INTO ".
                    "tbl_depositos".
                         "(".
                              "dep_banco,".
                              "dep_sucursal,".
                              "dep_transaccion,".
                              "dep_info,".
                              "dep_tipo,".
                              "dep_fecha,".
                              "dep_hora,".
                              "dep_monto,".
                              "art_id".
                         ") ".
                    "VALUES ".
                         "(".
                              "'$deposito[banco]',".
                              "'$deposito[numSucursal]',".
                              "'$deposito[numTransaccion]',".
                              "'$deposito[info]',".
                              "'$deposito[tipoPago]',".
                              "'$deposito[fecha]',".
                              "'$deposito[hora]',".
                              "$deposito[monto],".
                              "$deposito[idArticulo]".
                         ")";
         
         //Preparacion y ejecucion de la sentencia
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
     }//Fin registro_datos_deposito
    
    /*
     * Actualiza los datos de un deposito ya existente.
     */
     public function update_datos_deposito($deposito) {
         //Armando de la sentencia sql.
          $query = "UPDATE ".
                        "tbl_depositos ".
                         "SET ".
                              "dep_banco='$deposito[banco]',".
                              "dep_sucursal='$deposito[sucursal]',".
                              "dep_transaccion='$deposito[transaccion]',".
                              "dep_info='$deposito[info]',".
                              "dep_tipo='$deposito[tipoPago]',".
                              "dep_fecha='$deposito[fecha]',".
                              "dep_hora='$deposito[hora]',".
                              "dep_monto=$deposito[monto] ".
                        "WHERE ". 
                              "art_id=$deposito[idArticulo]";
         
         //Preparado y ejecuion de la sentencia.
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
     }//Fin update_datos_deposito
     
    /*
     * Guarda los datos de facturacion de un nuevo registro.
     */
     public function registro_datos_facturacion($facturacion) {
         //Armando de la sentencia sql
          $query = "INSERT INTO ".
                         "tbl_datos_facturacion".
                              "(".
                                   "fac_correo,".
                                   "fac_razon_social,".
                                   "fac_rfc,".
                                   "fac_calle,".
                                   "fac_numero,".
                                   "fac_colonia,".
                                   "fac_municipio,".
                                   "fac_estado,".
                                   "fac_cp,".
                                   "art_id".
                              ") ".
                         "VALUES ".
                              "(".
                                   "'$facturacion[correo]',".
                                   "'$facturacion[razonSocial]',".
                                   "'$facturacion[rfc]',".
                                   "'$facturacion[calle]',".
                                   "'$facturacion[numero]',".
                                   "'$facturacion[colonia]',".
                                   "'$facturacion[municipio]',".
                                   "'$facturacion[estado]',".
                                   "'$facturacion[cp]',".
                                   "$facturacion[idArticulo]".
                              ")";
         //Preparacion y ejecucion de la sentencia
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
     }//Fin registro_datos_facturacion
     
    /*
     * Actualiza los datos de facturacion.
     */
     public function update_datos_facturacion($facturacion) {
         //Identificamos si existe previamente un registro, en caso afirmativo, actualizamos, en caso contrario, insertamos el registro.
         if ($this->existeRegistroFacturacion(Session::get('idArticulo'))) {
             //Sentencia sql de actualizacion
             $query = "UPDATE ".
                         "tbl_datos_facturacion ".
                    "SET ".
                            "fac_correo='$facturacion[correo]',".
                            "fac_razon_social='$facturacion[razonSocial]',".
                            "fac_rfc='$facturacion[rfc]',".
                            "fac_calle='$facturacion[calle]',".
                            "fac_numero='$facturacion[numero]',".
                            "fac_colonia='$facturacion[colonia]',".
                            "fac_municipio='$facturacion[municipio]',".
                            "fac_estado='$facturacion[estado]',".
                            "fac_cp='$facturacion[cp]' ".
                    "WHERE ".
                            "art_id=".Session::get('idArticulo');
          }else{
                //Sentencia sql de insercion
                $query = "INSERT INTO ".
                         "tbl_datos_facturacion".
                              "(".
                                   "fac_correo,".
                                   "fac_razon_social,".
                                   "fac_rfc,".
                                   "fac_calle,".
                                   "fac_numero,".
                                   "fac_colonia,".
                                   "fac_municipio,".
                                   "fac_estado,".
                                   "fac_cp,".
                                   "art_id".
                              ") ".
                         "VALUES ".
                              "(".
                                   "'$facturacion[correo]',".
                                   "'$facturacion[razonSocial]',".
                                   "'$facturacion[rfc]',".
                                   "'$facturacion[calle]',".
                                   "'$facturacion[numero]',".
                                   "'$facturacion[colonia]',".
                                   "'$facturacion[municipio]',".
                                   "'$facturacion[estado]',".
                                   "'$facturacion[cp]',".
                                   "$facturacion[idArticulo]".
                              ")";
          }
         
         //Preparado y ejecuion de la sentencia
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
     }//update_datos_facturacion
     
    /*
     * Recupera la cantidad de asistentes de un articulo, filtrado por el tipo de asistente.
     */
     public function get_total_asistentes($idArticulo,$tipoAsistente) {
         //Armado de la sentencia sql.
          $query = "SELECT ".
                         "COUNT(asi_id) AS total ".
                    "FROM ".
                         "tbl_asistentes ".
                    "WHERE ".
                         "art_id=$idArticulo ".
                    "AND ".
                         "asi_tipo='$tipoAsistente'";
         
         //Preparado y ejecuion de la sentencia.
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
               $data = $data['total'];
          } else {
               $data = FALSE;
          }
          return $data;
     }//Fin get_total_asistentes
     
    /*
     * Recupera la informacion de facturacion
     */
    public function get_datos_facturacion($idArticulo) {
        //Armando de la sentencia
        $query = "SELECT ".
                    "fac_id,".
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
        
        //Preparacion y ejecucion de la sentencia
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
    }//Fin get_datos_facturacion
    
    /*
     * Recupera la informacion de un deposito.
     */
    public function get_datos_deposito($idArticulo) {
        //ARmando de la sentencia sql.
        $query = "SELECT ".
                    "dep_id,".
                    "dep_banco,".
                    "dep_sucursal,".
                    "dep_transaccion,".
                    "dep_tipo,".
                    "dep_info,".
                    "dep_fecha,".
                    "dep_hora,".
                    "dep_monto ".
                "FROM ".
                    "tbl_depositos ".
                "WHERE ".
                "art_id=$idArticulo";
        
        //Preparacion y ejecucion de la sentencia
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
     * Recupera el dato de comprobante de pago
     */
    public function get_comprobante($idArticulo) {
        //Armando de la sentencia sql
        $query = "SELECT ".
                    "doc_pago ".
                "FROM ".
                    "tblDocumentos ".
                "WHERE ".
                    "art_id=$idArticulo";
        
        //Preparado y ejecucion de la sentencia
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[0];
            $data = $data['doc_pago'];
        } else {
            $data = FALSE;
        }
        return $data;
    }//Fin get_comprobante
    
    
    /*
     * Actualiza el estado de la bandera de cambios en asistencia.
     */
    public function update_estatus_cambios($idArticulo, $estatus) {
        //armando de la sentencia sql
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "art_cambios_asistencia='$estatus' ".
                "WHERE ".
                    "artId=$idArticulo";
        
        //Preparado y ejecucion de la sentencia
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
    }//Fin update_estatus_cambios
    
    /*
     * Notifica de la existencia de datos de facturacion.
     */
    public function existeRegistroFacturacion($idRegistro){
        //Armando de la sentencia sql.
        $query = "SELECT ".
                    "* ".
                "FROM ".
                    "tbl_datos_facturacion ".
                "WHERE ".
                    "art_id=$idRegistro";
        
        //Preparado y ejecuion de la sentencia
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = TRUE;
        } else {
            $data = FALSE;
        }
        return $data;
    }//Fin existeRegistroFacturacion
    
    /*
     * Notifica la existencia de un comprobante de pago previo.
     */
    public function existe_comprobante_pago($idArticulo) {
        //Armando de la sentencia sql
        $query = "SELECT ". 
                    "doc_pago ".
                "FROM ".
                    "tblDocumentos ".
                "WHERE ".
                    "art_id=$idArticulo";
          $data = NULL;
        
        //Preparado y ejecucion de la sentencia sql
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
     }//Fin existe_comprobante_pago
    
    /*
     * Guarda el nombre del documento como comprobante.
     */
    public function registro_comprobante($idArticulo, $file){
        $query = "UPDATE ".
				"tblDocumentos ".
				"SET ".
				"doc_pago='$file' ".
				"WHERE ".
				"art_id=$idArticulo";
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
    }//Fin registro_comprobante
}//Fin class Registroasistencia_Model
