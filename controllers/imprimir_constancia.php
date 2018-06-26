<?php

require_once "libs/pdf/fpdf.php";

class imprimir_constancia extends Controller{

    public function __construct() {
        parent::__construct();
        Session::init();
        $logged=Session::get('sesion');
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'views/imprimir_constancia/css/imprimir_constancia.css',
			'public/plugins/datatable/jquery.datatables.min.css',
			'views/imprimir_constancia/css/menu.css'
        );
				
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'views/imprimir_constancia/js/imprimir_constancia.js',
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
        $this->view->render('imprimir_constancia/index');
    }


    function generarConstancia() {
		   	//if (empty($_POST['nombre-articulo']) || empty($_POST['nombre-articulo'])) {
    	    $nombreArticulo = $_POST['nombre-articulo'];
		   	$nombreAutor1 = $_POST['nombre-autor_1'];
		   	$nombreAutor2 = $_POST['nombre-autor_2'];
		   	$nombreAutor3 = $_POST['nombre-autor_3'];
		   	$nombreAutor4 = $_POST['nombre-autor_4'];
		   	// Instanciation of inherited class
		   	$pdf = new PDF("P","mm","Letter");
		   	#Establecemos los márgenes izquierda, arriba y derecha:
		   	$pdf->SetMargins(10, 10 , 10);
		   	#Establecemos el margen inferior:
		   	$pdf->SetAutoPageBreak(true,15);
		   	$pdf->AddPage();
		   	//COLOCA EL PRIMER AUTOR
		   	$pdf->SetFontSize(20);
		   	$fila=118;
		   	//AUTOR 1
		   	$pdf->SetXY(10,$fila);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor1= iconv('UTF-8', 'windows-1252', $nombreAutor1);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor1)),0,1,'C');
		   	//AUTOR 2
		   	$pdf->SetXY(10,$fila+7);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor2= iconv('UTF-8', 'windows-1252', $nombreAutor2);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor2)),0,1,'C');
		   	//AUTOR NAME 3
		   	$pdf->SetXY(10,$fila+14);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor3= iconv('UTF-8', 'windows-1252', $nombreAutor3);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor3)),0,1,'C');
		   	//AUTOR NAME 4
		   	$pdf->SetXY(10,$fila+21);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor4= iconv('UTF-8', 'windows-1252', $nombreAutor4);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor4)),0,1,'C');
		   	//ARTICLE NAME
		   	//$nombreArticulo= iconv('UTF-8', 'windows-1252', $nombreArticulo);
		   	$nombreArticulo = utf8_decode(mb_strtoupper($nombreArticulo));
		   	$pdf->SetFontSize(11);
		   	//SE COLOCA EN POSICION PARA EL NOMBRE DE LA PONENCIA
			$row_art=154;   
			if(strlen($nombreArticulo)>115){
		   		$arrayArt = explode(" ",$nombreArticulo);
		   		$total_articulo="";
		   		
		   		for($i=0;$i<count($arrayArt);$i++){
		   			$total_articulo .=$arrayArt[$i]." ";
		   			if(strlen($total_articulo)>=115){
		   				$pdf->SetXY(33,$row_art); //160
		   				$pdf->Cell(10,10,$total_articulo,0,1);
		   				$total_articulo="";
		   				$row_art+=4;
		   			}
		   		}
		   		if(strlen($total_articulo)>=1){
		   			$pdf->SetXY(33,$row_art);//154
		   			$pdf->Cell(10,10,$total_articulo,0,1);
		   		}
		   	
		   	}else {
		   		$pdf->SetXY(33,$row_art);//152
		   		$pdf->Cell(10,10,$nombreArticulo,0,1);
		   	}
		   	//DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
		   	$pdf->Output('Const_Cica2018.pdf','D');
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