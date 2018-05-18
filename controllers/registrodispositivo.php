<?php
class Registrodispositivo extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $token = $_POST['token'];
        if(!$this->model->token_existente($token)){
            $estatus = $this->model->guardar_token($token);
            if($estatus){
                echo 'true';
            }else{
                echo 'false';
            }
        }
    }

    function obtener_notificacion(){
        error_log("test");
    }
}

