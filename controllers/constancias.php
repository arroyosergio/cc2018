<?php

require_once "libs/pdf/fpdf.php";

class constancias extends Controller {

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
            'views/constancias/css/constancias.css',
		    'views/constancias/css/menu.css'
          );
          $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/constancias/js/constancias.js'
          );
     }

     function index() {
          $this->view->tblArticulos = $this->tablaArtAceptados();
          $this->view->render("constancias/index");
     }


     function tablaArtAceptados() {
          $idAutor = $this->model->get_id_autor(Session::get('id'));
          $responseDB = $this->model->get_constancias($idAutor);
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No existen art&iacute;culos con constancias</h2>';
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
				       '<th class="hidden">constancia</th>' .
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
	

	 function get_autores_articulo(){
    		$idArticulo=$_POST['id'];
    		if (!empty($idArticulo)) {
    			$responseDB = $this->model->get_autores_articulo($idArticulo);
     		}
    		return  $responseDB;
    }
	
	

	
	
    function generarConstancia() {
		try{
		  if (!empty($_POST['idArticulo'])) {
				$responseDB = $this->model->get_autores_articulo($_POST['idArticulo']);
				// Instanciation of inherited class
				$pdf = new PDF("P","mm","Letter");
				#Establecemos los márgenes izquierda, arriba y derecha:
				$pdf->SetMargins(10, 10 , 10);
				#Establecemos el margen inferior:
				$pdf->SetAutoPageBreak(true,15);
				//SE AGREGA UNA PAGINA AL ARCHIVO PDF
				$pdf->AddPage();
				//COLOCA EL PRIMER AUTOR
				$pdf->SetFontSize(20);
				$fila=118;
				$nombre="";
				$articulo="";

				foreach ($responseDB as $autor) {
					// SE COLOCA EL CURSOR EN LAS COORDINADAS x=10,y=$FILA
					$pdf->SetXY(10,$fila);
					$nombre=$autor["autNombre"].' '.$autor['autApellidoPaterno'].' '.$autor['autApellidoMaterno'];
					//HACE LA CONVERSION PARA CARACTERES ESPECIALES
					$nombre= iconv('UTF-8','windows-1252', $nombre);
					//CONVIERTE EL NOMBRE DEL ARTCICULO EN MAYUSCULAS
					$articulo= mb_strtoupper($autor["artNombre"]);
					//TRANSFORMA EL NOMBRE DEL ART. A FORMATO UTF-8
					$articulo= iconv('UTF-8','windows-1252', $articulo);
					//COLOCA EL NOMBRE DEL CURSOR CENTRADO Y SIN BORDE
					$pdf->Cell(200,10,$nombre,0,1,'C');
					$fila=$fila+7;
				}
				$pdf->SetFontSize(11);
				//SE COLOCA EN POSICION PARA EL NOMBRE DE LA PONENCIA
				if(strlen($articulo)>115){
					$arrayArt = explode(" ", $articulo);
					$total_articulo="";
					$fila_Art=154;
					for($i=0;$i<count($arrayArt);$i++){
						$total_articulo .=$arrayArt[$i]." ";
						if(strlen($total_articulo)>=115){
							$pdf->SetXY(33,$fila_Art);
							$pdf->Cell(10,10,$total_articulo,0,1);
							$total_articulo="";
							$fila_Art+=4;
						}
					}
					if(strlen($total_articulo)>=1){
					   $pdf->SetXY(33,$fila_Art);
					   $pdf->Cell(10,10,$total_articulo,0,1);
					}

				}else {
					$pdf->SetXY(33,155);
					$pdf->Cell(10,10,$articulo,0,1);
				}
				//DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
     		   	//$pdf->Output('Const_Cica2018.pdf','D');
				$pdf->Output(DOCS.$_POST['idArticulo']."/constancia_art_".$_POST['idArticulo'].'.pdf','F');
			}			
		}
		catch(Exception $ex){
			echo $ex->getMessage();
		}
    }
}


class PDF extends FPDF
{
	// Page header
	function Header()
	{
		// Logo
		$this->Image('public/img/constancia.jpg', 0, 0, $this->w, $this->h);
		$this->AddFont('big_noodle_titling', '', 'big_noodle_titling.php');
		$this->SetFont('big_noodle_titling', '', 12);

	}
}





