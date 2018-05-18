<?php

class Gafete extends Controller {

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
			'views/gafete/css/gafete.css',
			'views/gafete/css/menu.css'
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
            'public/js/bootstrap.min.js',
            'public/js/jquery.fluidbox.min.js',
			'views/gafete/js/gafete.js'
        );		
    }

    function index() {
        $this->view->tblArticulosPagados = $this->tablaArtPagados();
        $this->view->render("gafete/index");
    }
	
	
	function tablaArtPagados() {
          $idAutor = $this->model->get_id_autor(Session::get('id'));
          $responseDB = $this->model->get_art_registro_pago($idAutor);
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No existen art&iacute;culos con registro pagado</h2>';
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

    function listaAsistentes() {
		if(isset($_POST['idArticulo'])){
			$id=$_POST['idArticulo'];	
		}
		else{
			$id="";
		}
        $responseDB = $this->model->get_asistentes($id);
        if (!$responseDB) {
            $tabla = '<h2 class="text-center">No tienes nig&uacute;n asistentes registrado</h2>';
        } else {
            $tabla = '<table class="table table-striped table-hover" id="tbl-asistentes">' .
					  '<col width="10%">'.
					  '<col width="60%">'.
					  '<col width="10%">'.
					  '<col width="20%">'.				
                    '<thead>' .
                    '<tr>' .
                    '<th class="">Art&iacute;culo</th>' .
                    '<th>Nombre asistente</th>' .
                    '<th>Tipo</th>' .
                    '<th class="text-center">Gafete</th>' .
                    '</tr>' .
                    '</thead>';
            $tabla .= '<tbody>';
            foreach ($responseDB as $asistente) {
                $tabla .= '<tr>';
                $tabla .= '<td class="td-tabla">' . $asistente['art_id'] . '</td>';
                $tabla .= '<td class="td-tabla">' . utf8_encode($asistente['asi_nombre']) . '</td>';
                $tabla .= '<td class="td-tabla">' . $asistente['asi_tipo'] . '</td>';
                $tabla .= '<td class="text-center"><a href="gafete/generarGafete?asi=' . $asistente['asi_id'] . '&id=' . $asistente['art_id'] . '" target="_blank"><span class="glyphicon glyphicon-share-alt"></span> Generar</a></td>';
            }
            $tabla .= '</tr>';
            $tabla .= '</tbody>';
            $tabla .= '</table>';
        }
        echo $tabla;
    }

    function generarGafete() {
        if (empty($_GET['id']) || empty($_GET['asi'])) {
            header("location: index");
        } else {
			$asistenteValido=$this->model->getValidar_art_asistente($_GET['id'],$_GET['asi']); 
			if($asistenteValido){
				$asistente = $this->model->get_datos_asistente($_GET['id'], $_GET['asi']);
				$nombreEvento = "Congreso Interdisciplinario de Cuerpos Académicos 2018";
				$nombreEvento = utf8_decode($nombreEvento);

				$fecha = "27y 28 de Septiembre del 2017 Guanajuato, Gto.";
				$lugar = "Centro de Convenciones y Auditorio Pueblito de Rocha s/n " .
						"Guanajuato, Gto.";
				$pdf = $this->GeneratePDF;

				$pdf->AddPage();
				$pdf->AddFont('Sansation', '', 'Sansation_Bold.php');
				$pdf->SetMargins(20, 20, 10);
				$pdf->SetDrawColor(217, 217, 217);
				$pdf->SetLineWidth(1.5);
				$pdf->SetFont('Sansation', '', 12);

				$pdf->Rect(65, 10, 70, 10);
				$pdf->Rect(65, 10, 70, 120);

				//        LOGO CICA
				$pdf->Image('./public/img/cica2018.png', 78, 22, 45, 45);


				//        CODIGO DE BARRAS
				$pdf->Rect(65, 115, 70, 15);
				$pdf->EAN13(72, 116, $asistente['art_id'], 8, .60);

				//        PONENCIA
				$pdf->SetXY(65, 68);
				$nombreArticulo = $asistente['artNombre'];
				$nombreArticulo = substr($nombreArticulo, 0, 90);
				$pdf->MultiCell(70, 6, $nombreArticulo, 0, 'C', false);

				//        AUTOR
				$pdf->SetXY(65, 94);
				$pdf->SetFontSize(14);
				$pdf->MultiCell(70, 6, $asistente['asi_nombre'], 0, 'C', false);

				//        TIPO DE ASISTENTE
				$pdf->SetXY(65, 105);
				$pdf->SetFillColor(15, 117, 188);
				$pdf->SetFontSize(16);
				$pdf->SetTextColor(255, 255, 255);
				$tipo = $asistente['asi_tipo'];
				if ($tipo == 'general') {
					$tipo = 'publico general';
				}
				$pdf->MultiCell(70, 10, utf8_decode(strtoupper($tipo)), 0, 'C', TRUE);

				$pdf->SetFontSize(12);
				$pdf->SetTextColor(0, 0, 0);

				$pdf->SetFillColor(217, 217, 217);
				$pdf->Circle(101, 15, 3);

				$pdf->Output('I', 'gafete.pdf');
			
			}else{
				header("location: index");
			}
        }
    }
	

}


