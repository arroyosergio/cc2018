<?php

class Notificaciones_Model extends Model {
     function __construct() {
          parent::__construct();
     }

     public function get_dispositivos() {
        $query = "SELECT ".
                    "token ".
                "FROM ".
                    "tbl_dispositivos";

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

    public function guardar_notificacion($titulo, $mensaje){
        $query = "INSERT INTO ".
                    "tbl_notificaciones".
                        "(".
                            "titulo,".
                            "mensaje".
                        ") ".
                    "VALUES ".
                        "(".
                            "'$titulo',".
                            "'$mensaje'".
                        ")";
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
}





