<?php

class Gafete_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    public function get_asistentes($idArticulo) {
        $query = "SELECT ".
                    "asi_id,".
                    "asi_nombre,".
                    "asi_tipo,".
                    "art_id,".
                    "artNombre ".
                "FROM ".
                    "tblArticulos ".
                "INNER JOIN ".
                    "tbl_asistentes ".
                    "ON ".
                        "tblArticulos.artId=tbl_asistentes.art_id ".
                "WHERE ".
                    "tbl_asistentes.art_id=$idArticulo";
		
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
    
	
	
	
    public function get_datos_asistente($idArticulo, $id) {
        $query = "SELECT ".
                    "asi_id,".
                    "asi_nombre,".
                    "asi_tipo,".
                    "art_id,".
                    "artNombre ".
                "FROM ".
                    "tblArticulos ".
                "INNER JOIN ".
                    "tbl_asistentes ".
                    "ON ".
                        "tblArticulos.artId=tbl_asistentes.art_id ".
                "WHERE ".
                    "tbl_asistentes.art_id=$idArticulo ". 
                "AND ". 
                    "tbl_asistentes.asi_id=$id"; 
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


    public function getValidar_art_asistente($idArticulo, $id) {
        $query = "SELECT ".
                    "tbl_asistentes.asi_id ".
                "FROM ".
                    "tblArticulos ".
                "INNER JOIN ".
                    "tbl_asistentes ".
                    "ON ".
                        "tblArticulos.artId=tbl_asistentes.art_id ".
                "WHERE ".
                    "tbl_asistentes.art_id=$idArticulo ". 
                "AND ". 
                    "tbl_asistentes.asi_id=$id"; 
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
	
	
//    Obtiene todos los articulos registrados por el usuario autor
    public function get_art_registro_pago($idAutor) {

		$query="SELECT articulos.artId,artNombre,artAreaTematica,artTipo ". 
			   "FROM tblAutoresArticulos AS autores ".
			   " INNER JOIN tblArticulos AS articulos ON articulos.artId=autores.artId ".
			   " INNER JOIN tblDocumentos AS docs ON autores.artId=docs.art_id ".
			   " WHERE autores.autId=$idAutor AND artDictaminado='si' AND docs.doc_validar_pago='si'"; 
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
}