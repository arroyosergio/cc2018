<?php


class usuario extends Controller{
	public function __construct() {
        parent::__construct();
        Session::init();
        $logged=Session::get('sesion');
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
			'views/usuario/css/menu.css',
            'public/css/general.css',
            'views/usuario/css/usuario.css',
            
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
			'public/plugins/datatable/jquery.datatables.min.js',
            'views/usuario/js/usuario.js',
        );

      $role=Session::get('perfil');
      if($logged==false || $role!='administrador'){
        	Session::destroy();
        	header('location:login');
        	exit;
        }
    }

	public function index()
	{
        $this->view->userList=$this->model->userList();
        $this->view->render('usuario/index');
	}

    public function edit(){
        //$this->view->usuario=
        $this->model->userSingleList($_POST['id']);
    }

    public function editSave(){ 
        $pass = $_POST['password'];
        $rPass = $_POST['rpassword'];
        $correo = $_POST['correo'];
        $usrid = $_POST['usrid']; 
        $role = $_POST['role'];
        $correoValido = $this->comprobarCorreo($correo);
        if ($correoValido) {
            if ($pass != $rPass) {
                echo 'error-pass';
            } else {
                $existeCorreo = $this->model->existe_correo($correo,$usrid);
                if ($existeCorreo) {
                    echo 'error-correo-registrado';
                }else{
                    $data=array();
                    $data['correo']= $correo;
                    $data['password']=md5($pass);
                    $data['role']=$role;
                    $data['id']=$usrid;
                    $responseDB = $this->model->editSave($data);
                    if (!$responseDB) {
                        echo 'error-registro';
                    }else{
                        echo 'true';
                    }
                }
            }
        }else{
            echo 'error-correo';
        }
    }

    public function delete(){
        $responseDB =$this->model->delete($_POST['id']);
        if (!$responseDB){
            echo 'false';
        }else{
            echo 'true';
        }
    }

    public function nuevoRegistro() {
        $pass = $_POST['password'];
        $rPass = $_POST['rpassword'];
        $correo = $_POST['correo'];
        $correoValido = $this->comprobarCorreo($correo);
        if ($correoValido) {
            if(empty($pass)){
                echo 'error-emptypass';
            } else if ($pass != $rPass) {
                echo 'error-pass';
            } else {
                $existeCorreo = $this->model->existe_correo($correo);
                if ($existeCorreo) {
                    echo 'error-correo-registrado';
                }else{
                    $data=array();
                    $data['correo']= $_POST['correo'];
                    $data['password']=md5($_POST['password']);
                    $data['role']=$_POST['role'];
                    $responseDB = $this->model->create($data);
                    if (!$responseDB) {
                        echo 'error-registro';
                    }else{
                        echo 'true';
                    }
                }
            }
        }else{
            echo 'error-correo';
        }
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