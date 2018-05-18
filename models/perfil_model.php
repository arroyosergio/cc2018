<?php

class Perfil_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    public function guardar_datos_perfil($perfil) {
        $query = "INSERT INTO ".
                    "tblAutores".
                        "(".
                            "autNombre,".
                            "autApellidoPaterno,".
                            "autApellidoMaterno,".
                            "autInstitucionProcedencia,".
                            "autCiudad,".
                            "autEstado,".
                            "autPais,".
                            "autCorreo,".
                            "autGradoAcademico,".
                            "autTipoInstitucion,".
                            "autAsistenciaCica,".
                            "usuId".
                        ") ".
                    "VALUES".
                        "(".
                            "'$perfil[nombre]',".
                            "'$perfil[apellidoPaterno]',".
                            "'$perfil[apellidoMaterno]',".
                            "'$perfil[institucionProcedencia]',".
                            "'$perfil[ciudad]',".
                            "'$perfil[estado]',".
                            "'$perfil[pais]',".
                            "'$perfil[correo]',".
                            "'$perfil[gradoAcademico]',".
                            "'$perfil[tipoInstitucion]',".
                            "'$perfil[asistenciaCica]',".
                            "$perfil[idUsuario]".
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
    
    public function update_datos_perfil($perfil) {
        $query = "UPDATE ".
                    "tblAutores ".
                "SET ".
                    "autNombre='$perfil[nombre]',".
                    "autApellidoPaterno='$perfil[apellidoPaterno]',".
                    "autApellidoMaterno='$perfil[apellidoMaterno]',".
                    "autInstitucionProcedencia='$perfil[institucionProcedencia]',".
                    "autCiudad='$perfil[ciudad]',".
                    "autEstado='$perfil[estado]',".
                    "autPais='$perfil[pais]',".
                    "autCorreo='$perfil[correo]',".
                    "autGradoAcademico='$perfil[gradoAcademico]',".
                    "autTipoInstitucion='$perfil[tipoInstitucion]',".
                    "autAsistenciaCica='$perfil[asistenciaCica]' ".
                "WHERE ".
                    "usuId=$perfil[idUsuario]";
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
    
//    Verifica si el autor ya esta registrado
    public function existe_autor($idUsuario) {
        $query = "SELECT ".
                    "usuId ".
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
            $data = TRUE;
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
//    Optiene toda la información del perfil del usuario
    public function get_perfil($idUsuario) {
        
        $query = "SELECT autNombre, autApellidoPaterno, autApellidoMaterno, autInstitucionProcedencia, ";
        $query .= "autCiudad, autEstado, autPais, autGradoAcademico, autTipoInstitucion, autAsistenciaCica ";
        $query .= " FROM tblAutores ";
        $query .= " WHERE usuId = " . $idUsuario;
        
        
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
    
        
    public function actualizar_ingreso($idUsuario) {
        $query = "UPDATE ".
                    "tblUsuarios ".
                "SET ".
                    "usuPrimerIngreso='no' ".
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
    
    public function validar_password($idUsuario, $password) {
        $query = "SELECT ".
                    "usuId ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuId=$idUsuario ".
                "AND ".
                    "usuPassword='$password'";
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
    
    public function cambiar_password($idUsuario, $password) {
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
    
    public function get_correo_usuario($idUsuario) {
        $query = "SELECT ".
                    "usuCorreo ".
                "FROM ".
                    "tblUsuarios ".
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
            $data = $data['usuCorreo'];
        } else {
            $data = FALSE;
        }
        return $data;
    }
    
    public function get_paises() {
        $query = "SELECT ".
                    "pais_nombre,".
                    "pais_id ".
                "FROM ".
                    "tblPaises ".
                "ORDER BY ".
                    "pais_nombre ".
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
                    "est_nombre ".
                "FROM ".
                    "tblEstados ".
                "WHERE ".
                    "pais_id='$codigoPais'";
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