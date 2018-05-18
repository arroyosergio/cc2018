<?php

class Dashboard_Model extends Model {
	function __construct(){
		parent::__construct();
	}

	function fncGetTrabajos(){
		//$sth=$this->db->prepare("SELECT * FROM vstAutoresArticulos");
                $query = "SELECT ".
                            "tblArticulos.artId AS ID,".
                            "tblArticulos.artNombre as ARTICULO,".
                            "tblArticulos.artAreaTematica as AREA,".
                            "tblArticulos.artTipo AS TIPO,".
                            "tblAutores.autId AS IDAUTOR,".
                            "CONCAT(tblAutores.autNombre,' ',tblAutores.autApellidoPaterno,' ',tblAutores.autApellidoMaterno) AS AUTOR,".
                            "tblAutores.autCorreo AS CORREO,".
                            "tblArticulos.artRecibido AS RECIBIDO,".
                            "tblArticulos.artDictaminado AS DICTAMINADO,".
                            "tblArticulos.artAvisoCambio AS AVISOCAMBIO,".
                            "tblArticulos.art_validacion_deposito as DEPOSITO,".
                            "tblAutores.autAsistenciaCica AS ASISTENCIACICA ,".
                            "tblAutores.autCiudad AS CIUDAD,".
                            "tblAutores.autEstado AS ESTADO,".
                            "tblAutores.autGradoAcademico AS GRADOACADEMICO,".
                            "tblAutores.autInstitucionProcedencia AS PROCEDENCIA,".
                            "tblPaises.pais_nombre AS PAIS,".
                            "tblAutores.autTipoInstitucion AS TIPOINSTITUCION".
                        	"  FROM ".
                    		" tblArticulos LEFT JOIN tblAutores".      
					        " ON tblArticulos.autContacto=tblAutores.autid ". 
						    " LEFT JOIN tblPaises ON tblAutores.autPais=tblPaises.pais_id ".
                            " ORDER BY tblArticulos.artId desc";
		$sth=$this->db->prepare($query);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}
	

	function fncUpdateTrabajo($id,$campo,$estado){
        $data = NULL;
        $sth=$this->db->prepare('UPDATE  tblArticulos SET '.$campo.'=:estado WHERE artId=:artId');
        try {
			$sth->execute(array(
			'artId'=>$id,
			'estado'=>$estado
			));
            $data = TRUE;
        } catch (PDOException $exc) {
            $data = FALSE;
        }
        return $data;
	}

	function fncGetDetTrabajo($id){
		$sth=$this->db->prepare('SELECT * FROM tblArticulos WHERE artId="'.$id.'"');
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}

	function fncGetVerArticulos($id){
		$sth=$this->db->prepare('SELECT artId as articulo, verArchivo as archivo,verFecha as fecha FROM tblVersionesArticulos WHERE artId=:id');
		$sth->execute(array(
			':id'=>$id
		));
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$data=$sth->fetchall();
		return $data;
	}
	
	
	
	function fncGetDetTrabajoAutores($idAutor){
		$sth=$this->db->prepare('SELECT tblAutores.autId,tblAutores.autNombre,tblAutores.autApellidoPaterno,tblAutores.autApellidoMaterno,tblAutores.autInstitucionProcedencia,tblAutores.autCiudad,tblAutores.autEstado,tblPaises.pais_nombre AS autPais,'.
					            'tblAutores.autCorreo,tblAutores.autGradoAcademico,tblAutores.autTipoInstitucion,tblAutores.autAsistenciaCica,tblAutores.autContacto FROM tblAutoresArticulos,tblAutores,tblPaises '.
				                'WHERE tblAutoresArticulos.autId=tblAutores.autId AND artId="'.$idAutor.'" AND tblPaises.pais_id=tblAutores.autPais');
                $sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}

