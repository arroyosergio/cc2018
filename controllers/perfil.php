<?php

class Perfil extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get("sesion");
        if (!$logged) {
            Session::destroy();
            header("location: index");
            exit;
        }
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'views/perfil/css/perfil.css',
			'views/perfil/css/perfil-menu.css'
        );
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
            'public/bootstrap/js/bootstrap.min.js',
            'views/perfil/js/perfil.js'
        );
    }
    
    function index() {
        $this->view->selectPaises = $this->selectPaises();
        $this->view->render("perfil/index");
    }

    function infoPerfil() {
        $idUsuario = Session::get('id');
        
        $responseDB = $this->model->get_perfil($idUsuario);
        
        
        if (!$responseDB) {
            echo 'no-datos';
        }else{
            $perfil = array(
                "nombre" => $responseDB['autNombre'],
                "apellidoPaterno" => $responseDB['autApellidoPaterno'],
                "apellidoMaterno" => $responseDB['autApellidoMaterno'],
                "ciudad" => $responseDB['autCiudad'],
                "estado" => $responseDB['autEstado'],
                "pais" =>  $responseDB['autPais'],
                "estados" =>'', // $this->model->get_estados($responseDB['autPais']),
                "gradoAcademico" => $responseDB['autGradoAcademico'],
                "institucionProcedencia" => $responseDB['autInstitucionProcedencia'],
                "tipoInstitucion" => $responseDB['autTipoInstitucion'],
                "asistenciaCica" => $responseDB['autAsistenciaCica']
            );
            
            $estadosPais = $this->model->get_estados($responseDB['autPais']);
            
            $estados = array();
            foreach ($estadosPais as $value) {
                $value['nombre'] = utf8_encode($value['est_nombre']);
                array_push($estados, $value['nombre']);
            }
            $perfil['estados'] = $estados;
            
            echo json_encode($perfil);
        }
    }
    
    function guardarCambiosPerfil() {
        $nombre = $_POST['nombre'];
        $apellidoPaterno = $_POST['apellido-paterno'];
        $apellidoMaterno = $_POST['apellido-materno'];
        $ciudad = $_POST['ciudad'];
		if(isset($_POST['estado'])){
			$estado = $_POST['estado'];
		}else{
			$estado = "";
		}
        $pais = $_POST['pais'];
        $gradoAcademico = $_POST['grado-academico'];
        $institucionProcedencia = $_POST['institucion-procedencia'];
        $tipoInstitucion = $_POST['tipo-institucion'];
        $asistenciaCica = $_POST['asistencia-cica'];
        $idUsuario = Session::get('id');
        $correo = $this->model->get_correo_usuario($idUsuario);
//        Valida que todos los campos no esten nulos
        if (!empty($nombre) && !empty($apellidoPaterno) && !empty($apellidoMaterno)
        && !empty($ciudad) && !empty($estado) && !empty($pais)
        && !empty($gradoAcademico) && !empty($institucionProcedencia)
        && !empty($tipoInstitucion) && !empty($asistenciaCica)) {
            $perfil = array(
                'nombre' => $nombre,
                'apellidoPaterno' => $apellidoPaterno,
                'apellidoMaterno' => $apellidoMaterno,
                'ciudad' => $ciudad,
                'estado' => $estado,
                'pais' => $pais,
                'correo' => $correo,
                'gradoAcademico' => $gradoAcademico,
                'institucionProcedencia' => $institucionProcedencia,
                'tipoInstitucion' => $tipoInstitucion,
                'asistenciaCica' => $asistenciaCica,
                'idUsuario' => $idUsuario
            );
//            Verifica si el usuario ya tiene un registro en la tabla autores
//            Si es asi solo actualiza si no realiza un nuevo registro
            $existeAutor = $this->model->existe_autor($idUsuario);
            if ($existeAutor) {
                $responseDB = $this->model->update_datos_perfil($perfil);   
            }else{
                $responseDB = $this->model->update_datos_perfil($perfil);   
//                $responseDB = $this->model->guardar_datos_perfil($perfil);
            }
            
            if ($responseDB) {
//                Actualiza que ya no es su primer ingreso y ya tiene completo
//                los datos de su perfil
                $this->model->actualizar_ingreso($idUsuario);
                Session::set('usuario', "$perfil[nombre] $perfil[apellidoPaterno] $perfil[apellidoMaterno]");
                echo 'true';
            }else{
                echo 'error-registro';
            }
        }else{
            echo 'error-vacio';
        }
    }
    
    function cambiarPassword() {
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
    
    function selectPaises() {
        $responseDB = $this->model->get_paises();
        $select = '';
        $select = '<option value="">Selecciona uno</optioin>';
        foreach ($responseDB as $pais) {
            $select .= '<option value="' . $pais['pais_id'] . '">' . utf8_encode($pais['pais_nombre']) . '</optioin>';
        }
        return $select;
    }

    function getEstados() {
        $codigoPais = $_POST['codigo'];
        $responseDb = $this->model->get_estados($codigoPais);
        $select = '<option value="">Selecciona uno</optioin>';
        foreach ($responseDb as $estado) {
            $select .= '<option value="' . $estado['est_nombre'] . '">' . utf8_encode($estado['est_nombre']) . '</optioin>';
        }
        echo $select;
    }

}