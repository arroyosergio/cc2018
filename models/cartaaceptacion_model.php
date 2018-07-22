<?php

class cartaaceptacion_Model extends Model {

    function __construct() {
        parent::__construct();
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
    public function get_art_aceptados($idAutor) {
		$query="SELECT articulos.artId,artNombre,artAreaTematica,artTipo,docs.doc_carta_aceptacion ". 
			   "FROM tblAutoresArticulos AS autores ".
			   " INNER JOIN tblArticulos AS articulos ON articulos.artId=autores.artId ".
			   " INNER JOIN tblDocumentos as docs ON docs.art_id=articulos.artId ".
               " WHERE autores.autId=$idAutor AND artDictaminado='si'"; 
               //AND docs.doc_validar_carta_ori_ces='si' 	
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
	
    
}