<?php

class cartasautor extends Controller {

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
            'views/cartasautor/css/cartasautor.css',
		    'views/cartasautor/css/menu.css'
          );
          $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/cartasautor/js/cartasautor.js'
          );
     }

     function index() {
          //$this->view->selectPaises = $this->selectPaises();
          $this->view->tblArticulos = $this->tablaArtDictaminados();
          $this->view->render("cartasautor/index");
     }


     function tablaArtDictaminados() {
          $idAutor = $this->model->get_id_autor(Session::get('id'));
          $responseDB = $this->model->get_art_dictaminados($idAutor);
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No tienes nig&uacute;n art&iacute;culo dictaminado</h2>';
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
                    $tabla .= '</tr>';
               }
               $tabla .= '</tbody>';
               $tabla .= '</table>';
          }
          return $tabla;
     }
	
	function update_status_cambios(){
		$response='false';
		try{
			$idArticulo = $_POST['idArticulo'];
			$status=$_POST['status'];
			$this->model->update_estatus_cambios($idArticulo, $status);
			$response = 'true';			
		}catch(Exception $ex){
			error_log($ex->getMessage());			
		}
		echo $response;
	} 



	
     function getDetallesArticulo() {
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_detalles_articulo($idArticulo);
          $response = array(
              'area' => $responseDB['artAreaTematica'],
              'tipo' => $responseDB['artTipo'],
              'cambio' => $responseDB['art_cambio_cartas'],
              'dictaminado' => $responseDB['artDictaminado']
          );
          echo json_encode($response);
     }



     function subirCartaOriginalidad() {
          $response = '';
          $idArticulo = $_POST['id-articulo-original'];
          if (!empty($idArticulo)) {
               $validacionCambio = $this->model->get_estatus_dictaminado($idArticulo);
               if ($validacionCambio) {
                    $existeCarta = $this->model->existe_carta_originalidad($idArticulo);
                    if ($existeCarta['doc_carta_originalidad'] != NULL) {
                         try {
							 if (file_exists(DOCS . $idArticulo.'/' .$existeCarta['doc_carta_originalidad'])){
							 	unlink(DOCS . $idArticulo .'/' . $existeCarta['doc_carta_originalidad']);
							 }
                         } catch (Exception $exc) {
                              error_log($exc->getTraceAsString());
                         }
                    }
                    $file = $_FILES['input-carta-originalidad']['name'];
                    $formatoArchivo = explode('.', $file);
                    $formatoArchivo = end($formatoArchivo);
                    if ($formatoArchivo != 'pdf') {
                         echo 'error-formato-archivo';
                    } else {
						
                         if (!move_uploaded_file($_FILES['input-carta-originalidad']['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                              echo 'error-subir-archivo';
                         } else {
                              $this->model->registro_carta_originalidad($idArticulo, $idArticulo . '/' . $file);
                              echo 'true';
                         }
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

     function subirCartaCesionDerechos() {
          $response = '';
          $idArticulo = $_POST['id-articulo-cesion'];
          if (!empty($idArticulo)) {
               $validacionCambio = $this->model->get_estatus_dictaminado($idArticulo);
               if ($validacionCambio) {
                    $existeCarta = $this->model->existe_carta_cesion($idArticulo);
                    if ($existeCarta['doc_carta_cesion_der'] != NULL) {
                         try {
							 if (file_exists(DOCS . $idArticulo.'/' .$existeCarta['doc_carta_cesion_der'])){
							 	unlink(DOCS . $idArticulo .'/'. $existeCarta['doc_carta_cesion_der']);
							 }							 
                         } catch (Exception $exc) {
                              error_log($exc->getTraceAsString());
                         }
                    }
                    $file = $_FILES['input-carta-derechos']['name'];
                    $formatoArchivo = explode('.', $file);
                    $formatoArchivo = end($formatoArchivo);
                    if ($formatoArchivo != 'pdf') {
                         echo 'error-formato-archivo';
                    } else {
                         if (!move_uploaded_file($_FILES['input-carta-derechos']['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                              echo 'error-subir-archivo';
                         } else {
                              $this->model->registro_carta_cesion_derechos($idArticulo, $idArticulo . '/' . $file);
                              echo 'true';
                         }
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

	
	//===============================================================================
	//METODO PARA VALIDAR SI ESTAS CARGADAS LAS CARTAS DE CESION DE DERECHOS Y ORIGINALIDAD
	//================================================================================
     function getCartas() {
          $idArticulo = $_POST['id'];
          $cartaOriginalidad = $this->model->existe_carta_originalidad($idArticulo);
          $cartaDerechos = $this->model->existe_carta_cesion($idArticulo);
          $response = '<div class="col-sm-6">';
          $response .= '<h4 class="text-primary">Carta de originalidad:</h4>';
		 if (is_null($cartaOriginalidad['doc_carta_originalidad'])) {
               $response .= '<p>No hay ningún documento</p>';
          } else {
               $cartaOriginalidad = explode('/', $cartaOriginalidad['doc_carta_originalidad']);
               if(count($cartaOriginalidad)>1)
               		$response .= '<p>' . $cartaOriginalidad[1] . '</p>';
               else 
               	$response .= '<p>' . $cartaOriginalidad[0] . '</p>';
          }
          $response .= '</div>';
          
          $response .= '<div class="col-sm-6">';
          $response .= '<h4 class="text-primary">Carta de cesión de derechos:</h4>';
		 if (is_null($cartaDerechos['doc_carta_cesion_der'])) {
               $response .= '<p>No hay ningún documento</p>';
          } else {
               $cartaDerechos = explode('/', $cartaDerechos['doc_carta_cesion_der']);
               if(count($cartaDerechos)>1)
               	$response .= '<p>' . $cartaDerechos[1] . '</p>';
               else 
               	$response .= '<p>' . $cartaDerechos[0] . '</p>';
          }
          $response .= '</div>';
          echo $response;
     }

}
