<?php

class Articulos extends Controller {

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
            'public/plugins/toastr/toastr.min.css',
            //'views/misarticulos/css/misarticulos.css',
            'public/plugins/datatable/jquery.datatables.min.css'
        );
        $this->view->js = array(
            'public/plugins/toastr/toastr.min.js',
            'views/articulos/js/articulos.js',
            'public/plugins/datatable/jquery.datatables.min.js'
        );
    }

    function index() {
         $this->view->tblArticulos = $this->tablaArticulos();
         $this->view->render("articulos/index");

    }
         function getDetallesArticulo() {
        $idArticulo = $_POST['id'];
        $responseDB = $this->model->get_detalles_articulo($idArticulo);        
        echo strtoupper($responseDB['artTipo']);
    }
 
    
    function actualizarDetalles(){
        $response='';
        if(!isset($_POST)){
            $response='error-post-actualizardetalles';
        }
        else{            
         
        $Recibido=$_POST['Recibido'];
        if($Recibido){
          $response="true";  
        }
        else $response="false";
            
        $Dictaminado=$_POST['Dictaminado'];
        $Avisodecambio=$_POST['Avisodecambio'];
        $Id=$_POST['id'];
        //$reponse = $this->model->update_detalles($Recibido,$Dictaminado,$Avisodecambio,$Id);        
        //$response=$Recibido;
        }
        return $response;
        
    }
    
    function tablaArticulos() {
        $responseDB = $this->model->get_articulos();
        $tabla = '';
        if (!$responseDB) {
            $tabla = '<h2 class="text-center">No hay nig&uacute;n articulo registrado</h2>';
        } else {
            $tabla .= '<table class="table table-striped table-hover" id="tbl-articulos">' .
                    '<thead>' .
                    '<tr>' .
                    '<th class="hidden"></th>'.
                    '<th>Nombre</th>' .
                    '<th>&Aacute;rea</th>' .
                    '<th class="text-center">Recibido</th>' .
                    '<th class="text-center">Dictaminado</th>' .
                    '<th class="text-center">Aviso de cambio</th>' .
                    '</tr>' .
                    '</thead>';
            $tabla .= '<tbody>';
            foreach ($responseDB as $articulo) {
                $tabla .= '<tr>';
                $tabla .= '<td class="hidden">' . $articulo['artId'] . '</td>';
                $tabla .= '<td>' . $articulo['artNombre'] . '</td>';
                $tabla .= '<td>' . $articulo['artAreaTematica'] . '</td>';
                $tabla .= '<td class="text-center">' . $articulo['artRecibido'] . '</td>';
                $tabla .= '<td class="text-center">' . $articulo['artDictaminado'] . '</td>';
                $tabla .= '<td class="text-center">' . $articulo['artAvisoCambio'] . '</td>';
                $tabla .= '</tr>';
            }
            $tabla .= '</tbody>';
            $tabla .= '</table>';
        }
        return $tabla;
    }
    
      function getAutoresArticulo() {
        $idArticulo=$_POST['id'];
        $response = '';
        if (!empty($idArticulo)) {
            $responseDB = $this->model->get_autores_articulo($idArticulo);
            foreach ($responseDB as $autor) {
                $response .= '<li class="list-group-item" value="'.$autor['autId'].'">';
                $response .= $autor['autNombre'] .' '.$autor['autApellidoPaterno'].' '.$autor['autApellidoMaterno'];
                $response .= '</li>';
            }
        }
        echo $response;
    }
    
    
}
