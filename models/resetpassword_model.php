<?php

class resetpassword_Model extends Model{
	
    //  Valida que el correo no este registrado 
	public function existe_correo($correo) {
		$sth=$this->db->prepare('SELECT usuId'.
		                        ' FROM tblUsuarios WHERE usuCorreo=:correo');
		try{
			$sth->execute(array(
					':correo'=>$correo));
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$data=$sth->fetchall();
			$dato = NULL;
			if (count($data) > 0 ) {
				$dato = TRUE;
			} else {
				$dato = FALSE;
			}
		}catch(PDOException $exc){
            error_log($exc);
            $dato=NULL;
		}
		return $dato;
	}


    public function getIdUsuarioPorCorreo($correo) {
		try{
			$sth=$this->db->prepare("SELECT ".
						"usuId ".
					"FROM ".
						"tblUsuarios ".
					"WHERE ".
						"usuCorreo=:correo");
			$sth->execute(array(
					':correo'=>$correo));
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$data=$sth->fetchall();
			if (count($data) > 0 ) {
				$data = $data[0];
			} else {
				$data = FALSE;
			}			
		}
		catch(PDOException $exc) {
            error_log($exc);
            $data=NULL;
		}
	
        return $data;
    }
	
	public function restaurarPassword($idUsuario, $password) {
		$resp = NULL;
		$sth=$this->db->prepare('UPDATE  tblUsuarios SET '.                                                       
						        'usuPassword=:usupassword '.
								'WHERE usuId=:idusuario');
		try {
			$sth->execute(array(
				'usupassword'=>$password,
				'idusuario'=>$idUsuario
				));
				$resp = TRUE;
		} catch (PDOException $exc) {
             error_log($exc);			
			$resp = FALSE;
		}
		return $resp;
	}
}
