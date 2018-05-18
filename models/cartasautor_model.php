<?php

class cartasautor_Model extends Model {

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
    public function get_art_dictaminados($idAutor) {
        $query = "SELECT ".
                    "b.artId,".
                    "artNombre,".
                    "artAreaTematica,".
                    "artTipo ".
     			"FROM ".
                    "tblAutoresArticulos AS a ".
                "INNER JOIN ".
                    "tblArticulos AS b ".
                    "ON a.artId=b.artId ".
                "WHERE ".
                    "a.autId=$idAutor AND artDictaminado='si'" ;
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

   public function update_estatus_cambios($idArticulo, $estatus) {
        $query = "UPDATE ".
                    "tblArticulos ".
                "SET ".
                    "art_cambio_cartas='$estatus' ".
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
	
    public function get_detalles_articulo($idArticulo) {
        $query = "SELECT ".
                    "artNombre,".
                    "artAreaTematica,".
//                    "artArchivo,".
                    "artTipo,".
                    "art_cambio_cartas,".
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
	
    public function get_estatus_dictaminado($idArticulo) {
        $query = "SELECT ".
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
            $data = $data['artDictaminado'];
            if ($data == 'si') {
                $data = TRUE;
            }else{
                $data = FALSE;
            }
        } else {
            $data = FALSE;
        }
        return $data;
    }
	
    public function existe_carta_originalidad($idArticulo) {
        $query = "SELECT ". 
                    "doc_carta_originalidad ".
                "FROM ".
                    "tblDocumentos ".
                "WHERE ".
                    "art_id=$idArticulo";
          $data = NULL;
          $sth = $this->db->prepare($query);
          $sth->setFetchMode(PDO::FETCH_ASSOC);
          try {
               $sth->execute();
               $data = $sth->fetchAll();
               $data = $data[0];
          } catch (PDOException $exc) {
               error_log($query);
               error_log($exc);
               $data = FALSE;
          }
          return $data;
     }


    
    public function existe_carta_cesion($idArticulo) {
        $query = "SELECT ". 
                    "doc_carta_cesion_der ".
                "FROM ".
                    "tblDocumentos ".
                "WHERE ".
                    "art_id=$idArticulo";
          $data = NULL;
          $sth = $this->db->prepare($query);
          try {
               $sth->setFetchMode(PDO::FETCH_ASSOC);
               $sth->execute();
               $data = $sth->fetchAll();
               $data = $data[0];
          } catch (PDOException $exc) {
               error_log($query);
               error_log($exc);
               $data = FALSE;
          }
          return $data;
     }	
	

     public function registro_carta_originalidad($idArticulo, $carta) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_carta_originalidad='$carta' ".
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
	
	
     public function registro_carta_cesion_derechos($idArticulo, $carta) {
        $query = "UPDATE ".
                    "tblDocumentos ".
                "SET ".
                    "doc_carta_cesion_der='$carta' ".
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
	
    
}