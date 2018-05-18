<?php

class cartaaceptacion extends Controller {

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
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/cartaaceptacion/css/cartaaceptacion.css',
		    'views/cartaaceptacion/css/menu.css'
          );
          $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/cartaaceptacion/js/cartaaceptacion.js'
          );
     }

     function index() {
          $this->view->tblArticulos = $this->tablaArtAceptados();
          $this->view->render("cartaaceptacion/index");
     }


     function tablaArtAceptados() {
          $idAutor = $this->model->get_id_autor(Session::get('id'));
          $responseDB = $this->model->get_art_aceptados($idAutor);
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">Ning&uacute;n art&iacute;culo tiene carta de aceptaci&oacute;n</h2>';
          } else {
               $tabla .= '<table class="table table-striped table-hover" id="tbl-articulos">' .
				      '<col width="5%">'.
  					  '<col width="53%">'.
				   	  '<col width="30%">'.
				      '<col width="12%">'.
                       '<thead>' .
                       '<tr>' .
                       '<th class="">Id</th>' .
                       '<th>Nombre del art&iacute;culo</th>' .
                       '<th>&Aacute;rea tem&aacute;tica</th>' .
                       '<th class="text-center">Tipo art&iacute;culo</th>' .
				       '<th class="hidden">carta</th>' .
                       '</tr>' .
                       '</thead>';
               $tabla .= '<tbody>';
               foreach ($responseDB as $articulo) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="td-tabla">' . $articulo['artId'] . '</td>';
                    $tabla .= '<td class="td-tabla">' . $articulo['artNombre'] . '</td>';

                    switch ($articulo['artAreaTematica']) {
                         case 'CAYS':
                              $articulo['artAreaTematica'] = 'Ciencias administrativas y sociales';
                              break;
                         case 'EFC':
                              $articulo['artAreaTematica'] = 'Experiencia en formaci&oacute;n CA';
                              break;
                         case 'CA':
                              $articulo['artAreaTematica'] = 'Ciencias agropecuarias';
                              break;
                         case 'CNYE':
                              $articulo['artAreaTematica'] = 'Ciencias naturales y exactas';
                              break;
                         case 'CIYT':
                              $articulo['artAreaTematica'] = 'Ciencias de ingenier&iacute;a y tecnolog&iacute;a';
                              break;
                         case 'E':
                              $articulo['artAreaTematica'] = 'Educaci&oacute;n';
                              break;
                    }
                    $tabla .= '<td class="td-tabla">' . $articulo['artAreaTematica'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $articulo['artTipo'] . '</td>';
				    $tabla .= '<td class="td-tabla hidden">' . $articulo['doc_carta_aceptacion'] . '</td>';
                    $tabla .= '</tr>';
               }
               $tabla .= '</tbody>';
               $tabla .= '</table>';
          }
          return $tabla;
     }
	
	
     function getDetallesArticulo() {
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_detalles_articulo($idArticulo);
          $response = array(
              'area' => $responseDB['artAreaTematica'],
              'tipo' => $responseDB['artTipo'],
              'cambio' => $responseDB['artAvisoCambio'],
              'dictaminado' => $responseDB['artDictaminado']
          );
          echo json_encode($response);
     }



}
