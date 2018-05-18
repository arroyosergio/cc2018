<?php

class constancias_model extends Model {

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

       public function get_autores_articulo($idArticulo) {
        $query = "SELECT ".
                    "autNombre,".
                    "autApellidoPaterno,".
                    "autApellidoMaterno,".
                    "artNombre ".
                "FROM ".
                    "tblAutores ".
                "INNER JOIN ".
                    "tblAutoresArticulos ".
                    "ON tblAutores.autId=tblAutoresArticulos.autId ".
                    "INNER JOIN tblArticulos ON tblArticulos.artid = tblAutoresArticulos.artid ".
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
	
	
//    Obtiene todos los articulos registrados por el usuario autor
    public function get_constancias($idAutor) {
		$query="SELECT articulos.artId,artNombre,artAreaTematica,artTipo ". 
			   "FROM tblAutoresArticulos AS autores ".
			   " INNER JOIN tblArticulos AS articulos ON articulos.artId=autores.artId ".
			   " WHERE autores.autId=$idAutor AND artDictaminado='si' AND articulos.art_presentado='si'"; 	
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