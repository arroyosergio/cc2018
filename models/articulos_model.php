<?php

class Articulos_Model extends Model {

    function __construct() {
        parent::__construct();
    }
  public function get_articulos() {
        $query = "SELECT ".
                    "b.artId,".
                    "artNombre,".
                    "artArchivo,".
                    "artAreaTematica,".
                    "artRecibido,".
                    "artDictaminado,".
                    "artAvisoCambio ".
                "FROM ".
                    "tblAutoresArticulos AS a ".
                "JOIN ".
                    "tblArticulos AS b ".
                    "ON a.artId=b.artId ".
                "Group By b.artId";
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
    public function update_detalles($Recibido,$Dictaminado,$Avisodecambio,$Id){
        $response=false;
        $query = "UPDATE tblArticulos "
                . "SET "
                . "artRecibido =  ,"
                . "artDictaminado =  ,"
                . "artAvisoCambio =   "
                . "WHERE"
                . "artId= $Id";
        $sth = $this->db->prepare($query);
        if($sth->execute())
        {
            $response=true;
        }
        return $response;
        
        
    }
    public function get_detalles_articulo($idArticulo) {
        $query = "SELECT ".
                    "artTipo ".
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
    
       public function get_autores_articulo($idArticulo) {
        $query = "SELECT ".
                    "tblAutores.autId as autId,".
                    "autNombre,".
                    "autApellidoPaterno,".
                    "autApellidoMaterno ".
                "FROM ".
                    "tblAutores ".
                "INNER JOIN ".
                    "tblAutoresArticulos ".
                    "ON tblAutores.autId=tblAutoresArticulos.autId ".
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