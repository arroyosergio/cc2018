<?php
require_once "libs/pdf/fpdf.php";

class constancia extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get("sesion");
        if (!$logged) {
            Session::destroy();
            header("location: index");
            exit;
        }
    }

    function generar() {
        if (!empty($_GET['id'])) {
       		$responseDB = $this->model->get_autores_articulo($_GET['id']);
        	// Instanciation of inherited class
        	$pdf = new PDF("P","mm","Letter");
        	#Establecemos los márgenes izquierda, arriba y derecha:
        	$pdf->SetMargins(10, 10 , 10);
        	#Establecemos el margen inferior:
        	$pdf->SetAutoPageBreak(true,15);
        	$pdf->AddPage();
        	//COLOCA EL PRIMER AUTOR
        	$pdf->SetFontSize(20);
        	$fila=138;
        	$nombre="";
        	$articulo="";
        	foreach ($responseDB as $autor) {
        		$pdf->SetXY(10,$fila);
        	    $nombre=$autor["autNombre"].' '.$autor['autApellidoPaterno'].' '.$autor['autApellidoMaterno'];
        	    //HACE LA CONVERSION PARA CARACTERES ESPECIALES
        	    $nombre= iconv('UTF-8', 'windows-1252', $nombre);
        	    $articulo= mb_strtoupper($autor["artNombre"]);
        	    $articulo= iconv('UTF-8', 'windows-1252', $articulo);
        	    $pdf->Cell(200,10,mb_strtoupper($nombre),0,1,'C');
        	    //$pdf->Cell(50,30,mb_strtoupper($nombre),0,1);
        	    $fila=$fila+6;
        	}
        	$pdf->SetFontSize(11);
        	//SE COLOCA EN POSICION PARA EL NOMBRE DE LA PONENCIA
        	if(strlen($articulo)>60){
        		$arrayArt = explode(" ", $articulo);
        		$total_articulo="";
        		$col=166;
        		for($i=0;$i<count($arrayArt);$i++){
        			$total_articulo .=$arrayArt[$i]." ";
        			if(strlen($total_articulo)>=60){
        				$pdf->SetXY(17,$col);
        				$pdf->Cell(10,10,mb_strtoupper($total_articulo),0,1);
        				$total_articulo="";
        				$col+=3;
        			}
        		}
        		if(strlen($total_articulo)>=1){
        		   $pdf->SetXY(17,$col);
        		   $pdf->Cell(10,10,mb_strtoupper($total_articulo),0,1);
        	    }
        		
        	}else {
        		$pdf->SetXY(17,168);
        		$pdf->Cell(10,10,mb_strtoupper($articulo),0,1);
        	}
         	//DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
        	$pdf->Output('Cica_art_'.$_GET['id'].'.pdf','D');
        }
    }

    function get_autores_articulo(){
    		$idArticulo=$_POST['id'];
    		if (!empty($idArticulo)) {
    			$responseDB = $this->model->get_autores_articulo($idArticulo);
     		}
    		return  $responseDB;
    }
}

class PDF extends FPDF
{
	// Page header
	function Header()
	{
		// Logo
		$this->Image('public/img/constancia.jpg', 0, 0, $this->w, $this->h);
		$this->AddFont('Sansation', '', 'Sansation_Bold.php');
		$this->SetFont('Sansation', '', 12);

	}
}