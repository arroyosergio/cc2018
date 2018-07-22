<?php

class cartas_Model extends Model {

     function __construct() {
          parent::__construct();
     }

     public function get_cartas() {
          $query = 'SELECT '.
                         'artId as articulo,'.
                         'artNombre as nombre,'.
                         'artTipo as tipo,'.
                         'mid(doc_carta_aceptacion,INSTR(doc_carta_aceptacion,"/")+1) as c_aceptacion, '.
                         'upper(doc_validar_carta_ori_ces) as cartas_validada '.
                    'FROM '.
                         'tblArticulos inner join tblDocumentos on tblArticulos.artid=tblDocumentos.art_id '.
                    'WHERE '.
				       'tblArticulos.artDictaminado="si"';	 
					//'doc_carta_originalidad<>"" or doc_carta_cesion_der<>""';
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
      
	function fncGetCorreoAutor($idArt){
		$strQuery="SELECT tblArticulos.artId AS ID,tblArticulos.artNombre as ARTICULO,".
							   "tblArticulos.artAreaTematica as AREA,tblArticulos.artTipo AS TIPO,".
								"tblAutores.autId AS IDAUTOR,CONCAT(tblAutores.autNombre,tblAutores.autApellidoPaterno,". "tblAutores.autApellidoMaterno) AS AUTOR, tblAutores.autCorreo AS CORREO,".
								"tblArticulos.artRecibido AS RECIBIDO,tblArticulos.artDictaminado AS DICTAMINADO,". "tblArticulos.artAvisoCambio AS AVISOCAMBIO,tblAutores.autAsistenciaCica AS ASISTENCIACICA,". "tblAutores.autCiudad AS CIUDAD,tblAutores.autEstado AS ESTADO,".
							    "tblAutores.autGradoAcademico AS GRADOACADEMICO,tblAutores.autInstitucionProcedencia AS PROCEDENCIA,". "tblPaises.pais_nombre AS PAIS,tblAutores.autTipoInstitucion AS TIPOINSTITUCION  FROM ". "tblAutores,tblArticulos,tblPaises WHERE  tblArticulos.autContacto=tblAutores.autid AND ". "tblPaises.pais_id=tblAutores.autPais AND tblArticulos.artId=".$idArt;
		$sth=$this->db->prepare($strQuery);
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
				"doc_validar_carta_ori_ces as validado ".
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
		} else {
			$data = FALSE;
		}
		return $data;
	}

	function get_cambio_cartas($idArticulo) {
		$query = "SELECT ".
				"art_cambio_cartas as validado ".
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
	
	function update_cambio_cartas($idArticulo, $estatus){
		$query = "UPDATE ".
				"tblArticulos ".
				"SET ".
				"art_cambio_cartas='".$estatus."' ".
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

}
