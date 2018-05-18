<?php

class Registroautores_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
//    FunciÃ³n para validar si el usuario esta registrado regresa el id del usuario
    public function validar_usuario($correo, $password) {
        $query = "SELECT ".
                    "usuId,".
                    "usuTipo ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuCorreo='$correo' ".
                "AND ".
                    "usuPassword='$password'";
        
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
    
//    FunciÃ³n para verificar si es el primer ingreso del usuario
    public function primer_ingreso($idUsuario) {
        $query = "SELECT ".
                    "usuPrimerIngreso ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuId=$idUsuario";
        $sth = $this->db->prepare($query);
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();
        $data = $sth->fetchAll();
        $data = $data[0];
        if ($data['usuPrimerIngreso'] == 'si') {
            $data = TRUE;
        }else{
            $data = FALSE;
        }
        return $data;
    }
    
//    FunciÃ³n que obtiene los datos del usuario registrado
    public function datos_usuario($idUsuario) {
        $query = "SELECT ".
                    "usuTipo,".
                    "autNombre,".
                    "autApellidoPaterno,".
                    "autApellidoMaterno ".
                "FROM ".
                    "tblUsuarios AS a ".
                "INNER JOIN ".
                    "tblAutores AS b ".
                    "ON a.usuId=b.usuId ".
                "WHERE ".
                    "a.usuId=$idUsuario";
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
    
    public function registro_usuario($correo, $password) {
        $query = "INSERT INTO ".
                    "tblUsuarios".
                        "(".
                            "usuCorreo,".
                            "usuPassword".
                        ") ".
                    "VALUES ".
                        "(".
                            "'$correo',".
                            "'$password'".
                        ")";
        $data = NULL;
        $sth   = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = TRUE;
            $data = $this->db->lastInsertId();
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
    
//    Agregar el registro del autor con solo el correo el id del usuario
//    se agrego para corregir el error del autor de contacto
    public function registro_autor($correo, $idUsuario) {
        $query = "INSERT ".
                    "INTO tblAutores".
                        "(".
                            "autCorreo,".
                            "usuId".
                        ") ".
                    "VALUES".
                        "(".
                            "'$correo',".
                            "$idUsuario".
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
    
//  Valida que el correo no este registrado  
    public function existe_correo($correo) {
        $query = "SELECT ".
                    "usuId ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuCorreo='$correo'";
        
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
    
    public function getIdUsuarioPorCorreo($correo) {
        $query = "SELECT ".
                    "usuId ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuCorreo='$correo'";
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
    
    public function restaurarPassword($idUsuario, $password) {
        $query = "UPDATE ".
                    "tblUsuarios ".
                "SET ".
                    "usuPassword='$password' ".
                "WHERE ".
                    "usuId=$idUsuario";
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
    
    public function getIdAutorPorIdUsuario($idUsuario) {
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

}
