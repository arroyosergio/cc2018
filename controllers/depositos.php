<?php

class Depositos extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get('sesion');
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            
            //'public/plugins/toastr/toastr.min.css',
            //'views/dashboard/css/custom.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/depositos/css/depositos.css',
            'views/depositos/css/menu.css'
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            
            'public/plugins/datatable/jquery.datatables.min.js',
            "views/depositos/js/depositos.js",
        );

        $role = Session::get('perfil');
        if ($logged == false) {
            Session::destroy();
            header('location:index');
            exit;
        } elseif ($role != 'administrador' && $role != 'contabilidad') {
            header('location:index');
        }
    }

    function index() {
        $this->view->tblDepositos = $this->getDepositos();
        $this->view->render('depositos/index');
    }

    function getDepositos() {
        $responseDB = $this->model->get_depositos();
        $response='';
		if (!$responseDB) {
            $response .= '<h1 class="text-center">No hay dep&oacute;sitos registrados.</h1>';
        } else {
            $response = '<table id="listaDepositos" class="table table-hover dataTable">';
            $response .= '<thead>';
            $response .= '<tr>';
            $response .= '<th>Id</th>';
            $response .= '<th>Art&iacute;culo</th>';
            $response .= '<th>Tipo</th>';
            $response .= '<th></th>';
            $response .= '<th class="text-center">Factura enviada</th>';
            $response .= '<th class="text-center">Validaci&oacute;n pago</th>';
            $response .= '<th class="text-center">Cambios asistencia</th>';
            $response .= '</tr>';
            $response .= '</thead>';
            $response .= '<tbody>';
            foreach ($responseDB as $deposito) {
                $response .= '<td>' . $deposito['artId'] . '</td>';
                $response .= '<td>' . $deposito['artNombre'] . '</td>';
                $response .= '<td>' . $deposito['artTipo'] . '</td>';
                $response .= '<td><p class="btn btn-link detalles" deposito="' . $deposito['artId'] . '"><span class="glyphicon glyphicon-eye-open"></span> Detalles<p></td>';
                if ($deposito['art_factura_enviada'] == 'si') {
                    $response .= '<td class="text-center"><input class="facturacion" deposito="' . $deposito['artId'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="facturacion" deposito="' . $deposito['artId'] . '" type="checkbox" name=""></td>';
                }
                if ($deposito['doc_validar_pago'] == 'si') {
                    $response .= '<td class="text-center"><input class="validacion-deposito" deposito="' . $deposito['artId'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="validacion-deposito" deposito="' . $deposito['artId'] . '" type="checkbox" name=""></td>';
                }
                if ($deposito['art_cambios_asistencia'] == 'si') {
                    $response .= '<td class="text-center"><input class="cambios" deposito="' . $deposito['artId'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="cambios" deposito="' . $deposito['artId'] . '" type="checkbox" name=""></td>';
                }
                $response .= '</tr>';
            }
            $response .= '</tbody>';
            $response .= '</table>';
        }
        return $response;
    }

    function getDatosDeposito() {
        $idArticulo = $_POST['id'];
        $reponse = '';
        if (!empty($idArticulo)) {
            $responseDB = $this->model->get_datos_deposito($idArticulo);
            if (!$responseDB) {
                $response = 'false';
            } else {
                $comprobante = $this->model->existe_doc_pago($idArticulo);
                
                $deposito = array(
                    "id" => $responseDB['dep_id'],
                    "banco" => $responseDB['dep_banco'],
                	"sucursal" => $responseDB['dep_sucursal'],
                	"transaccion" => $responseDB['dep_transaccion'],
                	"hr" => $responseDB['dep_hora'],
                    "tipo" => $responseDB['dep_tipo'],
                    "info" => $responseDB['dep_info'],
                    "monto" => $responseDB['dep_monto'],
                    "fecha" => $responseDB['dep_fecha'],
                    "comprobante" => 'docs/' . $comprobante['doc_pago']
                );
                $response = $deposito;
            }
        }
        echo json_encode($response);
    }

    function getDatosFacturacion() {
        $idArticulo = $_POST['id'];
        $response = '';
        if (!empty($idArticulo)) {
            $responseDB = $this->model->get_datos_facturacion($idArticulo);
            if (!$responseDB) {
                $response = 'false';
            } else {
                $facturacion = array(
                    'razonSocial' => $responseDB['fac_razon_social'],
                    'correo' => $responseDB['fac_correo'],
                    'rfc' => $responseDB['fac_rfc'],
                    'calle' => $responseDB['fac_calle'],
                    'numero' => $responseDB['fac_numero'],
                    'colonia' => $responseDB['fac_colonia'],
                    'municipio' => $responseDB['fac_municipio'],
                    'estado' => $responseDB['fac_estado'],
                    'cp' => $responseDB['fac_cp']
                );
                $response = $facturacion;
            }
        }
        echo json_encode($response);
    }

    function updateEstatusCambios() {
        $idArticulo = $_POST['id'];
        $estatus = $_POST['estatus'];
        $response = '';
        if (!empty($idArticulo) && !empty($estatus)) {
            if ($estatus == 'true') {
                $estatus = 'si';
            } else {
                $estatus = 'no';
            }
            $responseDB = $this->model->update_estatus_cambios($idArticulo, $estatus);
            if (!$responseDB) {
                $response = 'false';
            } else {
                $response = 'true';
            }
        } else {
            error_log('updateEstatusCambios: uno de los parametros se envia nulo');
        }
        echo $response;
    }

    function enviarCorreo() {
        $idArticulo = $_POST['id-deposito'];
        $comentarios = $_POST['comentarios'];
        
        if (!empty($idArticulo) && !empty($comentarios)) {
            $correoContacto = $this->model->get_correo_contacto($idArticulo);
            
            $asunto="Solicitud de cambios del artículo";
    		$mensaje="<h1>Estimado autor:</h1><h2>".$comentarios."</h2>".
    					"<h3>atte.<br /> Comit&eacute; Organizador CICA 2018.<br />UTSOE</h3>";  
    		$mensaje1="Estimado autor:".$comentarios."<br/>".
    					"atte. Comit&eacute; Organizador CICA 2018. UTSOE";
            
            $enviado=$this->fncSendMail($correoContacto,$asunto,$mensaje,$mensaje1);
            echo $enviado;
        } else {
            echo 'error-null';
        }
    }

//    Actualiza si el deposito ha sido correcto o no 
    function updateEstatusDeposito() {
        $idAriculo = $_POST['id'];
        $estatusDeposito = $_POST['estatus'];
        $response = '';
        if (!empty($idAriculo) || !empty($estatusDeposito)) {
            if ($estatusDeposito == 'true') {
                $estatusDeposito = 'si';
            } else {
                $estatusDeposito = 'no';
            }
            $responseDB = $this->model->update_estatus_deposito($idAriculo, $estatusDeposito);
            if (!$responseDB) {
//                error en la consulta
                $response = 'false';
            } else {
                $response = 'true';
            }
        } else {
            error_log('updateEstatusDeposito: error no se reciben todos los valores');
        }
        echo $response;
    }
    
//    Actualiza si la factura se ha enviado o no
    function updateEstatusFacturacion() {
        $idAriculo = $_POST['id'];
        $estatusDeposito = $_POST['estatus'];
        $response = '';
        if (!empty($idAriculo) || !empty($estatusDeposito)) {
            if ($estatusDeposito == 'true') {
                $estatusDeposito = 'si';
            } else {
                $estatusDeposito = 'no';
            }
            $responseDB = $this->model->update_estatus_facturacion($idAriculo, $estatusDeposito);
            if (!$responseDB) {
//                error en la consulta
                $response = 'false';
            } else {
                $response = 'true';
            }
        } else {
            error_log('updateEstatusDeposito: error no se reciben todos los valores');
        }
        echo $response;
    }

    function fncExportaExcell() {
        $dsDatosRegistro = $this->model->fncGetRegistroExportar();
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
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
                ->setCellValue('A1', 'ARTICULO')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'MODALIDAD')
                ->setCellValue('D1', 'ASISTENTE')
                ->setCellValue('E1', 'INSTITUCION')
                ->setCellValue('F1', 'TIPO DE ASISTENTE')
                ->setCellValue('G1', 'RAZON SOCIAL')
                ->setCellValue('H1', 'RFC')
                ->setCellValue('I1', 'CORREO')
                ->setCellValue('J1', 'CALLE Y NUMERO')
                ->setCellValue('K1', 'COLONIA')
                ->setCellValue('L1', 'MUNICIPIO')
                ->setCellValue('M1', 'ESTADO')
                ->setCellValue('N1', 'CODIGO POSTAL')
                ->setCellValue('O1', 'BANCO')
                ->setCellValue('P1', 'SUCURSAL')
                ->setCellValue('Q1', 'TRANSACCION')
                ->setCellValue('R1', 'TIPO DEPOSITO')
                ->setCellValue('S1', 'INFORMACION DEL DEPOSITO')
                ->setCellValue('T1', 'FECHA DEPOSITO')
                ->setCellValue('U1', 'HR DEPOSITO')
                ->setCellValue('V1', 'PAGO TOTAL')
                ->setCellValue('W1', 'FACT. ENVIADA')
                ->setCellValue('X1', 'FECHA DEL REGISTRO');
        //DECLARACION DE FORMATO
        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'EE932C'),
                'size' => 15,
                'name' => 'Verdana',
                'wrap' => true,),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFCC99')
        ));
        //APLICA EL FORMATO AL RANGO DE CELDAS
        $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //ARTICULO
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50); //NOMBRE
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50); //MODALIDAD
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50); //ASISTENTE
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50); //INSTITUCION
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50); //TIPO DE ASISTENTE
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50); //RAZON SOCIAL
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50); //RFC
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50); //CORREO
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(50); //CALLE Y NUMERO 
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(50); //COLONIA
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(50); //MUNICIPIO
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50); //ESTADO
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20); //CODIGO POSTAL
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30); //BANCO
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30); //SUCURSAL
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30); //TRANSACCION
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30); //TIPO DEPOSITO
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30); //INFORMACION DE DEPOSITO
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30); //FECHA DEPOSITO
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30); //HR DEPOSITO
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(30); //PAGO TOTAL
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(30); //FACT. ENVIADA
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(30); //FECHA REGISTRO
        //GET THE CLIENTS RECORDS
        if (is_array($dsDatosRegistro)) {
            $fila = 2;
            foreach ($dsDatosRegistro as $registro) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $registro["articulo"]);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, $registro["nombre"]);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $registro["modalidad"]);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, $registro["asistente"]);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $registro["institucion"]);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, $registro["tipo_asist"]);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, $registro["razonsocial"]);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, $registro["rfc"]);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, $registro["correo"]);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, $registro["calle"]);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, $registro["colonia"]);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, $registro["municipio"]);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, $registro["estado"]);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, $registro["codpos"]);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, $registro["banco"]);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, $registro["sucursal"]);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, $registro["transaccion"]);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $fila, $registro["tipo_deposito"]);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $fila, $registro["informacion_dep"]);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $fila, $registro["fecha_dep"]);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $fila, $registro["hr_dep"]);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $fila, $registro["monto_pago"]);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $fila, $registro["fact_enviada"]);
                $objPHPExcel->getActiveSheet()->setCellValue('x' . $fila, $registro["fecha_registro"]);
                $fila++;
            }
        }
        $file = "RegistroCica.xlsx"; //file location
        //TIPO DE ARCHIVO QUE SE DESCARGARA
        header('Content-Type: application/vnd.ms-excel');
        //proporcionar un nombre de fichero recomendado y forzar al navegador el mostarar el diÃ¡logo para guardar el fichero.
        header('Content-Disposition: attachment; filename="' . $file . '"');
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('RELACION DE REGISTRO');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Save Excel 2007 file
        $callStartTime = microtime(true);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('xls/' . $file);
        //readfile($_SERVER['DOCUMENT_ROOT'] . '/xls/' . $file);
    }
    
    
    function fncSendMail($correo,$asunto,$mensaje,$mensajeSinF){
    	try {
			//Apartado para enviar correo
			$this->mail->SetFrom("administracion@higo-software.com", "CICA2018");
			$this->mail->FronName="Cica2018";
			$this->mail->addAddress($correo);
			$this->mail->addCC('contacto@cica2017.org');
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
    		$enviado="Correo-bad";;
    	}
    	return $enviado;
    }

}
