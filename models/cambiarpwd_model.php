<?php

class cambiarpwd_model extends Model{
	
	
	public function cambiar_password($idUsuario, $password) {
		$resp = NULL;
		$sth=$this->db->prepare('UPDATE  tblUsuarios SET '.                                                       
						        'usuPassword=:usupassword '.
								'WHERE usuId=:idusuario');
		try {
			$sth->execute(array(
				':usupassword'=>$password,
				':idusuario'=>$idUsuario
				));
				$resp = TRUE;
		} catch (PDOException $exc) {
             error_log($exc);			
			$resp = FALSE;
		}
		return $resp;
	}

 	public function validar_password($idUsuario, $password) {
		$data = NULL;
        $query = "SELECT ".
                    "usuId ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuId=:idUsuario ".
                "AND ".
                    "usuPassword=:usuPassword";
		try{
			$sth = $this->db->prepare($query);
			
			$sth->execute(array(
				':idUsuario'=>$idUsuario,
				':usuPassword'=>$password
			));
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$data=$sth->fetchall();
			if (count($data) > 0 ) {
				$data = TRUE;
			} else {
				$data = FALSE;
			}
			
		}catch (Exception $ex){
			error_log($ex->getMessage());
			$data = FALSE;
		}
        return $data;
    }
    
	
	
}
