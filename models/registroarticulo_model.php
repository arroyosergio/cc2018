<?php

class Registroarticulo_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    public function get_paises() {
        $query = "SELECT ".
                    "pais_nombre,".
                    "pais_id ".
                "FROM ".
                    "tblPaises ".
                "ORDER BY ".
                    "pais_nombre ".
                "ASC";
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
    
    public function get_estados($codigoPais) {
        $query = "SELECT ".
                    "est_nombre ".
                "FROM ".
                    "tblEstados ".
                "WHERE ".
                    "pais_id=$codigoPais";
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
    
    public function get_ciudades($estado) {
        $query = "SELECT ".
                    "Name ".
                "FROM ".
                    "city ".
                "WHERE ".
                    "Province='$estado'";
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
	
	
    public function get_version_articulo($idArticulos) {
        $query = "SELECT ".
                    "COUNT(verId) AS version ".
                "FROM ".
                    "tblVersionesArticulos ".
                "WHERE ".
                    "artId=$idArticulos";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[0];
            $data = $data['version'];
        } else {
            $data = FALSE;
        }
        return $data;
    }	
	
    /***************************************************
	FUNCION PARA CREAR UN NUEVO ARTICULO
	****************************************************/
     public function registro_articulo($articulo) {
        $query = "INSERT INTO ".
                    "tblArticulos".
                        "(".
                            "artNombre,".
                            "artAreaTematica,".
                            "autContacto,".
                            "artTipo".
                        ") ".
                    "VALUES".
                        "(".
                            "'$articulo[nombre]',".
                            "'$articulo[area]',".
                            "'$articulo[idAutor]',".
                            "'$articulo[tipo]'".
                        ")";
        $data = NULL;
        $sth   = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = $this->db->lastInsertId();
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
    
    public function registro_version_articulo($idArticulo,$archivo) {
        $query = "INSERT INTO ".
                    "tblVersionesArticulos ".
                        "(".
                            "artId,".
                            "verArchivo".
                        ") ".
                    "VALUES ".
                        "(".
                            "$idArticulo,".
                            "'$archivo'".
                        ")";
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
	
	
    
    //    Valida que el nombre del articulo no este registrado
    public function existe_articulo($articulo) {
		if(empty($articulo['idArticulo'])){
			
			$query = "SELECT ".
						"artId ".
					"FROM ".
						"tblArticulos ".
					"WHERE ".
						"artNombre='$articulo[nombre]'";
		}else{
			$query = "SELECT ".
						"artId ".
					"FROM ".
						"tblArticulos ".
					"WHERE ".
						"artNombre='$articulo[nombre]' AND artId<>$articulo[idArticulo]";
		}
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
    }
    
    public function registro_autor_articulo($idArticulo, $idAutor) {
        $query = "INSERT INTO ".
                    "tblAutoresArticulos".
                        "(".
                            "autId,".
                            "artId".
                        ") ".
                    "VALUES ".
                        "(".
                            "$idAutor,".
                            "$idArticulo".
                        ")";
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
    
    public function existe_autor_articulo($idArticulo, $nombre, $apPaterno, $apMaterno) {
        $query = "SELECT ".
                    "tblAutores.autId AS autId ".
                "FROM ".
                    "tblAutores ".
                "INNER JOIN ".
                    "tblAutoresArticulos ".
                    "ON tblAutores.autId=tblAutoresArticulos.autId ".
                "WHERE ".
                    "autNombre='$nombre' ".
                "AND ".
                    "autApellidoPaterno='$apPaterno' ".
                "AND ".
                    "autApellidoMaterno='$apMaterno' ".
                "AND ".
                    "artId=$idArticulo ";
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
    }
    
	
    public function registro_autor($autor) {
        $query = "INSERT INTO ".
                    "tblAutores".
                        "(".
                            "autNombre,".
                            "autApellidoPaterno,".
                            "autApellidoMaterno,".
                            "autInstitucionProcedencia,".
                            "autCiudad,".
                            "autEstado,".
                            "autPais,".
                            "autCorreo,".
                            "autGradoAcademico,".
                            "autTipoInstitucion,".
                            "autAsistenciaCica".
                        ") ".
                    "VALUES".
                        "(".
                            "'$autor[nombre]',".
                            "'$autor[apellidoPaterno]',".
                            "'$autor[apellidoMaterno]',".
                            "'$autor[institucionProcedencia]',".
                            "'$autor[ciudad]',".
                            "'$autor[estado]',".
                            "'$autor[pais]',".
                            "'$autor[correo]',".
                            "'$autor[gradoAcademico]',".
                            "'$autor[tipoInstitucion]',".
                            "'$autor[asistenciaCica]'".
                        ")";
        $data = NULL;
        $sth   = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = $this->db->lastInsertId();
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
    
   public function update_autor($autor) {
        $query = "UPDATE ".
                    "tblAutores ".
                "SET ".
                    "autNombre='$autor[nombre]',".
                    "autApellidoPaterno='$autor[apellidoPaterno]',".
                    "autApellidoMaterno='$autor[apellidoMaterno]',".
                    "autInstitucionProcedencia='$autor[institucionProcedencia]',".
                    "autCiudad='$autor[ciudad]',".
                    "autEstado='$autor[estado]',".
                    "autPais='$autor[pais]',".
                    "autCorreo='$autor[correo]',".
                    "autGradoAcademico='$autor[gradoAcademico]',".
                    "autTipoInstitucion='$autor[tipoInstitucion]',".
                    "autAsistenciaCica='$autor[asistenciaCica]' ".
                "WHERE ".
                    "autId='$autor[idAutor]'";
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
	
    public function get_autores_articulo($idArticulo) {
        $query = "SELECT ".
                    "tblAutores.autId as autId,".
                    "autNombre,".
                    "autApellidoPaterno,".
                    "autApellidoMaterno ".
                "FROM ".
                    "tblAutores ".
                "INNER JOIN ".
                    "tblAutoresArticulos ".
                    "ON tblAutores.autId=tblAutoresArticulos.autId ".
                "WHERE ".
                "tblAutoresArticulos.artId=$idArticulo ";
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
    
    public function get_total_autores($idArticulo) {
        $query = "SELECT ".
                    "COUNT(autId) as totalAutores ".
                "FROM ".
                    "tblAutoresArticulos ".
                "WHERE ".
                    "artId=$idArticulo";
        
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[0];
            $data = $data['totalAutores'];
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
	
    public function get_det_art_autor($idAutor,$idArticulo) {
        $query = "SELECT ".
                    "artNombre,".
                    "artAreaTematica,".
                    "artArchivo,".
                    "artTipo ".
                "FROM ".
                    "tblArticulos INNER JOIN tblAutoresArticulos ON tblArticulos.artId=tblAutoresArticulos.artId ".
                "WHERE ".
                    "tblAutoresArticulos.artId=:idArticulo AND tblAutoresArticulos.autId=:idAutor AND tblArticulos.artAvisoCambio='si'";
		$sth=$this->db->prepare($query);
		$sth->execute(array(
			':idArticulo'=>$idArticulo,
			':idAutor'=>$idAutor)
		);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
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
	
    public function get_detalles_articulo($idArticulo) {
        $query = "SELECT ".
                    "artNombre,".
                    "artAreaTematica,".
                    "artArchivo,".
                    "artTipo ".
                "FROM ".
                    "tblArticulos ".
                "WHERE ".
                    "artId=$idArticulo";
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
    
    public function get_nombre_archivo($idArticulo) {
        $query = "SELECT ".
                    "verArchivo ".
                "FROM ".
                    "tblVersionesArticulos ".
                "WHERE ".
                    "artId=$idArticulo";
        
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[0];
            $data = $data['verArchivo'];
        } else {
            $data = FALSE;
        }
        return $data;
                    
    }
    
    public function borrar_articulo($idArticulo) {
        $query = "DELETE FROM ".
                    "tblArticulos ".
                "WHERE ".
                    "artId=$idArticulo";
        
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
    
    public function borrar_autor($idAutor) {
        $query = "DELETE FROM ".
                    "tblAutores ".
                "WHERE ".
                    "autId=$idAutor";
        
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
    
	public function borrar_autor_articulo($idAutor, $idArticulo) {
        $query = "DELETE FROM ".
                    "tblAutoresArticulos ".
                "WHERE ".
                    "artId=$idArticulo ".
                "AND ".
                    "autId=$idAutor";
        $data = NULL;
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
	
    public function borrar_relacion_autor_articulo($idArticulo) {
        $query = "DELETE FROM ".
                    "tblAutoresArticulos ".
                "WHERE ".
                    "artId=$idArticulo";
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
    }
    
    public function autor_de_contacto($idArticulo, $idAutor) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "autContacto=$idAutor ".
                "WHERE ".
                "artId=$idArticulo";
        $data = NULL;
        $sth   = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = $this->db->lastInsertId();
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
    }
    

	 public function update_articulo($articulo) {
        $query = "UPDATE  ".
                    "tblArticulos".
                        " SET ".
                            "artNombre='$articulo[nombre]',".
                            "artAreaTematica='$articulo[area]',".
                            "artTipo='$articulo[tipo]'".
                    " WHERE ".
                            "artId=$articulo[idArticulo]";
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
	
	public function update_estatus_cambios($idArticulo, $estatus) {
		$query = "UPDATE ".
					"tblArticulos ".
				"SET ".
					"artAvisoCambio='$estatus' ".
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
	
    public function registro_tabla_documentos($idArticulo) {
        $query = "INSERT INTO ".
                    "tblDocumentos".
                        "(".
                            "art_id".
                        ") ".
                    "VALUES".
                        "(".
                            "$idArticulo".
                        ")";
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
    }
	
    public function get_detalles_autor($idAutor) {
        $query = "SELECT ".
                    "autNombre,".
                    "autApellidoPaterno,".
                    "autApellidoMaterno,".
                    "autCorreo,".
                    "autInstitucionProcedencia,".
                    "autCiudad,".
                    "autEstado,".
                    "autPais,".
                    "autGradoAcademico,".
                    "autTipoInstitucion,".
                    "autAsistenciaCica ".
                "FROM ".
                    "tblAutores ".
                "WHERE ".
                    "autId=$idAutor";
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

	function fncGetVerArticulos($id){
		$sth=$this->db->prepare('SELECT artId as articulo, verArchivo as archivo,verFecha as fecha FROM tblVersionesArticulos WHERE artId='.$id);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}
		
}
