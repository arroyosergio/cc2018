<?php

class registroautores extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'views/registroautores/css/style.css'
        );
        $this->view->js = array(
			
			'public/js/jquery-2.1.4.min.js',
            'public/bootstrap/js/bootstrap.min.js',
            'views/registroautores/js/registroautores.js',
        );
    }

    function index() {
        $this->view->render("registroautores/index");
    }


    
    function nuevoRegistro() {
        $pass = $_POST['password'];
        $rPass = $_POST['rpassword'];
        $correo = $_POST['correo'];
        $correoValido = $this->IsEmail($correo);
        if ($correoValido) {
            if (empty($pass) || empty($rPass)) {
                echo 'error-null';
            } else {
                if ($pass != $rPass) {
                    echo 'error-pass';
                } else {
                    $existeCorreo = $this->model->existe_correo($correo);
                    if ($existeCorreo) {
                        echo 'error-correo-registrado';
                    } else {
                        $responseDB = $this->model->registro_usuario($correo, md5($pass));
                        if (!$responseDB) {
                            echo 'error-registro';
                        } else {
                            $idAutor = $responseDB;
//                        Crea el registro del autor
                            $this->model->registro_autor($correo, $idAutor);
                            echo 'true';
                        }
                    }
                }
            }
        } else {
            echo 'error-correo';
        }
    }


	
	function IsEmail($email) {
	  $emailValido=false;	
	  $regex = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/";
	  if(preg_match($regex,$email))
		$emailValido=true;
	  return $emailValido;
	}
	
    function comprobarCorreo($correo) {
        $correoCorrecto = 0;
        if ((strlen($correo) >= 6) && (substr_count($correo, '@') == 1) && (substr($correo,0,1) != '@') && (substr($correo, strlen($correo)-1,1) != '@')) {
            if ((!strstr($correo, "'")) && (!strstr($correo, "\"")) && (!strstr($correo, "\\")) && (!strstr($correo, "\$")) && (!strstr($correo, " "))) {
                if (substr_count($correo, ".") >= 1) {
                    $term_dom = substr(strrchr($correo, '.'), 1);
                    if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom, '@'))) {
                        $antes_dom = substr($correo,0, strlen($correo)-strlen($term_dom)-1);
                        $caracter_ult = substr($antes_dom, strlen($antes_dom)-1,1);
                        if ($caracter_ult != '@' && $caracter_ult != '.') {
                            $correoCorrecto = 1;
                        }
                    }
                }
            }
        }
        if ($correoCorrecto) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

}
