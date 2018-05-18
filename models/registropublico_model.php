<?php

/*
 * Caso de uso de registro de asistencia de publico en general.
 */
class registropublico_Model extends Model {

    /*
     * Se crean instancias.
     */
	function __construct() {
		parent::__construct();
		Session::init();
	}//Fin __construct
    
    /*
     * Recupera a los asistentes registrados
     */
	public function get_asistentes_publico($idRegistroPublico) {
		$query = "SELECT ".
				"id,".
				"reg_nombre,".
				"reg_institucion,".
				"reg_tipo ".
				"FROM ".
				"tblreg_asis_pub ".
				"WHERE ".
				"reg_id=".$idRegistroPublico;
                
    
        
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
	}//Fin get_asistentes_publico
    
    /*
     * Recuperar la informacion de un asistente.
     */
    public function get_datos_asistente($idAsistente) {
		$query = "SELECT ".
				"id,".
				"reg_nombre,".
				"reg_institucion,".
				"reg_tipo ".
				"FROM ".
				"tblreg_asis_pub ".
				"WHERE ".
				"id=$idAsistente";
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
	}//Fin get_datos_asistente
    
    
    /*
     * Elimina un asistente.
     */
    public function borrar_asistente($idAsistente) {
		$query = "DELETE FROM ".
				"tblreg_asis_pub ".
				"WHERE ".
				"id=$idAsistente";
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
     * Actualiza un asistente.
     */
    public function update_asistente($asistente) {
		$query = "UPDATE ".
				"tblreg_asis_pub ".
				"SET ".
				"reg_nombre='$asistente[nombre]',".
				"reg_institucion='$asistente[institucion]',".
				"reg_tipo='$asistente[tipo]' ".
				"WHERE ".
				"id=$asistente[id]";
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
	}//update_asistente
    
    
    /*
     * Persiste un nuevo asistente.
     */
	public function regitro_asistente($asistente) {
        //Armando de la sentencia sql.
		$query = "INSERT INTO ".
				"tblreg_asis_pub".
				"(".
				"reg_id,".
				"reg_nombre,".
				"reg_institucion,".
				"reg_tipo".
				") ".
				"VALUES ".
				"(".
				"$asistente[reg_id],".
				"'$asistente[nombre]',".
				"'$asistente[institucion]',".
				"'$asistente[tipoAsistente]'".
				")";
                //Preprado y ejecucion de la sentencia
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
     * Crear un nuevo registro de asistencia publica.
     */
	public function nuevo_registro() {
        //Fecha actual de sistema
 		$dt = new DateTime('now', new DateTimeZone('America/Mexico_City'));
        //Armando de la sentencia sql
		$query = "INSERT INTO ".
				"tblreg_publico".
				"(".
				"reg_fecha,".
				"reg_validar_dep,".
				"reg_fact_enviada".
				") ".
				"VALUES ".
				"('".$dt->format('Y-m-d').
				"','no','no'".
				")";
        //Preparado y ejecucion de la sentencia
		$sth = $this->db->prepare($query);
		try {
			$sth->execute();
            /********************************************************************
            **** Guardamos en la sesion del identificado del registro publico
            ********************************************************************/
			
            Session::set('idRegistroPublico', $this->db->lastInsertId());
            
            /********************************************************************
            **** Guardamos en la sesion del identificado del registro publico
            ********************************************************************/
            
            
			$data = TRUE;
		} catch (PDOException $exc) {
			error_log($query);
			error_log($exc);
			$data = FALSE;
		}
		return $data;
	}//Finnuevo_registro
    
    /*
     * Persisten los datos del deposito.
     */
    public function registro_datos_deposito($deposito) {
        //Armando de la sentencia sql.
		$query = "INSERT ".
				"INTO ".
				"tblreg_pub_depositos".
				"(".
				"dep_banco,".
				"dep_sucursal,".
				"dep_transaccion,".
				"dep_info,".
				"dep_tipo,".
				"dep_fecha,".
				"dep_hora,".
				"dep_monto,".
				"reg_id".
				") ".
				"VALUES ".
				"(".
				"'$deposito[banco]',".
				"'$deposito[sucursal]',".
				"'$deposito[transaccion]',".
				"'$deposito[info]',".
				"'$deposito[tipoPago]',".
				"'$deposito[fecha]',".
				"'$deposito[hora]',".
				"$deposito[monto],".
				"$deposito[reg_id]".
				")";
        
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
	}//Fin 
     
    /*
     * Persiste los datos de factuacion
     */
	public function registro_datos_facturacion($facturacion) {
        //Armando de la sentencia sql.
		$query = "INSERT INTO ".
				"tblreg_datos_factura".
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
				"reg_id".
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
				"$facturacion[reg_id]".
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
     * Recupera el numero de asistentes, filtrando su tipo.
     */
	public function get_total_asistentes($idRegistroPublico,$tipoAsistente) {
        //Armando de la sentencia sql.
		$query = "SELECT ".
				"COUNT(reg_id) AS total ".
				"FROM ".
				"tblreg_asis_pub ".
				"WHERE ".
				"reg_id=$idRegistroPublico ".
				"AND ".
				"reg_tipo='$tipoAsistente'";
        
        //Preparado y ejecucion de la sentencia
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
     * Recupera el numero total de asistentes.
     */
    public function get_total_asistentes_pub($idRegistroPublico) {
        //Armando de la sentencia sql.
		$query = "SELECT ".
				"COUNT(reg_id) AS total ".
				"FROM ".
				"tblreg_asis_pub ".
				"WHERE ".
				"reg_id=$idRegistroPublico ";
        
        //Preparado y ejecucion de la sentencia.
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
	}//Fin get_total_asistentes_pub
    
    
    /*
     * Busca los datos de un asistente dado su id.
     */
    public function get_buscar_asistente($nombre_asistente) {
        //Armando de la sentecia sql
		$query ="SELECT ".
				"CONCAT(TRIM( autNombre ) ,TRIM( autApellidoPaterno ) , TRIM( autApellidoMaterno )) AS autnombre ".
				"FROM ".
				"tblautores ".
				"WHERE ".
				"UPPER(CONCAT(TRIM(autNombre),TRIM(autApellidoPaterno),TRIM(autApellidoMaterno))) IN (:nombre)";
        //Preprado y ejecucion de la sentencia
		$sth = $this->db->prepare($query);
		$nombre_asistente=strtoupper($nombre_asistente);
		$sth->bindParam(':nombre', $nombre_asistente);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$count = $sth->rowCount();
		$data = NULL;
		if ($count > 0) {
			$data = true;
		} else {
			$data = false;
		}
		return $data;
	}//Fin get_buscar_asistente
    
    /*
     * Guarda el nombre del documento como comprobante.
     */
    public function registro_comprobante($idRegistroPublico, $file){
        $query = "UPDATE ".
				"tblreg_pub_depositos ".
				"SET ".
				"dep_comprobante='$file' ".
				"WHERE ".
				"reg_id=$idRegistroPublico";
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
    
    
    /*******************************************************************************************/
    /***********************************No se utiliza    ***************************************/
    /*******************************************************************************************/
	 
	/*
	public function get_datos_facturacion($idArticulo) {
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
	
	public function get_datos_deposito($idArticulo) {
		$query = "SELECT ".
				"dep_id,".
				"dep_banco,".
				"dep_sucursal,".
				"dep_transaccion,".
				"dep_tipo,".
				"dep_info,".
				"dep_fecha,".
				"dep_hora,".
				"dep_monto,".
				"dep_comprobante ".
				"FROM ".
				"tbl_depositos ".
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
		} else {
			$data = FALSE;
		}
		return $data;
	}
	
	public function get_comprobante($idArticulo) {
		$query = "SELECT ".
				"dep_comprobante ".
				"FROM ".
				"tbl_depositos ".
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
			$data = $data['dep_comprobante'];
		} else {
			$data = FALSE;
		}
		return $data;
	}
    */
}//Fin registropublico_Model
	