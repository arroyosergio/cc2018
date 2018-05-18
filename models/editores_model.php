<?php

class Editores_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_articulos() {
        $query = "SELECT ".
                    "artId,".
                    "artNombre,".
                    "artArchivo,".
                    "revision_editor ".
                "FROM ".
                    "tblArticulos ".
                "WHERE art_validacion_deposito = 'si'";
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
    
     public function get_ultima_version_articulo($idArticulo) {
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
    
    public function update_estatus_revisado($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "revision_editor='$estatus' ".
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

}