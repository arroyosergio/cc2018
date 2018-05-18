<?php

class Misarticulos_Model extends Model {

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
                    "pais_id='$codigoPais'";
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
    
//    Obtiene todos los articulos registrados por el usuario autor
    public function get_articulos($idAutor) {
        $query = "SELECT ".
                    "b.artId,".
                    "artNombre,".
//                    "artArchivo,".
                    "artAreaTematica,".
                    "artRecibido,".
                    "artDictaminado,".
                    "art_validacion_deposito,".
                    "artAvisoCambio ".
                "FROM ".
                    "tblAutoresArticulos AS a ".
                "INNER JOIN ".
                    "tblArticulos AS b ".
                    "ON a.artId=b.artId ".
                "WHERE ".
                    "a.autId=$idAutor";
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
    
    public function registro_articulo($articulo) {
        $query = "INSERT INTO ".
                    "tblArticulos".
                        "(".
                            "artNombre,".
                            "artArchivo,".
                            "artAreaTematica,".
                            "artTipo".
                        ") ".
                    "VALUES".
                        "(".
                            "'$articulo[nombre]',".
                            "'$articulo[archivo]',".
                            "'$articulo[area]',".
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
    
//    Valida que el nombre del articulo no este registrado
    public function existe_articulo($nombre) {
        $query = "SELECT ".
                    "artId ".
                "FROM ".
                    "tblArticulos ".
                "WHERE ".
                    "artNombre='$nombre'";
        
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
                    "autId='$autor[id]'";
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
    
	//    Optiene toda la información del perfil del usuario
    public function get_validar_perfil($idUsuario) {
        $query = "SELECT autNombre, autApellidoPaterno, autApellidoMaterno  ";
        $query .= " FROM tblAutores ";
        $query .= " WHERE autId = " . $idUsuario. " AND autNombre IS NOT NULL";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
		$data = "";
        if ($count > 0) {
            $data = "TRUE";
        } else {
            $data = "FALSE";
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
    
    public function get_detalles_articulo($idArticulo) {
        $query = "SELECT ".
                    "artNombre,".
                    "artAreaTematica,".
//                    "artArchivo,".
                    "artTipo,".
                    "artAvisoCambio,".
                    "artDictaminado ".
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
    
    public function get_nombre_archivo($idArticulo) {
        $query = "SELECT ".
                    "artArchivo ".
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
            $data = $data['artArchivo'];
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
            $data = $this->db->lastInsertId();
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
//            $data = $this->db->lastInsertId();
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
            $data = $this->db->lastInsertId();
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
    
    public function validacion_cambio_articulo($idArticulo) {
        $query = "SELECT ".
                    "artAvisoCambio ".
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
            $data = $data['artAvisoCambio'];
            if ($data == 'si') {
                $data = TRUE;
            }else{
                $data = FALSE;
            }
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
    public function get_estatus_dictaminado($idArticulo) {
        $query = "SELECT ".
                    "artDictaminado ".
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
            $data = $data['artDictaminado'];
            if ($data == 'si') {
                $data = TRUE;
            }else{
                $data = FALSE;
            }
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
    public function update_articulo($idArticulo, $nombre, $area, $tipo) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "artNombre='$nombre',".
                    "artAreaTematica='$area',".
                    "artTipo='$tipo' ".
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
    
    public function get_ultima_version_archivo($idArticulo) {
        $query = "SELECT ".
                    "verArchivo ".
                "FROM ".
                    "tblVersionesArticulos ".
                "WHERE ".
                    "artId=$idArticulo ".
                "ORDER BY 'verId' DESC";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $count = $sth->rowCount();
        $data = NULL;
        if ($count > 0) {
            $data = $sth->fetchAll();
            $data = $data[$count-1];
            $data = $data['verArchivo'];
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
    public function existe_carta_originalidad($idArticulo) {
        $query = "SELECT ". 
                    "doc_carta_originalidad ".
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

     public function registro_carta_originalidad($idArticulo, $carta) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_carta_originalidad='$carta' ".
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
    
    public function existe_carta_cesion($idArticulo) {
        $query = "SELECT ". 
                    "doc_carta_cesion_der ".
                "FROM ".
                    "tblDocumentos ".
                "WHERE ".
                    "art_id=$idArticulo";
          $data = NULL;
          $sth = $this->db->prepare($query);
          try {
               $sth->setFetchMode(PDO::FETCH_ASSOC);
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

     public function registro_carta_cesion_derechos($idArticulo, $carta) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_carta_cesion_der='$carta' ".
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
    
    public function get_estatus_recibo_pago($idArticulo) {
         $query = "SELECT ".
                    "doc_validar_pago ".
               "FROM ".
                    "tblDocumentos ".
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
               $data = $data['doc_validar_pago'];
               if ($data == 'si') {
                    $data = TRUE;
               } else {
                    $data = FALSE;
               }
          } else {
               $data = FALSE;
          }
          return $data;
     }
     
      public function existe_recibo_pago($idArticulo) {
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
               $data = $data['doc_pago'];
          } catch (PDOException $exc) {
               error_log($query);
               error_log($exc);
               $data = FALSE;
          }
          
          if (is_null($data)) {
               $data = FALSE;
          }
          
          return $data;
     }
     public function registro_recibo_pago($idArticulo, $carta) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_pago='$carta' ".
                "WHERE ".
                    "art_id=$idArticulo";
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

     function get_estatus_cartas($idArticulo) {
          $query = "SELECT " .
                    "doc_validar_carta_ori_ces " .
                  "FROM " .
                    "tblDocumentos " .
                  "WHERE " .
                    "art_id=$idArticulo";

          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
               $data = $data['doc_validar_carta_ori_ces'];
          } else {
               $data = FALSE;
          }
          return $data;
     }
     //===================CARTA DE ACEPTACION======================
     public function existe_carta_aceptacion($idArticulo) {
     	$query = "SELECT ".
     			"doc_carta_aceptacion ".
     			"FROM ".
     			"tblDocumentos ".
     			"WHERE ".
     			"art_id=$idArticulo";
     	$data = NULL;
     	$sth   = $this->db->prepare($query);
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
     //========================FIN CARTA DE ACEPTACION=====================
        
     function get_estatus_recibo($idArticulo) {
          $query = "SELECT " .
                    "doc_validar_pago " .
                  "FROM " .
                    "tblDocumentos " .
                  "WHERE " .
                    "art_id=$idArticulo";

          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          $sth->execute();
          $count = $sth->rowCount();
          $data = NULL;
          if ($count > 0) {
               $data = $sth->fetchAll();
               $data = $data[0];
               $data = $data['doc_validar_pago'];
          } else {
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

    //    Valida que el nombre del articulo no este registrado
    public function validarPresentacionArt($articulo) {
    	$query = "SELECT ".
    			"artId ".
    			"FROM ".
    			"tblArticulos ".
    			"WHERE ".
    			"artId='$articulo' and art_presentado='si'";
    
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
    
}