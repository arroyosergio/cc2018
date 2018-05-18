<?php

/*
 * Controlador de los pagos del autor
 */
class mispagos extends Controller {

    /*
     * Crea instancias de la clase.
     */
     function __construct() {
         
          parent::__construct();
         
         //Inicio de sesión valido?
          Session::init();
          $logged = Session::get("sesion");
          if (!$logged) {
               Session::destroy();
               header("location: index");
               exit;
          }
         //Carga de hojas de estilo
          $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/mispagos/css/mispagos.css',
		    'views/mispagos/css/menu.css'
          );
         
          //Carga de scripts del lado de cliente.
          $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/mispagos/js/mispagos.js'
          );
     }//Fin__construct

    /*
     * Renderiza la pagina de mis pagos.
     */
     function index() {
          $this->view->tblArticulos = $this->tablaArtDictaminados();
          $this->view->render("mispagos/index");
     }//Fin index


    /*
     * Crea la tabla de pagos, la tabla muestra los articulos dictaminados.
     */
     function tablaArtDictaminados() {
          $id = Session::get('id');
         
          //Identifica el autor y sus artículos dictaminados
          $idAutor = $this->model->get_id_autor($id);
          $responseDB = $this->model->get_art_dictaminados($idAutor);
         
         //En caso de existir articulos dictaminados crea una tabla html
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No tienes nig&uacute;n art&iacute;culo dictaminado</h2>';
          } else {
               $tabla .= '<table class="table table-striped table-hover" id="tbl-articulos">' .
				      '<col width="5%">'.
  					  '<col width="53%">'.
				   	  '<col width="15%">'.
				      '<col width="10%">'.
                      '<col width="10%">'.
                       '<thead>' .
                       '<tr>' .
                       '<th class="">Id</th>' .
                       '<th>Nombre del art&iacute;culo</th>' .
                       '<th>&Aacute;rea tem&aacute;tica</th>' .
                       '<th class="text-center">Tipo art&iacute;culo</th>' .
                       '<th class="text-center">Pago</th>' .
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
                   

                   
                    /*if ($this->getEstatusCambios($articulo['artId']) == 'si' ) {*/
                        $tabla .= '<td class="text-center"><a href="registroasistencia?id='.$articulo['artId'].'"><span class="glyphicon glyphicon-upload"></span> Formato</a></td>';
                    /*}else{
                        $tabla .= '<td class="text-center"></td>';
                    }*/
                    $tabla .= '</tr>';
               }
               $tabla .= '</tbody>';
               $tabla .= '</table>';
          }
          return $tabla;
     }//Fin tablaArtDictaminados
    
    /*
     * Recupera el datos del estado de cambio permitod de la asistencia
     */
    function getEstatusCambios($idArticulo) {
        //$idArticulo = Session::get('idArticulo');
        $responseDB = $this->model->get_estatus_cambios($idArticulo);
        return $responseDB;
    }//Fin getEstatusCambios
    
    /*
     * Recupera el datos del estado del registro
     */
    function getEstatusRegistro($get = FALSE) {
        $idArticulo = Session::get('idArticulo');
        $responseDB = $this->model->get_estatus_registro($idArticulo);
        
        if ($get) {
            return $responseDB;
        } else {
            echo $responseDB;
        }
    }//Fin getEstatusRegistro

}//Fin mispagos
