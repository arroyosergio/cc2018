<?php

class control_asistencia extends Controller {

	
    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get('sesion');
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/control_asistencia/css/ctrl_asistencia.css',
		    'views/control_asistencia/css/menu.css'
        );
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/control_asistencia/js/control_asistencia.js'
        );

        $role = Session::get('perfil');
        if ($logged == false) {
            Session::destroy();
            header('location:index');
            exit;
        } elseif ($role != 'administrador' && $role != 'asistencia') {
            header('location:index');
        }
    }

    function index() {
        $this->view->tbl_control_asistencia = $this->getArticulos();
        $this->view->render('control_asistencia/index');
    }

    function getArticulos() {
        $responseDB = $this->model->get_articulos();
		$response='';
        if (!$responseDB) {
            $response .= '<h1 class="text-center">No hay Art&iacute;culos registrados.</h1>';
        } else {
            $response = '<table id="tbl-Articulos" class="table table-hover dataTable">';
            $response .= '<thead>';
            $response .= '<tr>';
            $response .= '<th>Id</th>';
            $response .= '<th>Art&iacute;culo</th>';
            $response .= '<th>Tipo</th>';
            $response .= '<th class="text-center">Art. presentado</th>';
            $response .= '<th class="text-center">kit entregado</th>';
            $response .= '</tr>';
            $response .= '</thead>';
            $response .= '<tbody>';
            foreach ($responseDB as $articulo) {
                $response .= '<td>' . $articulo['artId'] . '</td>';
                $response .= '<td>' . $articulo['artNombre'] . '</td>';
                $response .= '<td>' . $articulo['artTipo'] . '</td>';
                if ($articulo['art_presentado'] == 'si') {
                    $response .= '<td class="text-center"><input class="presentado" articulo="' . $articulo['artId'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="presentado" articulo="' . $articulo['artId'] . '" type="checkbox" name=""></td>';
                }
                if ($articulo['art_kit_entregado'] == 'si') {
                    $response .= '<td class="text-center"><input class="kit" articulo="' . $articulo['artId'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="kit" articulo="' . $articulo['artId'] . '" type="checkbox" name=""></td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody>';
            $response .= '</table>';
        }
        return $response;
    }



    function update_estatus_presentado() {
        $idArticulo = $_POST['id'];
        $estatus = $_POST['estatus'];
        $response = '';
        if (!empty($idArticulo) && !empty($estatus)) {
            if ($estatus == 'true') {
                $estatus = 'si';
            } else {
                $estatus = 'no';
            }
            $responseDB = $this->model->update_estatus_presentado($idArticulo, $estatus);
            if (!$responseDB) {
                $response = 'false';
            } else {
                $response = 'true';
            }
        } else {
            error_log('updateEstatusCambios: uno de los parametros se envia nulo');
        }
        echo $response;
    }



//    Actualiza si el deposito ha sido correcto o no 
    function update_estatus_kit_entregado() {
        $idArticulo = $_POST['id'];
        $estatus_kit_entragado = $_POST['estatus'];
        $response = '';
        if (!empty($idArticulo) || !empty($estatus_kit_entragado)) {
            if ($estatus_kit_entragado == 'true') {
                $estatus_kit_entragado = 'si';
            } else {
                $estatus_kit_entragado = 'no';
            }
            $responseDB = $this->model->update_estatus_kit_entregado($idArticulo, $estatus_kit_entragado);
            if (!$responseDB) {
//                error en la consulta
                $response = 'false';
            } else {
                $response = 'true';
            }
        } else {
            error_log('updateEstatusDeposito: error no se reciben todos los valores');
        }
        echo $response;
    }
    


}