	function fncGetCorreoAutor($idArt){
		$sth=$this->db->prepare("SELECT tblArticulos.artId AS ID,tblArticulos.artNombre as ARTICULO,tblArticulos.artAreaTematica as AREA,tblArticulos.artTipo AS TIPO,tblAutores.autId AS IDAUTOR,CONCAT(tblAutores.autNombre,' ',tblAutores.autApellidoPaterno,' ',tblAutores.autApellidoMaterno) AS AUTOR, tblAutores.autCorreo AS CORREO,tblArticulos.artRecibido AS RECIBIDO,".
								"tblArticulos.artDictaminado AS DICTAMINADO,tblArticulos.artAvisoCambio AS AVISOCAMBIO,tblAutores.autAsistenciaCica AS ASISTENCIACICA ,tblAutores.autCiudad AS CIUDAD,tblAutores.autEstado AS ESTADO,tblAutores.autGradoAcademico AS GRADOACADEMICO,tblAutores.autInstitucionProcedencia AS PROCEDENCIA,tblPaises.pais_nombre AS PAIS,".
								"tblAutores.autTipoInstitucion AS TIPOINSTITUCION  FROM tblAutores,tblArticulos,tblPaises WHERE  tblArticulos.autContacto=tblAutores.autid AND tblPaises.pais_id=tblAutores.autPais AND tblArticulos.artId=".$idArt);
		
                $sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}
	

	function fncGetArtExportar(){
		$sth=$this->db->prepare("SELECT tblArticulos.artId AS ID,tblArticulos.artNombre as ARTICULO,tblArticulos.artAreaTematica as AREA,tblArticulos.artTipo AS TIPO,CONCAT(tblAutores.autNombre,' ',tblAutores.autApellidoPaterno,' ',tblAutores.autApellidoMaterno) AS AUTOR, tblAutores.autCorreo AS CORREO,tblArticulos.artRecibido AS RECIBIDO".
				                ",tblArticulos.artDictaminado AS DICTAMINADO,tblArticulos.artAvisoCambio AS AVISOCAMBIO,tblAutores.autAsistenciaCica AS ASISTENCIACICA ,tblAutores.autCiudad AS CIUDAD,tblAutores.autEstado AS ESTADO,tblAutores.autGradoAcademico AS GRADOACADEMICO,tblAutores.autInstitucionProcedencia AS PROCEDENCIA,".
								"tblPaises.pais_nombre AS PAIS,tblAutores.autTipoInstitucion AS TIPOINSTITUCION  FROM tblAutores,tblArticulos,tblAutoresArticulos,tblPaises WHERE tblArticulos.artId=tblAutoresArticulos.artId AND tblAutores.autId= tblAutoresArticulos.autId AND tblPaises.pais_id=tblAutores.autPais");
                $sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}
        
        function get_carta_originalidad($idArticulo) {
            $query = "SELECT ".
                        "doc_carta_originalidad ".
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
                $data = $data['doc_carta_originalidad'];
            } else {
                $data = FALSE;
            }
            return $data;
        }
        
        function get_carta_derechos($idArticulo) {
            $query = "SELECT ".
                        "doc_carta_cesion_der ".
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
                $data = $data['doc_carta_cesion_der'];
            } else {
                $data = FALSE;
            }
            return $data;
        }
        
        function get_estatus_cartas($idArticulo) {
             $query = "SELECT ".
                         "doc_validar_carta_ori_ces ".
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
              $data = $data['doc_validar_carta_ori_ces'];
          } else {
              $data = FALSE;
          }
          return $data;
        }
        
        function update_estatus_cartas($idArticulo, $estatus) {
            $query = "UPDATE ".
                        "tblDocumentos ".
                    "SET ".
                        "doc_validar_carta_ori_ces='".$estatus."' ".
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
     
     function update_estatus_recibo($idArticulo, $estatus) {
          $query = "UPDATE " .
                    "tblDocumentos " .
                  "SET " .
                    "doc_validar_pago='" . $estatus . "' " .
                  "WHERE " .
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

     function get_recibo_pago($idArticulo) {
            $query = "SELECT ".
                        "doc_pago ".
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
                $data = $data['doc_pago'];
            } else {
                $data = FALSE;
            }
            return $data;
        }
        
        //=============================CARTAS DE ACEPTACION=====================================
        public function registro_carta_aceptacion($idArticulo, $carta) {
        	$query = "UPDATE ".
        			"tblDocumentos ".
        			"SET ".
        			"doc_carta_aceptacion='$carta' ".
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
        
        public function existe_carta_aceptacion($idArticulo) {
        	$query = "SELECT ".
        			"doc_carta_aceptacion ".
        			"FROM ".
        			"tblDocumentos ".
        			"WHERE ".
        			"art_id=$idArticulo";
        	$data = NULL;
        	try {
        		$sth   = $this->db->prepare($query);
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
        
        	} catch (PDOException $exc) {
        		error_log($query);
        		error_log($exc);
        		$data = FALSE;
        	}
        	return $data;
        }
        //==================================FIN DE CARTAS DE ACEPTACION=====================================


}