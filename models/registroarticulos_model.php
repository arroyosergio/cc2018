<?php

class Registroarticulos_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    public function get_paises() {
        $query = "SELECT ".
                    "Name,".
                    "Code ".
                "FROM ".
                    "country ".
                "ORDER BY ".
                    "Name ".
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
                    "Name ".
                "FROM ".
                    "province ".
                "WHERE ".
                    "Country=$codigoPais";
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
    
    public function get_ciudades($codigoEstado) {
        $query = "SELECT ".
                    "Name ".
                "FROM ".
                    "city ".
                "WHERE ".
                    "Province=$codigoEstado";
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
