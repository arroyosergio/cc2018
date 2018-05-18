<?php

class cambiarpwd extends Controller {

    function __construct() {
        parent::__construct();
		Session::init();
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
			'views/cambiarpwd/css/cambiarpwd.css',
			'views/cambiarpwd/css/menu.css'
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
            'public/js/bootstrap.min.js',
            'public/js/jquery.fluidbox.min.js',
			'views/cambiarpwd/js/cambiarpwd.js'
        );
        
    }

    function index() {
        $this->view->render("cambiarpwd/index");
    }

    function cambiarpasswd() {
        $passActual = md5($_POST['password-actual']);
        $password = $_POST['nuevo-password'];
        $rpassword = $_POST['rpassword'];
        $idUsuario = Session::get('id');
        $response = '';
        if (!empty($password) && !empty($rpassword)) {
            $passwordValido = $this->model->validar_password($idUsuario, $passActual);
            if ($passwordValido) {
                if ($password == $rpassword) {
                    $responseDB = $this->model->cambiar_password($idUsuario, md5($password));
                    if (!$responseDB) {
                        $response = 'error-actualizar';
                    } else {
                        $response = 'true';
                    }
                } else {
                    $response = 'error-password';
                }
            } else {
                $response = 'error-validacion';
            }
        }else{
            $response = 'error-vacio';
        }
        echo $response;
    }	
    
    function cerrarSesion() { 
        Session::init();
        Session::destroy();
        header('location: ../index');
    }
    



}
