<?php

class Editores extends Controller {

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
            'views/editores/css/editores.css',
		    'views/editores/css/menu.css'
        );
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/editores/js/editores.js'
        );		
		
       $role = Session::get('perfil');
        if ($logged == false) {
            Session::destroy();
            header('location:index');
            exit;
        } elseif ($role != 'administrador' && $role != 'editor') {
            header('location:index');
        }
    }

    function index() {
        $this->view->tblArticulos = $this->tabla_articulos();
        $this->view->render('editores/index');
    }
    
    function tabla_articulos() {
        $responseDB = $this->model->get_articulos();
        if (!$responseDB) {
            return '<h1>No hay artículos registrados</h1>';
        } else {
            $tabla = '<table class="table table-striped table-hover" id="tbl-articulos">' .
                    '<thead>' .
                    '<tr>' .
                    '<th>Id</th>' .
                    '<th>Nombre</th>' .
                    '<th class="text-center">Archivo</th>' .
                    '<th>Revisado</th>' .
                    '</tr>' .
                    '</thead>';
            $tabla .= '<tbody>';
            foreach ($responseDB as $articulo) {
                $tabla .= '<tr>';
                $tabla .= '<td>' . $articulo['artId'] . '</td>';
                $tabla .= '<td>' . $articulo['artNombre'] . '</td>';
                $tabla .= '<td class="text-center"><a href="./docs/'.$this->getUltimaVersionArticulo($articulo['artId']).'"><span class="glyphicon glyphicon-save"></span> Descargar</a></td>';
                if ($articulo['revision_editor'] == 'si') {
                    $tabla .= '<td class="text-center"><input type="checkbox" class="revisado" value="'.$articulo['artId'].'" checked></td>';
                } else {
                    $tabla .= '<td class="text-center"><input type="checkbox" class="revisado" value="'.$articulo['artId'].'"></td>';
                }
                $tabla .= '</tr>';
            }
            $tabla .= '</tbody>';
            $tabla .= '</table>';
        }
        
        return $tabla;
    }
    
    function getUltimaVersionArticulo($idArticulo) {
        $responseDB = $this->model->get_ultima_version_articulo($idArticulo);
        return $responseDB;
    }
    
    function updateEstatusRevisado() {
        $estatus = $_POST['estatus'];
        $id = $_POST['id'];
        if (!empty($estatus) && !empty($id)) {
            if ($estatus == 'true') {
                $estatus = 'si';
            } else {
                $estatus = 'no';
            }
            $responseDB = $this->model->update_estatus_revisado($id, $estatus);
            if (!$responseDB) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

}
