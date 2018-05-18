<?php

class constancia_Model extends Model {

    function __construct() {
        parent::__construct();
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
    
}


