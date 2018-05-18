<?php

class control_asistencia_model extends Model {

     function __construct() {
          parent::__construct();
     }
     
     public function get_articulos() {
          $query = 'SELECT '.
                         'artId,'.
                         'artNombre,'.
                         'artTipo,'.
                         'art_presentado,'.
                         'art_kit_entregado '.
                    'FROM '.
                         'tblArticulos '.
                    'WHERE '.
                         'art_validacion_deposito="si"';
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
      

     
    public function update_estatus_presentado($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "art_presentado='$estatus' ".
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
    
    
    public function update_estatus_kit_entregado($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "art_kit_entregado='$estatus' ".
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
