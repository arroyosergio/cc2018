<?php

class Usuario_Model extends Model{

	function __construct(){
		parent::__construct();
	}

	public function userList(){
		$sth=$this->db->prepare('SELECT usuId,usuCorreo,usuPassword,usuTipo FROM tblUsuarios');
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$data=$sth->fetchall();
		return $data;
	}

	public function userSingleList($id){
		$sth=$this->db->prepare('SELECT usuId,usuCorreo,usuPassword,usuTipo FROM tblUsuarios WHERE usuId=:id');
		try{
			$sth->execute(array(
					':id'=>$id));
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$data=$sth->fetchall();
		}catch(PDOException $exc){
            error_log($exc);
            $data=NULL;
		}
		echo json_encode($data);
	}


	public function create($data){
		$sth=$this->db->prepare('INSERT INTO  tblUsuarios(usuCorreo,usuPassword,usuTipo) VALUES(:usucorreo,:usupassword,:usutipo)');
		$accion=TRUE;
		try{
			$sth->execute(array(
			'usucorreo'=>$data['correo'],
			'usupassword'=>$data['password'],
			'usutipo'=>$data['role'],
			));

		}catch(PDOException $exc){
            error_log($query);
            error_log($exc);
            $accion = FALSE;
		}	
		return $accion;
	}

	public function edit(){
		$this->view->usuario=$this->model->userSingleList($id);
		$this->view->render('usuario/edit');
	}

	public function editSave($data){
		$resp = NULL;
		$sth=$this->db->prepare('UPDATE  tblUsuarios SET usuCorreo=:usucorreo,usuPassword=:usupassword,usuTipo=:usutipo	WHERE usuId=:id');
		try {
			$sth->execute(array(
				'usucorreo'=>$data['correo'],
				'usupassword'=>$data['password'],
				'usutipo'=>$data['role'],
				'id'=>$data['id'],
				));
				$resp = TRUE;
		} catch (PDOException $exc) {
			$resp = FALSE;
		}
		return $resp;
	}

	public function delete($id){
        $data = NULL;
		$sth=$this->db->prepare('DELETE FROM tblUsuarios WHERE usuId=:id');
        try {
			$sth->execute(array(
			':id'=>$id));
            $data = TRUE;
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
	}


//  Valida que el correo no este registrado  
    public function existe_correo($correo,$id=NULL) {
        $query = "SELECT ".
                    "usuId ".
                "FROM ".
                    "tblUsuarios ".
                "WHERE ".
                    "usuCorreo='$correo' ".(empty($id)?'':' AND usuId<>'.$id);
        
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
}


