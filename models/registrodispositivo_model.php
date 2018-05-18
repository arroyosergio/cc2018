<?php

class registrodispositivo_Model extends Model {
    function __construct() {
        parent::__construct();
    }

    public function guardar_token($token) {
        $query = "INSERT INTO ".
                    "tbl_dispositivos".
                        "(".
                            'token'.
                        ") ".
                    "VALUES".
                        "(".
                            "'$token'".
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

    public function token_existente($token){
        $query = "SELECT ".
                    "token ".
                "FROM ".
                    "tbl_dispositivos ".
                "WHERE ".
                    "token='" .$token."'";

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

    public function get_ultima_notificacion(){
        $query = "SELECT ".
                    "* ".
                "FROM ".
                    "tbl_notificaciones ".
                "ORDER BY ".
                    "id ".
                "DESC ".
                    "LIMIT 1";
                    
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