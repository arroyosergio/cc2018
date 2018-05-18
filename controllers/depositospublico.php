<?php

class Depositospublico extends Controller {

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
            'views/depositospublico/css/depositospub.css',
            'views/depositospublico/css/menu.css'
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            
            'public/plugins/datatable/jquery.datatables.min.js',
            "views/depositospublico/js/depositospub.js",
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
        $this->view->tblDepositospublico = $this->getDepositos();
        $this->view->render('depositospublico/index');
    }

    function getDepositos() {
        $responseDB = $this->model->get_depositos();
		$response='';
        if (!$responseDB) {
            $response .= '<h1 class="text-center">No hay dep&oacute;sitos registrados.</h1>';
        } else {
            $response = '<table id="tbl-depositos" class="table table-hover dataTable">';
            $response .= '<thead>';
            $response .= '<tr>';
            $response .= '<th>Registro</th>';
            $response .= '<th>Banco</th>';
            $response .= '<th>Tipo</th>';
            $response .= '<th>Fecha Deposito</th>';
            $response .= '<th>Monto</th>';
            $response .= '<th></th>';
            $response .= '<th class="text-center">Factura enviada</th>';
            $response .= '<th class="text-center">Validaci&oacute;n pago</th>';
            $response .= '<th class="text-center">Enviar correo</th>';
            $response .= '</tr>';
            $response .= '</thead>';
            $response .= '<tbody>';
            foreach ($responseDB as $deposito) {
                $response .= '<td>' . $deposito['id'] . '</td>';
                $response .= '<td>' . $deposito['banco'] . '</td>';
                $response .= '<td>' . $deposito['tipo'] . '</td>';
                $response .= '<td>' . $deposito['fecha'] . '</td>';
                $response .= '<td>' . number_format($deposito['monto'], 2, '.', ',') . '</td>';
                $response .= '<td><p class="btn btn-link detalles" deposito="' . $deposito['id'] . '"><span class="glyphicon glyphicon-eye-open"></span> Detalles<p></td>';
                if ($deposito['enviada'] == 'si') {
                    $response .= '<td class="text-center"><input class="facturacion" deposito="' . $deposito['id'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="facturacion" deposito="' . $deposito['id'] . '" type="checkbox" name=""></td>';
                }
                if ($deposito['validar'] == 'si') {
                    $response .= '<td class="text-center"><input class="validacion-deposito" deposito="' . $deposito['id'] . '" type="checkbox" name="" checked></td>';
                } else {
                    $response .= '<td class="text-center"><input class="validacion-deposito" deposito="' . $deposito['id'] . '" type="checkbox" name=""></td>';
                }
                $response .= '<td class="text-center"><p class="btn btn-link email" deposito="' . $deposito['id'] . '"><span class="glyphicon  glyphicon-envelope"></span> Correo<p></td>';
                $response .= '</tr>';
                
            }
            $response .= '</tbody>';
            $response .= '</table>';
        }
        return $response;
    }

    function getDatosDeposito() {
        $reg_id = $_POST['id'];
        $reponse = '';
        if (!empty($reg_id)) {
            $responseDB = $this->model->get_datos_deposito($reg_id);
            if (!$responseDB) {
//                    Error no hay registro del deposito
                $response = 'false';
            } else {
                $deposito = array(
                    "id" => $responseDB['dep_id'],
                    "banco" => $responseDB['dep_banco'],
                	"sucursal" => $responseDB['dep_sucursal'],
                	"transaccion" => $responseDB['dep_transaccion'],
                	"tipo" => $responseDB['dep_tipo'],
                    "info" => $responseDB['dep_info'],
                    "monto" => number_format($responseDB['dep_monto'], 2, '.', ','),
                    "fecha" => $responseDB['fecha'],
                	"hr" => $responseDB['dep_hora'],                		
                    "comprobante" => 'docs/dep_publico/' . $responseDB['dep_comprobante']
                );
                $response = $deposito;
            }
        }
        echo json_encode($response);
    }

    function getDatosFacturacion() {
        $regId = $_POST['id'];
        $response = '';
        if (!empty($regId)) {
            $responseDB = $this->model->get_datos_facturacion($regId);
            if (!$responseDB) {
//                    Error no hay datos de facturación
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


    function enviarCorreo() {
        $regid = $_POST['id-deposito'];
        $comentarios = $_POST['comentarios'];
        if (!empty($regid) && !empty($comentarios)) {
            $correoContacto = $this->model->get_correo_contacto($regid);
            
            $asunto= html_entity_decode('Formato de asistencia CICA 2016 recibido.');
            $mensaje = $comentarios;
            $mensaje1 = $comentarios;
            
            $enviado=$this->fncSendMail($correoContacto,$asunto,$mensaje,$mensaje1);
            
            echo $enviado;
        } else {
            echo 'error-null';
        }
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

//    Actualiza si el deposito ha sido correcto o no 
    function updateEstatusDeposito() {
        $reg_id = $_POST['id'];
        $estatusDeposito = $_POST['estatus'];
        $response = '';
        if (!empty($reg_id) || !empty($estatusDeposito)) {
            if ($estatusDeposito == 'true') {
                $estatusDeposito = 'si';
            } else {
                $estatusDeposito = 'no';
            }
            $responseDB = $this->model->update_estatus_deposito($reg_id, $estatusDeposito);
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
        $reg_id = $_POST['id'];
        $estatusDeposito = $_POST['estatus'];
        $response = '';
        if (!empty($reg_id) || !empty($estatusDeposito)) {
            if ($estatusDeposito == 'true') {
                $estatusDeposito = 'si';
            } else {
                $estatusDeposito = 'no';
            }
            $responseDB = $this->model->update_estatus_facturacion($reg_id, $estatusDeposito);
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
    	try {
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
    		->setCellValue('A1', 'REGISTRO')
    		->setCellValue('B1', 'NOMBRE')
    		->setCellValue('C1', 'TIPO DE ASISTENTE')
    		->setCellValue('D1', 'BANCO')
    		->setCellValue('E1', 'SUCURSAL')
    		->setCellValue('F1', 'TRANSACCION')
    		->setCellValue('G1', 'MOVIMIENTO')
    		->setCellValue('H1', 'INFORMACION DEPOSITO')
    		->setCellValue('I1', 'FECHA DEPOSITO')
    		->setCellValue('J1', 'HORA DEPOSITO')
    		->setCellValue('K1', 'MONTO')
    		->setCellValue('L1', 'RAZON SOCIAL')
    		->setCellValue('M1', 'RFC')
    		->setCellValue('N1', 'CALLE Y NUMERO')
    		->setCellValue('O1', 'COLONIA')
    		->setCellValue('P1', 'MUNICIPIO')
    		->setCellValue('Q1', 'ESTADO')
    		->setCellValue('R1', 'COD. POSTAL')
    		->setCellValue('S1', 'CORREO')
    		->setCellValue('T1', 'DEP. VALIDADO')
    		->setCellValue('U1', 'FACT. ENVIADA');
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
    		$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->applyFromArray($styleArray);
    		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //REGISTRO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50); //NOMBRE
    		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50); //ASISTENTE
    		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50); //BANCO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50); //SUCURSAL
    		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50); //TRANSACCION
    		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50); //MOVIMIENTO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50); //INFORMACION DE DEPOSITO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50); //FECHA
    		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(50); //HORA
    		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(50); //MONTO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(50); //RAZON SOCIAL
    		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(50); //RFC
    		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(50); //CALLE Y NUMERO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50); //COLONIA
    		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(50); //MUNICIPIO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20); //ESTADO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30); //CP
    		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30); //CORREO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30); //CORREO VALIDADO
    		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30); //FACTURA ENVIADA
    		//GET THE CLIENTS RECORDS
    		if (is_array($dsDatosRegistro)) {
    			$fila = 2;
    			foreach ($dsDatosRegistro as $registro) {
    				$objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $registro["id"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, $registro["nombre"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $registro["tipo"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, $registro["banco"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $registro["sucursal"]);    				
    				$objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, $registro["transaccion"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, $registro["tipo_deposito"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, $registro["informacion"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, $registro["fechadeposito"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, $registro["hrdeposito"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, $registro["monto"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('L' . $fila, $registro["razon_social"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('M' . $fila, $registro["rfc"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('N' . $fila, $registro["calle"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('O' . $fila, $registro["colonia"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('P' . $fila, $registro["municipio"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('Q' . $fila, $registro["estado"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('R' . $fila, $registro["cp"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('S' . $fila, $registro["correo"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('T' . $fila, $registro["validar"]);
    				$objPHPExcel->getActiveSheet()->setCellValue('U' . $fila, $registro["enviada"]);
    				$fila++;
    			}
            }
            $file = "RegPubCica.xlsx"; //file location
            //TIPO DE ARCHIVO QUE SE DESCARGARA
            header('Content-Type: application/vnd.ms-excel');
            //proporcionar un nombre de fichero recomendado y forzar al navegador el mostarar el diÃ¡logo para guardar el fichero.
            header('Content-Disposition: attachment; filename="' . $file . '"');
    		// Rename worksheet
    		$objPHPExcel->getActiveSheet()->setTitle('REGISTROS DE PUBLICO');
    		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    		$objPHPExcel->setActiveSheetIndex(0);
    		// Save Excel 2007 file
    		$callStartTime = microtime(true);
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    		
    		$objWriter->save($_SERVER['DOCUMENT_ROOT'] . '/xls/' . $file);

    	} catch (Exception $e) {
    		error_log($e);
   		
    	}

    }

}
