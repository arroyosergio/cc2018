<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/css/style.css',
            'public/css/responsiveslides.css',
			'views/index/css/index.css'			
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
            'public/js/bootstrap.min.js',
            'public/js/index.js',
            'public/js/jquery.fluidbox.min.js',
            'public/js/responsiveslides.min.js'
        );
    }

    function index() {
        $this->view->render("index/index");
    }

    function login() {
        $correo = $_POST['correo'];
        $password = $_POST['password'];
        $correoValido = $this->comprobarCorreo($correo);
        if ($correoValido) {
            $responseDB = $this->model->validar_usuario($correo, md5($password));
            if (!$responseDB) {
                echo 'error-login';
            }else{
                $idUsuario = $responseDB['usuId'];
                $primerIngreso = $this->model->primer_ingreso($idUsuario);
                $idAutor = $this->model->getIdAutorPorIdUsuario($idUsuario);
                Session::init();
                Session::set('sesion', TRUE);
                Session::set('id', $idUsuario);
                Session::set('idAutor', $idAutor);
                Session::set('perfil', $responseDB['usuTipo']);
                 if ($primerIngreso) {
                    echo 'primer-ingreso';
				    $nombreUsuario =substr($responseDB['usuCorreo'],0,strpos($responseDB['usuCorreo'],'@'));
				   Session::set('usuario', $nombreUsuario);	
				}else{
                    //$responseDB = $this->model->datos_usuario($idUsuario);
                    //$nombreUsuario = "$responseDB[autNombre]";
                    $nombreUsuario =substr($responseDB['usuCorreo'],0,strpos($responseDB['usuCorreo'],'@'));
					 Session::set('usuario', $nombreUsuario);
                    echo 'true';
				}
            }
        }else{
            echo 'error-correo';
        }
    }
	

    
    function cerrarSesion() { 
        Session::init();
        Session::destroy();
        header('location: ../index');
    }
    

	function IsEmail($email) {
	  $emailValido=false;	
	  $regex = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/";
	  if(preg_match($regex,$email))
		$emailValido=true;
	  return $emailValido;
	}
	
    function nuevoRegistro() {
        $pass = $_POST['password'];
        $rPass = $_POST['rpassword'];
        $correo = $_POST['correo'];
        $correoValido = $this->comprobarCorreo($correo);
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

    function recuperarPassword() {
        $correo = $_POST['correo'];
        if (!empty($correo)) {
            $correoValido = $this->comprobarCorreo($correo);
            if ($correoValido) {
                $responseDb = $this->model->getIdUsuarioPorCorreo($correo);
                if (!$responseDb) {
                    echo 'error-correo';
                } else {
                    $idUsuario = $responseDb['usuId'];
                    $password = $this->gerarPassword();
                    $responseDb = $this->model->restaurarPassword($idUsuario, md5($password));
                    if (!$responseDb) {
                        echo 'error-operacion';
                    } else {
                        
//                        Apartado para enviar correo
                        $this->mail->setFrom('administracion@higo-software.com', 'CICA');
                        $this->mail->addAddress($correo);
                        $this->mail->isHTML(TRUE);
                        $this->mail->Subject = utf8_decode('Restauración de contraseña');
                        $this->mail->Body = "Tu nueva contrase&ntilde;a para entrar al portal es <br/><b>$password</b>";
                        if (!$this->mail->send()) {
                            error_log("Error al enviar el correo para nueva contraseña a $correo:" . $this->mail->ErrorInfo);
                        }
                        echo 'true';
                    }
                }
            } else {
                echo 'error-formato';
            }
        } else {
            echo 'error-null';
        }
    }

    function gerarPassword() {
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@-_!#$%&()?¡¿";
        $longitudCadena = strlen($cadena);
        $pass = "";
        $longitudPass = 10;

        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
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
