<?php

class imprimir_gafete extends Controller{

    public function __construct() {
        parent::__construct();
        Session::init();
        $logged=Session::get('sesion');
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'views/imprimir_gafete/css/imprimir_gafete.css',
			'public/plugins/datatable/jquery.datatables.min.css',
			'views/imprimir_gafete/css/menu.css'
        );
				
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'views/imprimir_gafete/js/imprimir_gafete.js',
			'public/plugins/datatable/jquery.datatables.min.js'
        );
        $role=Session::get('perfil');
		  if($logged==false || $role!='administrador'){
				Session::destroy();
				header('location:login');
				exit;
			}
    }

    function index()
    {
        $this->view->render('imprimir_gafete/index');
    }


            
    function generarGafete() {
		try{
			if (empty($_POST['id']) || empty($_POST['nombre-articulo']) || empty($_POST['nombre-asistente']) || empty($_POST['tipo-asistente'])) {
				header("location: ../dashboard?status=error-null");
			} else {
				$nombreEvento = "Congreso Interdisciplinario de Cuerpos AcadÃ©micos 2018";
				$nombreEvento = utf8_decode($nombreEvento);

				$fecha = "17 y 18 de noviembre de 2018 Guanajuato, Gto.";
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
				$pdf->Image('./public/img/cica2017.png', 84, 22, 35, 45);

	//        CODIGO DE BARRAS
				$pdf->Rect(65, 115, 70, 15);
				$pdf->EAN13(72, 116,is_numeric($_POST['id'])?$_POST['id']:'0000', 8, .60); 

	//        PONENCIA
				$pdf->SetXY(65, 67);
				$nombreArticulo = $_POST['nombre-articulo'];
				$nombreArticulo = substr($nombreArticulo, 0, 100);
				$pdf->MultiCell(70, 6, utf8_decode($nombreArticulo), 0, 'C', false);

	//        AUTOR
				$pdf->SetXY(65, 94);
				$pdf->SetFontSize(14);
				$pdf->MultiCell(70, 6, $_POST['nombre-asistente'], 0, 'C', false);

	//        TIPO DE ASISTENTE
				$pdf->SetXY(65, 105);
				$pdf->SetFillColor(15, 117, 188);
				$pdf->SetFontSize(16);
				$pdf->SetTextColor(255, 255, 255);
				$tipo = $_POST['tipo-asistente'];
				if ($tipo == 'general') {
					$tipo = 'publico general';
				}
				$pdf->MultiCell(70, 10, utf8_decode(strtoupper($tipo)), 0, 'C', TRUE);
					
				$pdf->SetFontSize(12);
				$pdf->SetTextColor(0, 0, 0);

				$pdf->SetFillColor(217, 217, 217);
				$pdf->Circle(101, 15, 3);
				//DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
				
				$pdf->Output('I','gafete.pdf');
			}		
		}
		catch(Exception  $ex){
			echo $ex->getMessage();
		}	

    }
   

}
