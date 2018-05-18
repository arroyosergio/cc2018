<?php

require_once "libs/pdf/fpdf.php";

class dashboard extends Controller{

    public function __construct() {
        parent::__construct();
        Session::init();
        $logged=Session::get('sesion');
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'views/dashboard/css/dashboard.css',
			//'views/dashboard/css/perfil-menu.css',
			'public/plugins/datatable/jquery.datatables.min.css',
			'views/dashboard/css/menu.css'
        );
				
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'views/dashboard/js/dashboard.js',
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
        $this->view->listaTrabajos=$this->model->fncGetTrabajos();
        $this->view->render('dashboard/index');
    }

    function fncGetVerArticulos(){
    	$ruta= URL.'docs/';//$_SERVER['DOCUMENT_ROOT'] .'/controlCongresos/docs/';
    	$responseDB = $this->model->fncGetVerArticulos($_POST['ID']);
    	$versiones = "<div>";
    	
    	if (!$responseDB) {
    		$versiones.="<div>No exiten art&iacute;culos</div>";
    	}else{ 
    		foreach ($responseDB as $articulo) {
    			$versiones.="<div class='row'><div class='col-md-1'><span class='glyphicon glyphicon-floppy-save'></div>".
    					     "<div class='col-md-8'><a href='".$ruta.$articulo['archivo']."'><b>". strtoupper($articulo['archivo'])."</b></a></div></div>";
    		}
    	}
    	$versiones.="</div>";
    	echo ($versiones);
    }    		 
    	
   //OBTIENE LOS AUTORES CORRESPONDIENTES AL ID DEL ARTICULO SOLICITADO
    function fncGetDetTrabajoAutores() {
        $responseDB = $this->model->fncGetDetTrabajoAutores($_POST['idArticulo']);
        $tabla = '';
        if (!$responseDB) {
            $tabla = '<h2 class="text-center">No existen Autores para este Art&iacute;culo</h2>';
        } else {
            $tabla='<table class="table table-hover table-responsive">'.
                    '<thead>'.
                    '<tr>'.
                    '<th class="col-sm-3">ID</th>'.
                    '<th class="col-sm-5">AUTOR</th>'.
                    '<th class="col-sm-5">INSTITUCION</th>'.
                    '<th class="col-sm-5">CIUDAD</th>'. 
                    '<th class="col-sm-5">ESTADO</th>'.
                    '<th class="col-sm-5">PAIS</th>'.
                    '<th class="col-sm-5">CORREO</th>'.
                    '<th class="col-sm-5">GRADO ACADEMICO</th>'.
                    '<th class="col-sm-5">TIPO</th>'.
                    '<th class="col-sm-5">ASISTENCIA CICA</th>'.
                    '<th class="col-sm-5">CONTACTO</th>'.
                    '</tr>'.
                    '</thead>'.
                    '<tbody>';
                    foreach ($responseDB as $autor) {
                        $tabla .='<tr>';
                        $tabla .='<td>'.strtoupper($autor['autId']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autNombre'].' '.$autor['autApellidoPaterno'].' '.$autor['autApellidoMaterno']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autInstitucionProcedencia']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autCiudad']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autEstado']).'</td>';
                        $tabla .='<td>'.strtoupper(utf8_encode($autor['autPais'])).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autCorreo']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autGradoAcademico']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autTipoInstitucion']).'</td>';
                        $tabla .='<td>'.strtoupper($autor['autAsistenciaCica']).'</td>';
                        $tabla .='<td class="text-center" ><input type="checkbox" name="Avisocambio" '.($autor["autContacto"]=='si'?'checked':'').' disabled />';
                        //$tabla .='<td>'.strtoupper($autor['autContacto']).'</td>';
                        $tabla .='</tr>';
                    }
                    $tabla .='</tbody>';
                    $tabla .='</table>';
        }
        echo $tabla;
    }

    function fncUpdateTrabajo(){
        //VALIDA SI EL CAMPO DE RECIBIDO ESTA CHECADO y ENVIA CORREO AL USUARIO
        $enviado="Correo-no";
        $this->model->fncUpdateTrabajo($_POST['id'],$_POST['campo'],$_POST['estado']);  
        if($_POST['campo']=='artRecibido' && $_POST['estado']=='si'){
            $responseDB=$this->model->fncGetCorreoAutor($_POST['id']);
            if($responseDB[0]['CORREO']!=NULL){
            	$mensaje="<h1>Estimado autor:</h1><h2>Su trabajo fue recibido correctamente registrado con el Id No. ".$_POST['id'].", Espere pr&oacute;ximamente el dictamen del comit&eacute; arbitral</h2>".
                       "<h3>atte.<br /> Comit&eacute; Organizador CICA 2018.<br />UTSOE</h3>";
            	$mensaje1="Estimado autor:Su trabajo fue recibido correctamente registrado con el Id No. ".$_POST['id'].", Espere pr&oacute;ximamente el dictamen del comit&eacute; arbitral".
            			"atte. Comit&eacute; Organizador CICA 2018.  UTSOE";
            	$asunto="Recibimos artículo";
                $enviado=$this->fncSendMail($responseDB[0]['CORREO'],$asunto,$mensaje,$mensaje1);
            }
        }
        echo $enviado;
    }

    function fncSendComentario(){
    	//VALIDA SI EL CAMPO DE RECIBIDO ESTA CHECADO y ENVIA CORREO AL USUARIO
    	$idArticulo=$_POST['idArticulo'];
    	$campo=$_POST['campo'];
    	$comentario=$_POST['comentario1'];
    	$enviado="Correo-no";
    	if($campo=='Dictaminado'){
    		$responseDB=$this->model->fncGetCorreoAutor($idArticulo);
    		if($responseDB[0]['CORREO']!=NULL){
    			$asunto="Dictamen del artículo";
    			$mensaje="<h1>Estimado autor:</h1><h2>".$comentario."</h2>".
    					"<h3>atte.<br /> Comit&eacute; Organizador CICA 2018.<br />UTSOE</h3>";
    			$mensaje1="Estimado autor:".$comentario.
    					"atte. Comit&eacute; Organizador CICA 2018.   UTSOE";
    			$enviado=$this->fncSendMail($responseDB[0]['CORREO'],$asunto,$mensaje,$mensaje1);
    		}
    	}else if($campo=='AvisoCambio'){
    		$responseDB=$this->model->fncGetCorreoAutor($idArticulo);
    		if($responseDB[0]['CORREO']!=NULL){
    			$asunto="Solicitud de cambios del artículo";
    			$mensaje="<h1>Estimado autor:</h1><h2>".$comentario."</h2>".
    					"<h3>atte.<br /> Comit&eacute; Organizador CICA 2018.<br />UTSOE</h3>";  
    			$mensaje1="Estimado autor:".$comentario."<br/>".
    					"atte. Comit&eacute; Organizador CICA 2018. UTSOE";
    			$enviado=$this->fncSendMail($responseDB[0]['CORREO'],$asunto,$mensaje,$mensaje1);
    		}
    	}
    	echo $enviado;
    }    
    
    function fncSendMail($correo,$asunto,$mensaje,$mensajeSinF){
    	try {
			//Apartado para enviar correo
			$this->mail->SetFrom(USER_SMTP, ORGANIZACION_MAIL);
			$this->mail->FronName= ORGANIZACION_MAIL;
			$this->mail->addAddress($correo);
			$this->mail->isHTML(TRUE);
			$this->mail->Subject = utf8_decode($asunto);
			$this->mail->Body = $mensaje;
			$this->mail->AltBody ='Problemas de compatibilidad con el navegador'; 
			$this->mail->CharSet="UTF-8";	
			$enviado="Correo-ok";
			if (!$this->mail->send()) {
				error_log("Error al enviar el correo a $correo:" . $this->mail->ErrorInfo);
				$enviado="Correo-bad";
			}
    	} catch (Exception $e) {
    		$enviado="Correo-bad";
    	}
    	return $enviado;
    }

    //OBTIENE LOS DATOS DETALLADOS DEL ARTICULO CORRESPONDIENTES AL ID DEL ARTICULO SOLICITADO
    function fncGetDetTrabajo(){
        $idArt=$_POST['id'];
        $responseDB = $this->model->fncgetDetTrabajo($idArt);
        $tabla = '';
        if (!$responseDB) {
            $tabla = '<h2 class="text-center">No existe este numero de articulo</h2>';
        } else {
            $tabla = '<form class="">';
            $tabla .= '<div class="row">';
            $tabla .= '<div class="col-sm-3">';
            $tabla .= '<div class="form-group">';
            $tabla .= '<label for="">Id:</label>';
            $tabla .= '<p class="form-control-static">'.$responseDB[0]['artId'].'</p>';
            $tabla .= '</div>';
            $tabla .= '</div>';
            $tabla .= '<div class="col-sm-9">';
            $tabla .= '<div class="form-group">';
            $tabla .= '<label for="">Nombre:</label>';
            $tabla .= '<p class="form-control-static">'.$responseDB[0]['artNombre'].'</p>';
            $tabla .= '</div>';
            $tabla .= '</div>';
            $tabla .= '</div>';
            $tabla .= '<div class="row">';
            $tabla .= '<div class="col-sm-3">';
            $tabla .= '<div class="form-group">';
            $tabla .= '<label for="">Tipo:</label>';
            $tabla .= '<p class="form-control-static">'.strtoupper($responseDB[0]['artTipo']).'</p>';
            $tabla .= '</div>';
            $tabla .= '</div>';
            $tabla .= '<div class="col-sm-9">';
            $tabla .= '<div class="form-group">';
            $tabla .= '<label for="">&Aacute;rea tematica:</label>';
            switch ($responseDB[0]['artAreaTematica']) {
                case 'CAYS':
                    $responseDB[0]['artAreaTematica'] = 'CIENCIAS ADMINISTRATIVAS Y SOCIALES';
                    break;
                case 'EFC':
                    $responseDB[0]['artAreaTematica'] = 'EXPERIENCIA EN FORMACI&oacute;N CA';
                    break;
                case 'CA':
                    $responseDB[0]['artAreaTematica'] = 'CIENCIAS AGROPECUARIAS';
                    break;
                case 'CNYE':
                    $responseDB[0]['artAreaTematica'] = 'CIENCIAS NATURALES Y EXACTAS';
                    break;
                case 'CIYT':
                    $responseDB[0]['artAreaTematica'] = 'CIENCIAS DE INGENIER&iacute;A Y TECNOLOG&iacute;A';
                    break;
                case 'E':
                    $responseDB[0]['artAreaTematica'] = 'EDUCACI&oacute;N';
                    break;
            }
            $tabla .= '<p class="form-control-static">'.$responseDB[0]['artAreaTematica'].'</p>';
            $tabla .= '</div>';
            $tabla .= '</div>';
            $tabla .= '</div>';
            $tabla .= '</form>';
        }
        echo $tabla;        
    }
    
    function getCartaOriginalidad() {
        $idArticulo = $_POST['id'];
        $response = '<div class="form-group">';
        $response .= '<label for="">';
        $response .= '<label for="">Carta de originalidad:</label>';
        $response .= '<p class="form-control-static">';
        if (!empty($idArticulo)) {
            $cartaOriginalidad = $this->model->get_carta_originalidad($idArticulo);
            if (!$cartaOriginalidad) {
                $response .= 'No hay carta de originalidad';
            }else{
                $response .= '<a href="docs/'.$cartaOriginalidad.'"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>';
            }
        }else{
            error_log('Error no se esta mandando el id del art&iacute;culo : geCartaOriginalidad');
        }
        $response .= '</p>';
        $response .= '</div>';
        echo $response;
    }
        
    function getCartaDerechos() {
        $idArticulo = $_POST['id'];
        $response = '<div class="form-group">';
        $response .= '<label for="">';
        $response .= '<label for="">Carta de seci&oacute;n de derechos:</label>';
        $response .= '<p class="form-control-static">';
//                                <a href="">test</a></p>
        if (!empty($idArticulo)) {
            $cartaDerechos = $this->model->get_carta_derechos($idArticulo);
            if (!$cartaDerechos) {
                $response .= 'No hay carta de seci&oacute;n de derechos';
            }else{
                $response .= '<a href="docs/'.$cartaDerechos.'"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>';
            }
        }else{
            error_log('Error no se esta mandando el id del art&iacute;culo : getCartaDerechos');
        }
        $response .= '</p>';
        $response .= '</div>';
        echo $response;
    }
    
    function getEstatusCartas() {
         $idArticulo = $_POST['id'];
         $responseDB = $this->model->get_estatus_cartas($idArticulo);
         echo $responseDB;
    }
    
    function updateEstatusCartas() {
        $idArticulo = $_POST['id'];
        $estatus= $_POST['estatus'];
        $responseDB = $this->model->update_estatus_cartas($idArticulo, $estatus);
        $response = 'false';
        if ($responseDB) {
            $response = 'true';
            if ($estatus == 'si') {
               $asunto = "Validaci&oacute;n de cartas de originalidad y seci&oacute;n de derechos";
               $mensaje = '<h1>Estimado autor:</h1>'.
                       '<h2>Sus cartas de originalidad y cesi&oacute;n de derechos han sido revisadas y validadas</h2>'.
                       '<h3>atte. Comit&eacute; Organizador CICA 2017.<br />UTSOE</h3>';
               $mensajeSinF = 'Sus cartas de originalidad y cesi&oacute;n de derechos han diso revisadas y validadas';
               $correo = $this->model->fncGetCorreoAutor($idArticulo);
               $correo = $correo[0]['CORREO'];
               $this->fncSendMail($correo, $asunto, $mensaje, $mensajeSinF);
            }
        }
        echo $response;
    }
    function getEstatusRecibo() {
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_estatus_recibo($idArticulo);
          echo $responseDB;
     }
     
     function updateEstatusRecibo() {
          $idArticulo = $_POST['id'];
          $estatus = $_POST['estatus'];
          $responseDB = $this->model->update_estatus_recibo($idArticulo, $estatus);
          $response = 'false';
          if ($responseDB) {
               $response = 'true';
               if ($estatus == 'si') {
                    $asunto = "ValidaciÃ³n de recibo de pago";
                    $mensaje = '<h1>Estimado autor:</h1>' .
                            '<h2>Surecibo de pago ha sido revisado y validado</h2>' .
                            '<h3>atte.<br /> ComitÃ© Organizador CICA 2017.<br />UTSOE</h3>';
                    $mensajeSinF = 'Surecibo de pago ha sido revisado y validado';
                    $correo = $this->model->fncGetCorreoAutor($idArticulo);
                    $correo = $correo[0]['CORREO'];
                    $this->fncSendMail($correo, $asunto, $mensaje, $mensajeSinF);
               }
          }
          echo $response;
     }

     function getReciboPago() {
          $idArticulo = $_POST['id'];
          $response = '<div class="form-group">';
          $response .= '<label for="">';
          $response .= '<label for=""><b>Recibo de pago:</b></label>';
          $response .= '<p class="form-control-static">';
          if (!empty($idArticulo)) {
               $recibo = $this->model->get_recibo_pago($idArticulo);
               if (!$recibo) {
                    $response .= 'No hay recibo de pago.';
               } else {
                    $response .= '<a href="docs/' . $recibo . '"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>';
               }
          } else {
               error_log('Error no se esta mandando el id del artÃ­culo : getReciboPago');
          }
          $response .= '</p>';
          $response .= '</div>';
          echo $response;
     }

     //============================ CARTAS DE ACEPTACION =================================
     function subir_carta_aceptacion() {
     	$response = '';
     	$idArticulo = $_POST['idArticulo'];
     	if (!empty($idArticulo)) {
     		$existeCarta = $this->model->existe_carta_aceptacion($idArticulo);
     		if ($existeCarta != FALSE) {
     			try {
     				if(!empty($existeCarta['doc_carta_aceptacion']))
     				    unlink(DOCS . $existeCarta['doc_carta_aceptacion']);
     			} catch (Exception $exc) {
     				error_log($exc->getTraceAsString());
     			}
     		}
     		$file = $_FILES[0]['name'];
     		$formatoArchivo = explode('.', $file);
     		$formatoArchivo = end($formatoArchivo);
     		if ($formatoArchivo != 'pdf') {
     			echo 'error-formato-archivo';
     		} else {
     			if (!move_uploaded_file($_FILES[0]['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
     				echo 'error-subir-archivo';
     			} else {
     				$this->model->registro_carta_aceptacion($idArticulo, $idArticulo . '/' . $file);
     				echo 'true';
     			}
     		}
     	} else {
     		$response = 'error-null';
     	}
     	echo $response;
     }
     
     function getCartaAceptacion() {
     	$idArticulo = $_POST['id'];
     	$cartaAceptacion = $this->model->existe_carta_aceptacion($idArticulo);
     	$response = '<div class="col-sm-6">';
     	$response .= '<h5 class="text-primary">Carta de aceptaci&oacute;n:</h5>';
     	if(empty( $cartaAceptacion['doc_carta_aceptacion'])){
     		$response .= '<p>No hay ningun documento</p>';
     	}else{
     		if (!$cartaAceptacion) {
     			$response .= '<p>No hay ningun documento</p>';
     		} else {
     			$cartaAceptacion = explode('/', $cartaAceptacion['doc_carta_aceptacion']);
     			$response .= '<p>' . $cartaAceptacion[1] . '</p>';
     		}
     	}
     	$response .= '</div>';
     	echo $response;
     }
     //=================================FIN DE CARTAS DE ACEPTACION=============================

    function fncExportaExcell(){
        try {
            //error_reporting(E_ALL);
            //ini_set('display_errors', TRUE);
            //ini_set('display_startup_errors', TRUE);
            
            /** Include PHPExcel */
            require_once dirname(__FILE__) . '/../libs/PHPExcel.php';
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            $dsArticulos = $this->model->fncGetArtExportar();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("higo-software")
                                            ->setLastModifiedBy("Higo-software")
                                            ->setTitle("Higo-software")
                                            ->setSubject("exportacion a excel")
                                            ->setDescription("exportacion a excel")
                                            ->setKeywords("office PHPExcel php")
                                            ->setCategory("exportacion a excel");
    
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'ARTICULO')
                        ->setCellValue('C1', 'AREA TEMATICA')
                        ->setCellValue('D1', 'TIPO')
                        ->setCellValue('E1', 'DICTAMINADO')
                        ->setCellValue('F1', 'AUTOR')
                        ->setCellValue('G1', 'INSTITUCION PROCEDENCIA')
                        ->setCellValue('H1', 'CIUDAD')
                        ->setCellValue('I1', 'ESTADO')
                        ->setCellValue('J1', 'PAIS')
                        ->setCellValue('K1', 'CORREO')
                        ->setCellValue('L1', 'GRADO ACADEMICO')
                        ->setCellValue('M1', 'TIPO INSTITUCION')
                        ->setCellValue('N1', 'ASISTENCIA CICA')
                        ->setCellValue('O1', 'AUTOR CONTACTO');
    
                        //DECLARACION DE FORMATO
                        $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                                'color' => array('rgb' => 'EE932C'),
                                'size'  => 15,
                                'name'  => 'Verdana',
                                'wrap' => true,),
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FFCC99')
                                ));
                    //APLICA EL FORMATO AL RANGO DE CELDAS 
                    $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);//ID
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);//ARTICULO
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);//AREA TEMATICA
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);//TIPO
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);//DICTAMINADO
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);//AUTOR
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);//INSTITUCION PROCEDENCIA
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);//CIUDAD
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);//ESTADO
                    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);//PAIS
                    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);//CORREO
                    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(50);//GRADO ACADEMICO
                    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50);//TIPO INSTITUCION
                    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);//ASISTENCIA CICA
                    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);//AUTOR CONTACTO
                    //GET THE CLIENTS RECORDS 
                    if (is_array($dsArticulos)){
                        $fila=2;
                        foreach ($dsArticulos as $articulo) {
                            $objPHPExcel->getActiveSheet()->setCellValue('A'.$fila,$articulo["ID"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila,$articulo["ARTICULO"]);
                            if($articulo["AREA"]==="CAYS")
                                $areaTematica="Ciencias administrativas y sociales";
                            if($articulo["AREA"]==="EFC")
                                $areaTematica="Experiencia en formaci&oacute;n CA";
                            if($articulo["AREA"]==="CA")
                                $areaTematica="Ciencias agropecuarias";
                            if($articulo["AREA"]==="CNYE")
                                $areaTematica="Ciencias naturales y exactas";
                            if($articulo["AREA"]==="CIYT")
                                $areaTematica="Ciencias de ingenier&iacute;a y tecnolog&iacute;a";
                            if($articulo["AREA"]==="E")
                                $areaTematica="Educaci&oacute;n";
                            $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila,html_entity_decode($areaTematica));
                            $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila,$articulo["TIPO"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila,$articulo["DICTAMINADO"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila,$articulo["AUTOR"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila,$articulo["PROCEDENCIA"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('H'.$fila,$articulo["CIUDAD"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('I'.$fila,utf8_encode($articulo["ESTADO"]));    
                            $objPHPExcel->getActiveSheet()->setCellValue('J'.$fila,$articulo["PAIS"]);    
                            $objPHPExcel->getActiveSheet()->setCellValue('K'.$fila,$articulo["CORREO"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('L'.$fila,$articulo["GRADOACADEMICO"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('M'.$fila,$articulo["TIPOINSTITUCION"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('N'.$fila,$articulo["ASISTENCIACICA"]);
                            $fila++;
                        }
                    }
                        
                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle('RELACION DE ARTICULOS');
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);                
                $file="ArtCica.xlsx"; //file location 
            
                header('Content-Disposition: attachment; filename="'.$file.'"'); 
            // Redirect output to a client’s web browser (Excel2007)
            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Type: application/vnd.ms-excel');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0
            
            // Save Excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            $objWriter->save($_SERVER['DOCUMENT_ROOT'].'/xls/'.$file);
            //TIPO DE ARCHIVO QUE SE DESCARGARA
            //header('Content-Type: application/xls');
            //proporcionar un nombre de fichero recomendado y forzar al navegador el mostarar el dialogo para guardar el fichero.
            //readfile($_SERVER['DOCUMENT_ROOT'].'/xls/'.$file);
        }catch(Exception  $ex){
            echo $ex->getMessage();
        }
    }
            
    function generarGafete() {
		try{
			if (empty($_POST['id']) || empty($_POST['nombre-articulo']) || empty($_POST['nombre-asistente']) || empty($_POST['tipo-asistente'])) {
				header("location: ../dashboard?status=error-null");
			} else {
				$nombreEvento = "Congreso Interdisciplinario de Cuerpos AcadÃ©micos 2017";
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
				$pdf->EAN13(72, 116, $_POST['id'], 8, .60);

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
		   	$fila=138;
		   	//AUTOR 1
		   	$pdf->SetXY(10,$fila);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor1= iconv('UTF-8', 'windows-1252', $nombreAutor1);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor1)),0,1,'C');
		   	//AUTOR 2
		   	$pdf->SetXY(10,$fila+6);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor2= iconv('UTF-8', 'windows-1252', $nombreAutor2);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor2)),0,1,'C');
		   	//AUTOR NAME 3
		   	$pdf->SetXY(10,$fila+12);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor3= iconv('UTF-8', 'windows-1252', $nombreAutor3);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor3)),0,1,'C');
		   	//AUTOR NAME 4
		   	$pdf->SetXY(10,$fila+18);                                       //SE COLOCA EL CURSOR EN LA POSICION DESEADA
		   	//$nombreAutor4= iconv('UTF-8', 'windows-1252', $nombreAutor4);//HACE LA CONVERSION PARA CARACTERES ESPECIALES
		   	$pdf->Cell(200,10,utf8_decode(mb_strtoupper($nombreAutor4)),0,1,'C');
		   	//ARTICLE NAME
		   	//$nombreArticulo= iconv('UTF-8', 'windows-1252', $nombreArticulo);
		   	$nombreArticulo = utf8_decode(mb_strtoupper($nombreArticulo));
		   	$pdf->SetFontSize(11);
		   	//SE COLOCA EN POSICION PARA EL NOMBRE DE LA PONENCIA
		   	if(strlen($nombreArticulo)>60){
		   		$arrayArt = explode(" ",$nombreArticulo);
		   		$total_articulo="";
		   		$col=166;
		   		for($i=0;$i<count($arrayArt);$i++){
		   			$total_articulo .=$arrayArt[$i]." ";
		   			if(strlen($total_articulo)>=60){
		   				$pdf->SetXY(17,$col); //160
		   				$pdf->Cell(10,10,mb_strtoupper($total_articulo),0,1);
		   				$total_articulo="";
		   				$col+=3;
		   			}
		   		}
		   		if(strlen($total_articulo)>=1){
		   			$pdf->SetXY(17,$col);//154
		   			$pdf->Cell(10,10,mb_strtoupper($total_articulo),0,1);
		   		}
		   	
		   	}else {
		   		$pdf->SetXY(17,168);//152
		   		$pdf->Cell(10,10,$nombreArticulo,0,1);
		   	}
		   	//DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
		   	$pdf->Output('Const_Cica2018.pdf','D');
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