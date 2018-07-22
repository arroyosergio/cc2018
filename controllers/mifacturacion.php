<?php

include('libs/CFDI/Node/Comprobante.php');
include('libs/CFDI/Node/Receptor.php');
include('libs/CFDI/Node/Emisor.php');
include('libs/CFDI/Node/Concepto.php');
  
use CFDI\CFDI;
use CFDI\Node\Concepto;
use CFDI\Node\Receptor;
use CFDI\Node\Emisor;

/*
 * Modulo de facturacion del autor.
 */
class mifacturacion extends Controller {
    
    /*
     * Constructor de instancias
     */
    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get('sesion');
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/mifacturacion/css/mifacturacion.css',
            'views/mifacturacion/css/menu.css'
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
            "views/mifacturacion/js/mifacturacion.js",
        );

        $role = Session::get('perfil');
        
        if ($logged == false) {
            Session::destroy();
            header('location:index');
            exit;
        }
    }//Fin __construct
    
    /*
     * Renderiza la pagina.
     */
    public function index() {
        $this->view->tblDepositosValidados = $this->getDepositosValidados();
        $this->view->render('mifacturacion/index');
    }//Fin index
    
    /*
     * Recupera los depositos validados del autor.
     */
    public function getDepositosValidados() {
          $idUsuario = Session::get('id');
        
          $idAutor = $this->model->get_id_autor($idUsuario);
          $responseDB = $this->model->get_depositos_validados($idAutor);
        
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No tienes nig&uacute;n deposito validado.</h2>';
          } else {
               $tabla .= '<table class="table table-striped table-hover" id="tbl-articulos">' .
				      '<col width="5%">'.
  					  '<col width="53%">'.
				   	  '<col width="30%">'.
				      '<col width="12%">'.
                      '<col width="12%">'.
                      '<col width="12%">'.
                       '<thead>' .
                       '<tr>' .
                       '<th class="">Art&iacute;culo Id</th>' .
                       '<th>Nombre del art&iacute;culo</th>' .
                       '<th>Raz&oacuten social</th>' .
                       '<th class="text-center">RFC</th>' .
                       '<th class="text-center">Monto</th>' .
                       '<th class="text-center"></th>' .
                       '</tr>' .
                       '</thead>';
               $tabla .= '<tbody>';
               foreach ($responseDB as $deposito) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="td-tabla">' . $deposito['artid'] . '</td>';
                    $tabla .= '<td class="td-tabla">' . $deposito['artNombre'] . '</td>';
                    $tabla .= '<td class="td-tabla">' . $deposito['fac_razon_social'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $deposito['fac_rfc'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $deposito['dep_monto'] . '</td>';
                    $tabla .= '<td><p class="btn btn-link detalles" deposito="' . $deposito['artid'] . '"><span class="glyphicon glyphicon-paperclip"></span> Factura<p></td>';
                    $tabla .= '</tr>';
               }
               $tabla .= '</tbody>';
               $tabla .= '</table>';
          }
        return $tabla;
    }//Fin getDepositosValidados
    
    /*
     * Brinda los datos del deposito, el identificador del deposito es esperado como parametro en POST
     */
    public function getDatosDeposito() {
        $idArticulo = $_POST['id'];
        $reponse = '';
        
        if (!empty($idArticulo)) {
                                        
            $responseDB = $this->model->get_datos_deposito($idArticulo);
            if (!$responseDB) {
                $response = 'false';
            } else {                
                $deposito = array(
                    "id" => $responseDB['dep_id'],
                    "banco" => $responseDB['dep_banco'],
                	"sucursal" => $responseDB['dep_sucursal'],
                	"transaccion" => $responseDB['dep_transaccion'],
                	"hr" => $responseDB['dep_hora'],
                    "tipo" => $responseDB['dep_tipo'],
                    "info" => $responseDB['dep_info'],
                    "monto" => $responseDB['dep_monto'],
                    "fecha" => $responseDB['dep_fecha']                
                );
                $response = $deposito;
            }
        }
        echo json_encode($response);
    }//Fin getDatosDeposito
    
    /*
     *Brinda los datos de facturacion. El id del articulo viene el POST como parametro.
     */
    public function getDatosFacturacion() {
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
    }//Fin getDatosFacturacion
    
    /*
     *Brinda los documentos de facturacion. El id del articulo viene el POST como parametro.
     */
    public function getDocumentosFacturacion() {
        $idArticulo = $_POST['id'];
        $response = '';
        
        if (!empty($idArticulo)) {
                                
            $responseDB = $this->model->get_documentos_facturacion($idArticulo);
            
            if (!$responseDB) {
                $response = 'false';
            } else {
                $documentos = array(
                    'generada' => $responseDB['doc_factura_creada'],
                    'archivoxml' => URL.'docs/'.$idArticulo.'/'.$responseDB['doc_factura_xml'],
                    'archivopdf' => URL.'docs/'.$idArticulo.'/'.$responseDB['doc_factura_pdf']
                );
                $response = $documentos;
            }
        }
        echo json_encode($response);
    }//Fin getDatosFacturacion
    
    /*
     * Generar la factura.
     */
     public function GenerarFactura(){
        $idArticulo = $_POST['id'];
         
        $path=DOCS.$idArticulo.'/';
        $nameXml='factura.xml';
        $namePdf='factura.pdf';
         
        //Genera el documento xml del cfdi
        $correcto = $this->GenerarCfdiXml($idArticulo, $path, $nameXml);
        if(!$correcto){
            return false;
        }
         
        //Timbrar el document cfdi, via el pack 
        $correcto = $this->TimbrarDocumento($path, $nameXml);
        if(!$correcto){
            return false;
        }
         
         /*Persiste el nombre de los archivos de la factura y activa la bandera de factura previamente generada*/
        $correcto = $this->model->registro_documentos_factura($idArticulo, $nameXml, $namePdf);
        if(!$correcto){
            return false;
        }
        
        echo 'true';
     }//Fin generarFactura
    
    /*
     * Genera el documento XML del CFI
     */
    private function GenerarCfdiXml($idArticulo, $path, $name){
        try {
            
            $this->GenerarDatosComprobante($idArticulo);
            $this->GenerarDatosEmisor();
            $this->GenerarDatosReceptor($idArticulo);
            $this->GenerarDatosConceptos($idArticulo);    
            $xml=$this->GenerateCFDI->getXML();
            $this->GenerateCFDI->save($path, $name);
            
        } catch (Exception $exception) {
            echo "# del error: " . $exception->getCode() . "\n";
            echo "Descripción del error: " . $exception->getMessage() . "\n";
            return false;
        }
        return true;
    }//Fin GenerarCfdiXml

    /*
     * Genera los datos generales del XML del CFI
     */
    private function GenerarDatosComprobante($idArticulo){
        
        //Recuperamos los datos del deposito.
        $responseDB = $this->model->get_datos_deposito($idArticulo);
        if (!$responseDB) {
            return 'no-pago';
        }
        
        //Tomamos la fecha actual del sistema, como fecha para la factura
        date_default_timezone_set('America/Mexico_City');
        $emisiondate = date('Y-m-d_H:i:s');
        $emisiondate = str_replace("_", "T", $emisiondate);
        
        //Generamos los datos del comprobante.
        $datos =[
            'Serie' => 'A',                          //opcional
            'Folio' => 'A001',                          //opcional
            'Fecha' => $emisiondate,                //Cambiar requerido
            'FormaPago' => '03',                    //Requerido
            'NoCertificado' => '30001000000300023708',                  //Requerido
            'CondicionesDePago' => 'I',             //opcional
            'SubTotal' => number_format($responseDB['dep_monto'],2,'.',''), //requerido
            'Moneda' => 'MXN',                      //requerido
            'Total' => number_format($responseDB['dep_monto'],2,'.',''),    //Requerido
            'TipoDeComprobante' => 'I',             //requerido
            'MetodoPago' => 'PUE',                  //opcional
            'LugarExpedicion' => '38400',           //Requerido, CP de valle, tomado del ejemplo de factura
        ];
        
        //Generar el comprobante.
        $this->GenerateCFDI->addData($datos);  
    }//Fin GenerarDatosComprobante
    
    /*
     * Genera los datos del emisor del XML del CFI
     */
    private function GenerarDatosEmisor(){
        $emisor= [
            'Rfc' => 'UTS980928HM6',
            'Nombre' => 'Universidad Tecnologica del Suroeste de Guanajuato',
            'RegimenFiscal' => '603',       //603 Personas Morales con Fines no Lucrativos
        ];
        $this->GenerateCFDI->add(new Emisor($emisor));

    }//Fin GenerarDatosEmisor
    
    /*
     * Genera los datos del receptor del XML del CFI
     */
    private function GenerarDatosReceptor($idArticulo){
        $responseDB = $this->model->get_datos_facturacion($idArticulo);
        
        if (!$responseDB) {
            return 'no-datos-facturacion';
        }
        
        $domicilio = utf8_encode($responseDB['fac_calle']). ' ' . utf8_encode($responseDB['fac_numero']) . ' ' . utf8_encode($responseDB['fac_colonia']) . ' ' . utf8_encode($responseDB['fac_municipio']). ' '. utf8_encode($responseDB['fac_estado']) . ' ' .utf8_encode($responseDB['fac_cp']);
        
        $receptor = [
            'Rfc' => 'AAA010101AAA',  // $responseDB['fac_rfc'],
            'Nombre' => $responseDB['fac_razon_social'],
            'ResidenciaFiscal' => $domicilio,
            'NumRegIdTrib' => '121585958',
            'UsoCFDI' => 'G01',
        ];
        
        $this->GenerateCFDI->add(new Receptor($receptor));
    }//Fin GenerarDatosReceptor
    
    /*
     * Genera los datos de los conceptos del XML del CFI
     */
    private function GenerarDatosConceptos($idArticulo){
        //Recuperamos los datos de asistencia.
        $responseDB = $this->model->get_asistentes_articulo($idArticulo);
                
        if (!$responseDB) {
            return 'no-asistentes';
        }
        
        foreach ($responseDB as $asistente) {
            $descripcion = "INSCRIPCION AL CONGRESO INTERDISCIPLINARIO DE CUERPOS ACADEMICOS A CELEBRARSE EN LA UNIVERSIDAD TECNOLOGICA DEL SUROESTE DE GUANAJUATO LOS DIAS 20 Y 21 DE SEPTIEMBRE DE 2018 ";

            if($asistente['asi_tipo'] == 'ponente'){
                //Calculo del costo, depende la fecha
                $cuota = 2900.00;
                $descripcion .= "Ponente: ";
            }else{
                //Calculo del costo, depende la fecha
                $cuota = 2600.00;
                $descripcion .= "General: ";
            }
            $descripcion .= utf8_encode($asistente['asi_nombre']);
            $descripcion .= " ID: " . $idArticulo;
            
            $concepto = [
                'ClaveProdServ' => '01010101',              //Clave, no existen en el catalogo
                'Cantidad' => '1.00',                       //
                'ClaveUnidad' => 'H87',                     //Por definir
                'Unidad' => 'NA',                        //Este atributo es opcional.
                'ValorUnitario' => number_format($cuota,2,'.',''),
                'Importe' => number_format($cuota,2,'.',''),
                'Descripcion' => $descripcion,
            ];
            
            $this->GenerateCFDI->add(new Concepto($concepto));                  
        }         
    }//Fin GenerarDatosConceptos
    
    /*
     * Timbre el CFDI, a través del WS de SAT.
     */
    private function TimbrarDocumento($path, $name){
        
        // convertir la cadena del xml en base64
        $ruta_xml = $path . $name;
        $documento_xml = file_get_contents($ruta_xml);
        $xml_base64 = base64_encode($documento_xml);        
        
        //crear un cliente para hacer la petición al WS
        $cliente = new SoapClient(WSDL_URL, array(
            'trace' => 1,
            'use' => SOAP_LITERAL,
        ));

        //parametros para llamar la funcion timbrar_cfdi
        $parametros = array(
            "username" => WSDL_USUARIO,
            "password" => WSDL_CONTRASENA,
            "sxml" => $xml_base64,
        );

        try {
            
            //llamar la funcion timbrar_cfdi
            $respuesta = $cliente->__soapCall("timbrar_cfdi", $parametros);

            //Guardar el xml timbrado
            file_put_contents($path."factura_timbrada.xml", $respuesta->xml);
            
            return true;
        } catch (Exception $exception) {
            //imprimir los mensajes de la excepcion
            //echo "# del error: " . $exception->getCode() . "<br/>";
            echo "Descripción del error: " . $exception->getMessage() . "<br/>";
            return false;
        }
    }//Fin TimbrarDocumento
    
    /*
     * Pon comentario aquí.
     */

    public function test_unit(){
        //ARRAY STRUCT THAT I WILL USE LIKE  PARAMETERS
        $total="13920.00";
        $datos_factura = [
            //'id' => '12',          
            'no_factura' => '12',          
            'fol_fiscal' => '3EFEDDDE-0971-4F3C-B415-4A2A9BAFCAFF',              
            'serie_csd' => '12',                
            'cp_fecha_hr_emision' => '12',       
            'efecto_comprobante' => number_format("12",2,'.',''),
            'serie_cert_sat' => number_format("12",2,'.',''),
            'cliente' => "12",
            'rfc' => "GQO090713894",
            'rfc_emisor'=>"PEVR821210PE8",
            'uso_cfdi' => "12",
            'regimen_fiscal' => "12",
            'rfc_prov_certificacion' => "12",
            'forma_pago' => "12",
            'metodo_pago' => "12",
            'fecha_hr_certificacion' => "12",
            'moneda' => "12",
            'efecto_comprobante' => "12",
            'total_fact' => number_format($total,2,'.',''),
            'sello_dig_cfdi' => "yBteOWQcvIopNv1IjGo8BD5R9TQlN7QmFKzNPqSvkLy1NVSagfloGA2vyWk3MlrIsFedhAWaYQ/Xewn46lw8eviOI6kzNcQyb7mO7N4C9UR4WDKlafqp/YAqcAnXje14R5jiqHT8CdjjgqUeQ/7vNjUazO9MFXFqZBoA9ZRCIQXTxtNexuqgahIq2lNPcs3u0Tnjj9AXYHdefKXP9UPEEFz5A3llWG6gBvRt9AqLOaawEjf7oNMC9FmSDJVpSENnWVEYxcAXv1Qon4sXItfcQOXsv+dcw0xxiDa3EFrP9N+6qJuu5NP6F3bqp7djFOhEQbibmaq/5T73cg2re2hNXA==",
            'sello_dig_sat' => "12",
            'sello_ori_comple_cert_dig' => "12",
            'art_id' => "1"
        ];
        $det_factura=[];
        $num=1;
        $d{$num} = new detalle();
        $d{$num}->cantidad=$num;
        $d{$num}->uni_medida="unidad";
        $d{$num}->no_identifi="00001";
        $d{$num}->descripcion="INSCRIPCIÓN AL CONGRESO INTERDISCIPLINARIO DE CUERPOS ACADÉMICOS A CELEBRARSE EN LA UNIVERSIDAD TECNOLÓGICA DEL SUROESTE DE GUANAJUATO LOS DÍAS 27 Y 28 DE SEPTIEMBRE DE 2017 Pnente: ZAIRA VARGAS SOLANO. ID:1210";       
        $d{$num}->precio_uni=number_format("2600",2,'.','');
        $d{$num}->importe=number_format("2600",2,'.','');
        $det_factura[]=$d{$num};
        
        $num=2;
        $d{$num} = new detalle();
        $d{$num}->cantidad=$num;
        $d{$num}->uni_medida="unidad";
        $d{$num}->no_identifi="00001";
        $d{$num}->descripcion="INSCRIPCIÓN AL CONGRESO INTERDISCIPLINARIO DE CUERPOS ACADÉMICOS A CELEBRARSE EN LA UNIVERSIDAD TECNOLÓGICA DEL SUROESTE DE GUANAJUATO LOS DÍAS 27 Y 28 DE SEPTIEMBRE DE 2017 Pnente: ZAIRA VARGAS SOLANO. ID:1210";       
        $d{$num}->precio_uni=number_format("2600",2,'.','');
        $d{$num}->importe=number_format("2600",2,'.','');
        $det_factura[]=$d{$num};
        
        $this->generaFacturaPDF($datos_factura,$det_factura);
    }

    function generaFacturaPDF($datos_factura,$det_factura) {
        /*        foreach ($detalle as $detalle) {
            echo 'This car is a ' . $detalle->name . ' ' . $detalle->number . "\n";
        }
         */
        //SE CODIFICA A FORMATO JSON PARA TENER ACCESO A DICHO FORMATO
        $json_factura=json_encode($datos_factura,true);
        try{
       		//$responseDB = $this->model->get_autores_articulo($_GET['id']);
        	// Instanciation of inherited class
            $this->GeneratePDF->AddPage("P","Letter");
        	#Establecemos los m�rgenes izquierda, arriba y derecha:
        	$this->GeneratePDF->SetMargins(10, 10 , 10);
        	#Establecemos el margen inferior:
        	$this->GeneratePDF->SetAutoPageBreak(true,15);
        	//COLOCA EL PRIMER AUTOR
            //$this->GeneratePDF->SetFontSize(15);
            $this->GeneratePDF->AddFont('helvetica', '', 'helvetica.php');
            $this->GeneratePDF->SetFont('helvetica', '', 8);
            $fila=5;
            $col=40;
            //=================================================================
            //HACE LA ASIGNACION Y CONVERSION PARA CARACTERES ESPECIALES
            //=================================================================   
        	$emisor_nombre=iconv('UTF-8', 'windows-1252', "UNIVERSIDAD TECNOLOGICA DEL SUROESTE DE GUANAJUATO.");
            $emisor_rfc=iconv('UTF-8', 'windows-1252',"UTS980928HM6");
            $emisor_direccion=iconv('UTF-8', 'windows-1252',"CARR. VALLE - HUANIMARO KM. 1.2");
            $emisor_col=iconv('UTF-8', 'windows-1252',"Col. SN 38400");
            $emisor_ciudad=iconv('UTF-8', 'windows-1252',"VALLE DE SANTIAGO Guanajuato México.");
            $emisor_tel=iconv('UTF-8', 'windows-1252',"Tel. (456) 6437180, 6436265, 6437184 EXT. 112");
            $this->GeneratePDF->Image("./public/images/utsoe_fac.png", 3,$fila,35,20 );
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell( 90, 4, mb_strtoupper($emisor_nombre), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+8);
            $this->GeneratePDF->MultiCell( 90, 4, mb_strtoupper($emisor_rfc), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell( 90, 4, mb_strtoupper($emisor_direccion), 0,"L");  
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell( 90, 4, mb_strtoupper($emisor_col), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+20);
            $this->GeneratePDF->MultiCell( 90, 4, $emisor_ciudad, 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+24);
            $this->GeneratePDF->MultiCell( 90, 4, mb_strtoupper($emisor_tel), 0,"L");
            $this->GeneratePDF->Rect(130,$fila, 80, 50);
            //RECUADRO PARA LOS DATOS DE FOLIO Y FACTURA
            $this->GeneratePDF->Rect(130,$fila, 80, 50);
            $fila=8;
            $this->GeneratePDF->SetFont('helvetica', 'B', 8);
            $this->GeneratePDF->SetXY(130,$fila);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("FACTURA NO.:".$datos_factura['no_factura']), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+4);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("FOLIO FISCAL :"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+8);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper($datos_factura['fol_fiscal']), 0,"C");            
            $this->GeneratePDF->SetXY(130,$fila+12);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("NO. DE SERIE DEL CSD:"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+16);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper($datos_factura['serie_csd']), 0,"C");               
            $this->GeneratePDF->SetXY(130,$fila+20);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8','windows-1252','CóDIGO POSTAL, FECHA Y HORA DE EMISIóN'), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+24);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper($datos_factura['cp_fecha_hr_emision']), 0,"C");               
            $this->GeneratePDF->SetXY(130,$fila+28);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "EFECTO DE COMPROBANTE:"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+32);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper($datos_factura['efecto_comprobante']), 0,"C");               
            $this->GeneratePDF->SetXY(130,$fila+36);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "NO. DE SERIE DEL CERTIFICADO SAT:"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+40);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper($datos_factura['serie_cert_sat']), 0,"C");    
            $fila=60;
            $col=2;
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "CLIENTE:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+8);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("RFC:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "USO CFDI:"), 0,"L");
            $col=20;
            $this->GeneratePDF->SetFont('helvetica', '', 8);
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(54, 4, iconv('UTF-8', 'windows-1252', $datos_factura['cliente']), 0,"L");
            
            $this->GeneratePDF->SetXY($col,$fila+8);
            $this->GeneratePDF->MultiCell(54, 4, iconv('UTF-8', 'windows-1252', $datos_factura['rfc']), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(54, 4, iconv('UTF-8', 'windows-1252', $datos_factura['uso_cfdi']), 0,"L");
            $col=74;
            $this->GeneratePDF->SetFont('helvetica', 'B', 8);
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "Régimen fiscal:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "RFC del proveedor de certificación:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Forma de pago:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Método de pago:"), 0,"L");
            $col=150;
            
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Fecha y hora de certificación:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "Moneda:"), 0,"L");
            
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "Efecto de comprobante"), 0,"L");
            //DATOS FISCALES DE EMISOR DE LA FACTURA 
            $col=105;
            $this->GeneratePDF->SetFont('helvetica','', 8);
            //TIPO DE PERSONA
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', $datos_factura['regimen_fiscal']), 0,"L");
            //CIUDAD DEL EMISOR
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(45, 4, iconv('UTF-8', 'windows-1252', $datos_factura['rfc_prov_certificacion']), 0,"L");
            //FORMA DE PAGO DE LA FACTURA
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(45, 4, iconv('UTF-8', 'windows-1252', $datos_factura['forma_pago']), 0,"L");
            //TIPO DE PAGO
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(45, 4, iconv('UTF-8', 'windows-1252', $datos_factura['metodo_pago']), 0,"L");
            $col=182;
            //FECHA DE EMICION DE LA FACTURA
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(30, 4, iconv('UTF-8', 'windows-1252', $datos_factura['fecha_hr_certificacion']), 0,"L");
            //MONEDA UTILIZADA EN EL PAGO
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(30, 4, iconv('UTF-8', 'windows-1252', $datos_factura['moneda']), 0,"L");
            //NO DE CAJA
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(30, 4, iconv('UTF-8', 'windows-1252', $datos_factura['efecto_comprobante']), 0,"L");
            //=================================================
            //DETALLES DE LA FACTUTA
            //=================================================
            // Column headings
            $header = array('CANTIDAD', 'UNIDAD DE MEDIDA', 'NO. IDENTIFICACION', 'DESCRIPCION','PRECIO UNITARIO','IMPORTE');
            // Column widths
            $w = array(20, 15, 17, 95, 32, 30);
            // Header
            $this->GeneratePDF->SetXY(4,$fila+25);
            $ejeX=4;
            //CICLO PARA COLOCAR LOS TITULOS EN LA TABLA
            for($i=0;$i<count($header);$i++){
                if($i>=1 && $i<=2)
                   $this->GeneratePDF->SetFont('helvetica', '', 5);
                else
                    $this->GeneratePDF->SetFont('helvetica', '', 7);
                $this->GeneratePDF->MultiCell($w[$i], 3, $header[$i], "0","C");
                $ejeX+=$w[$i];
                $this->GeneratePDF->SetXY($ejeX,$fila+25);
            }
            $this->GeneratePDF->Ln();
            //DEFINICION DEL TIPO DE LETRA
            $this->GeneratePDF->SetFont('helvetica','', 7);
            // DATO DE LA FACTURA
            $line_height = 4;
            $fila+=32;
            $col=4;
            foreach($det_factura as $row)
            {
                $this->GeneratePDF->SetXY($col,$fila);
                //CANTIDAD
                $this->GeneratePDF->Cell($w[0],7,$row->cantidad,'LR',0,'C');
                //UNIDAD DE MEDIDA
                $this->GeneratePDF->Cell($w[1],7,$row->uni_medida,'LR',0,'C');
                //NO. DE IDENTIFICACION
                $this->GeneratePDF->Cell($w[2],7,$row->no_identifi,'LR',0,'C');
                $texto=utf8_decode($row->descripcion);
                //LA VARIABLE HEIGHT ALMACENA EL INICIO DEL SIGUIENTE INICIO 
                //DEL CONCEPTO A FACTURAR
                $height = (ceil(($this->GeneratePDF->GetStringWidth($texto) / $w[3])) * $line_height);
                $col=4+$w[0]+$w[1]+$w[2];
                $this->GeneratePDF->SetXY($col,$fila+1);
                //COLOCA EL TEXTO DE LA DESCRIPCION DE CADA ELEMENTO FACTURADO
                $this->GeneratePDF->MultiCell($w[3],$line_height,$texto,"0","L");
                $this->GeneratePDF->SetXY(151,$fila);
                //PRECIO UNITARIO
                $this->GeneratePDF->Cell($w[4],7,number_format($row->precio_uni,2,".",","),'0',0,'R');
                //IMPORTE DEL DETALLE  FACTURADO
                $this->GeneratePDF->Cell($w[5],7,number_format($row->importe,2,".",","),'0',0,'R');
                $fila+=$height+2;
                $col=4;
            }
            //GENERAR CODIGO QR
            $path_qr=$this->get_qr33($datos_factura['art_id'],$datos_factura['rfc_emisor'],$datos_factura['rfc'],$datos_factura['fol_fiscal'],$datos_factura['total_fact'],$datos_factura['sello_dig_cfdi']);
            $pntBegin=84;
            $pntEnd=197;
            //TRAZOS DE LINEAS VERTICALES PARA SEPARAR CADA COLUMNA
            //LiNEA INICIAL SUPERIOR
            $this->GeneratePDF->Line(4,$pntBegin,213,$pntBegin);
            $this->GeneratePDF->Line(4,$pntBegin,213,$pntBegin);
            //2da LiNEA  SUPERIOR
            $this->GeneratePDF->Line(4,93,213,93);
            $this->GeneratePDF->Line(4,93,213,93);
            //LINEA INICIAL DERECHA
            $this->GeneratePDF->Line(4,$pntBegin,4, $pntEnd);
            $this->GeneratePDF->Line(4,$pntBegin,4, $pntEnd);
            //2DA LINEA DERECHA
            $this->GeneratePDF->Line(24,$pntBegin,24, $pntEnd);
            $this->GeneratePDF->Line(24,$pntBegin,24, $pntEnd);
            //3DA LINEA DERECHA
            $this->GeneratePDF->Line(39,$pntBegin,39, $pntEnd);
            $this->GeneratePDF->Line(39,$pntBegin,39, $pntEnd);
            //4DA LINEA DERECHA
            $this->GeneratePDF->Line(56,$pntBegin,56, $pntEnd);
            $this->GeneratePDF->Line(56,$pntBegin,56, $pntEnd);
            //5DA LINEA DERECHA
            $this->GeneratePDF->Line(150,$pntBegin,150, $pntEnd);
            //$this->GeneratePDF->Line(153,$pntBegin,153, $pntEnd);
            //6DA LINEA DERECHA
            $this->GeneratePDF->Line(183,$pntBegin,183, $pntEnd);
            $this->GeneratePDF->Line(183,$pntBegin,183, $pntEnd);
            //LINEA INFERIOR 
            $this->GeneratePDF->Line(4,$pntEnd,213, $pntEnd);
            $this->GeneratePDF->Line(4,$pntEnd,213, $pntEnd);
            //LINEA FINAL IZQUIERDA
            $this->GeneratePDF->Line(213,$pntBegin,213, $pntEnd);
            $this->GeneratePDF->Line(213,$pntBegin,213, $pntEnd);
            //LNEA FINAL INFERIOR
            $this->GeneratePDF->Line(4,$pntEnd,213, $pntEnd);
            $this->GeneratePDF->Line(4,$pntEnd,213, $pntEnd);
            $fila=197;
            //CANTIDAD CON LETRA DEL TOTAL DE LA FACTURA
            $this->GeneratePDF->SetXY(10,$fila);
            $this->GeneratePDF->Cell($w[4],7,"CANTIDAD CON LETRA :",0,0,'L');
            $cantidadLetra=$this->numletras($datos_factura['total_fact'],1);

            $this->GeneratePDF->SetXY(40,$fila);
            $this->GeneratePDF->Cell($w[4],7,$cantidadLetra,0,0,'L');

            //SUBTOTAL DE LA FACTURA
            $this->GeneratePDF->SetXY(150,$fila);
            $this->GeneratePDF->Cell($w[4],7,"SUBTOTAL :",0,0,'R');
            $this->GeneratePDF->SetXY(181,$fila);
            $this->GeneratePDF->Cell($w[4],7,number_format($datos_factura['total_fact'],2,".",","),0,0,'R');
            //TOTAL DE LA FACTURA
            $fila=201;
            $this->GeneratePDF->SetXY(150,$fila);
            $this->GeneratePDF->Cell($w[4],7,"TOTAL :",0,0,'R');
            $this->GeneratePDF->SetXY(181,$fila);
            $this->GeneratePDF->Cell($w[4],7,number_format($datos_factura['total_fact'],2,".",","),0,0,'R');
            //TITULO  DEL SELLO DIGITAL DEL CFDI
            $fila=210;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Cell($w[4],7,"SELLO DIGITAL DEL CFDI",0,0,'L');
            //TITULO DEL SELLO DIGITAL DEL SAT
            $fila=228;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Cell($w[4],7,"SELLO DIGITAL DEL SAT",0,0,'L');
            //TITULO ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT
            $fila=243;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Cell($w[4],7,utf8_decode("CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACIÓN DIGITAL DEL SAT"),0,0,'L');
            //CADENA DEL SELLO DIGITAL DEL CFDI
            $fila=215;
            $this->GeneratePDF->SetFont('helvetica', 'B', 6);
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->MultiCell(160,4,$datos_factura['sello_dig_cfdi'],1,"L");
            //CADENA DEL SELLO DIGITAL DEL SAT
            $fila=234;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Multicell(160,4,$datos_factura['sello_dig_sat'],1,"L");
            //CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT
            $fila=249;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Multicell(160,4,$datos_factura['sello_ori_comple_cert_dig'],1,"L");
            //IMPRESION DE CODE-QR
            $fila=211;
            if($path_qr!=""){
                $this->GeneratePDF->Image($path_qr,165,$fila,48,48,'jpg');
            }
            $fila=263;
            $this->GeneratePDF->SetXY(4,$fila);
            // Mensaje inferior de la factura
            $this->GeneratePDF->Cell(210,1,utf8_decode('Este documento es una representación impresa de un CFDI'),0,0,"C");
            //No. de pagina
            $this->GeneratePDF->SetXY(200,$fila);
            $this->GeneratePDF->Cell(10,1,utf8_decode('Pág. ').$this->GeneratePDF->PageNo()." de 1",0,0,"C");

            //DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
            //$this->GeneratePDF->Output('fact_art_1.pdf','I');
            $this->GeneratePDF->isFinished  = true;
            $this->GeneratePDF->Output(DOCS.$datos_factura['art_id'].'/fact_art_'.$datos_factura['art_id'].'.pdf','F');
        }catch(Exception $e){
            echo ($e->getMessage());    
        }
    }

    function get_qr33($id_art,$emisor,$receptor,$UUID,$total,$sello){
        //GENERAR CODIGO QR
        //===================================================
        //METODO PARA GENERAR EL CODIGO QR DE LA FACTURA
        //===================================================
        $classQR = 'libs/QRcode33/phpqrcode.lib.php';
        if (file_exists($classQR)) {
            require_once $classQR;
            $folder = DOCS.$id_art."/";
            $file_name=$UUID."png";
            $s_Filename = $folder.$file_name;
            $code="https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx&id=F7C0E3BC-B09D-482F-881E-3F6B063DED31&re=AAA010101AAA&rr=XXX010101XXA&tt=125.6&fe=A1345678";
           
            //$code="https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx&id=".$UUID."&re=".$emisor']."&rr=".$receptor."&tt=".$total."&fe=".substr(sello,-8);
            
            QRcode::png($code, $s_Filename);
            if(file_exists($s_Filename))
            {
                $img = imagecreatefrompng($s_Filename);                                
                imagejpeg($img,$folder."$UUID.jpg"); 
                return $folder.$UUID.".jpg";
            }
        }
        return "";
    }

    /* 
    $numero=valor a retornar en letras. 
    $_moneda=1=Colones, 2=Dólares 3=Euros 
    Las siguientes funciones (unidad() hasta milmillon() forman parte de ésta función 
    */ 
    public function numletras($numero,$_moneda) 
    { 
        switch($_moneda) 
        { 
        case 1: 
            $_nommoneda='PESOS'; 
            break; 
        case 2: 
            $_nommoneda='DÓLARES'; 
            break; 
        case 3: 
            $_nommoneda='EUROS'; 
            break; 
        } 
        $tempnum = explode('.',$numero); 
        if ($tempnum[0] !== ""){ 
            $numf = $this->milmillon($tempnum[0]); 
            if ($numf == "UNO") 
            { 
                $numf = substr($numf, 0, -1); 
            } 
            $TextEnd = $numf.' '; 
            $TextEnd .= $_nommoneda.' , '; 
        } 
        if ($tempnum[1] == "" || $tempnum[1] >= 100) 
        { 
            $tempnum[1] = "00" ; 
        } 
        $TextEnd .= $tempnum[1] ; 
        $TextEnd .= "/100 MXN"; 
        return $TextEnd;    
    } 

    function unidad($numuero){ 
        switch ($numuero) 
        { 
            case 9: 
            { 
                $numu = "NUEVE"; 
                break; 
            } 
            case 8: 
            { 
                $numu = "OCHO"; 
                break; 
            } 
            case 7: 
            { 
                $numu = "SIETE"; 
                break; 
            } 
            case 6: 
            { 
                $numu = "SEIS"; 
                break; 
            } 
            case 5: 
            { 
                $numu = "CINCO"; 
                break; 
            } 
            case 4: 
            { 
                $numu = "CUATRO"; 
                break; 
            } 
            case 3: 
            { 
                $numu = "TRES"; 
                break; 
            } 
            case 2: 
            { 
                $numu = "DOS"; 
                break; 
            } 
            case 1: 
            { 
                $numu = "UNO"; 
                break; 
            } 
            case 0: 
            { 
                $numu = ""; 
                break; 
            } 
        } 
        return $numu; 
    } 

    function decena($numdero){ 
        if ($numdero >= 90 && $numdero <= 99) 
        { 
            $numd = "NOVENTA "; 
            if ($numdero > 90) 
                $numd = $numd."Y ".($this->unidad($numdero - 90)); 
        } 
        else if ($numdero >= 80 && $numdero <= 89) 
        { 
            $numd = "OCHENTA "; 
            if ($numdero > 80) 
                $numd = $numd."Y ".($this->unidad($numdero - 80)); 
        } 
        else if ($numdero >= 70 && $numdero <= 79) 
        { 
            $numd = "SETENTA "; 
            if ($numdero > 70) 
                $numd = $numd."Y ".($this->unidad($numdero - 70)); 
        } 
        else if ($numdero >= 60 && $numdero <= 69) 
        { 
            $numd = "SESENTA "; 
            if ($numdero > 60) 
                $numd = $numd."Y ".($this->unidad($numdero - 60)); 
        } 
        else if ($numdero >= 50 && $numdero <= 59) 
        { 
            $numd = "CINCUENTA "; 
            if ($numdero > 50) 
                $numd = $numd."Y ".($this->unidad($numdero - 50)); 
        } 
        else if ($numdero >= 40 && $numdero <= 49) 
        { 
            $numd = "CUARENTA "; 
            if ($numdero > 40) 
                $numd = $numd."Y ".($this->unidad($numdero - 40)); 
        } 
        else if ($numdero >= 30 && $numdero <= 39) 
        { 
            $numd = "TREINTA "; 
            if ($numdero > 30) 
                $numd = $numd."Y ".($this->unidad($numdero - 30)); 
        } 
        else if ($numdero >= 20 && $numdero <= 29) 
        { 
        if ($numdero == 20) 
            $numd = "VEINTE "; 
        else 
            $numd = "VEINTI".($this->unidad($numdero - 20)); 
        } 
        else if ($numdero >= 10 && $numdero <= 19) 
        { 
            switch ($numdero){ 
            case 10: 
            { 
                $numd = "DIEZ "; 
                break; 
            } 
            case 11: 
            { 
                $numd = "ONCE "; 
                break; 
            } 
            case 12: 
            { 
                $numd = "DOCE "; 
                break; 
            } 
            case 13: 
            { 
                $numd = "TRECE "; 
                break; 
            } 
            case 14: 
            { 
                $numd = "CATORCE "; 
                break; 
            } 
            case 15: 
            { 
                $numd = "QUINCE "; 
                break; 
            } 
            case 16: 
            { 
                $numd = "DIECISEIS "; 
                break; 
            } 
            case 17: 
            { 
                $numd = "DIECISIETE "; 
                break; 
            } 
            case 18: 
            { 
                $numd = "DIECIOCHO "; 
                break; 
            } 
            case 19: 
            { 
                $numd = "DIECINUEVE "; 
                break; 
            } 
        } 
    } 
    else 
        $numd = $this->unidad($numdero); 
        return $numd; 
    } 

    function centena($numc){ 
        if ($numc >= 100) 
        { 
            if ($numc >= 900 && $numc <= 999) 
            { 
                $numce = "NOVECIENTOS "; 
                if ($numc > 900) 
                    $numce = $numce.($this->decena($numc - 900)); 
            } 
            else if ($numc >= 800 && $numc <= 899) 
            { 
                $numce = "OCHOCIENTOS "; 
                if ($numc > 800) 
                    $numce = $numce.($this->decena($numc - 800)); 
            } 
            else if ($numc >= 700 && $numc <= 799) 
            { 
                $numce = "SETECIENTOS "; 
                if ($numc > 700) 
                    $numce = $numce.($this->decena($numc - 700)); 
            } 
            else if ($numc >= 600 && $numc <= 699) 
            { 
                $numce = "SEISCIENTOS "; 
                if ($numc > 600) 
                    $numce = $numce.($this->decena($numc - 600)); 
            } 
            else if ($numc >= 500 && $numc <= 599) 
            { 
                $numce = "QUINIENTOS "; 
                if ($numc > 500) 
                    $numce = $numce.($this->decena($numc - 500)); 
            } 
            else if ($numc >= 400 && $numc <= 499) 
            { 
                $numce = "CUATROCIENTOS "; 
                if ($numc > 400) 
                    $numce = $numce.($this->decena($numc - 400)); 
            } 
            else if ($numc >= 300 && $numc <= 399) 
            { 
                $numce = "TRESCIENTOS "; 
                if ($numc > 300) 
                    $numce = $numce.($this->decena($numc - 300)); 
            } 
            else if ($numc >= 200 && $numc <= 299) 
            { 
                $numce = "DOSCIENTOS "; 
                if ($numc > 200) 
                    $numce = $numce.($this->decena($numc - 200)); 
            } 
            else if ($numc >= 100 && $numc <= 199) 
            { 
                if ($numc == 100) 
                    $numce = "CIEN "; 
                else 
                    $numce = "CIENTO ".($this->decena($numc - 100)); 
            } 
        } 
        else{
            $numce = $this->decena($numc); 
        }
        return $numce; 
    } 

    function miles($nummero){ 
        if ($nummero >= 1000 && $nummero < 2000){ 
            $numm = "MIL ".($this->centena($nummero%1000)); 
        } 
        if ($nummero >= 2000 && $nummero <10000){ 
            $numm = $this->unidad(Floor($nummero/1000))." MIL ".($this->centena($nummero%1000)); 
        } 
        if ($nummero < 1000) 
            $numm = $this->centena($nummero); 
        return $numm; 
    } 

    function decmiles($numdmero){ 
        if ($numdmero == 10000) 
            $numde = "DIEZ MIL"; 
        if ($numdmero > 10000 && $numdmero <20000){ 
            $numde = $this->decena(Floor($numdmero/1000))."MIL ".($this->centena($numdmero%1000)); 
        } 
        if ($numdmero >= 20000 && $numdmero <100000){ 
            $numde = $this->decena(Floor($numdmero/1000))." MIL ".($this->miles($numdmero%1000)); 
        } 
        if ($numdmero < 10000) 
            $numde = $this->miles($numdmero); 
        return $numde; 
    } 

    function cienmiles($numcmero){ 
        if ($numcmero == 100000) 
            $num_letracm = "CIEN MIL"; 
        if ($numcmero >= 100000 && $numcmero <1000000){ 
            $num_letracm = $this->centena(Floor($numcmero/1000))." MIL ".($this->centena($numcmero%1000)); 
        } 
        if ($numcmero < 100000) 
            $num_letracm = $this->decmiles($numcmero); 
        return $num_letracm; 
    } 

    function millon($nummiero){ 
        if ($nummiero >= 1000000 && $nummiero <2000000){ 
            $num_letramm = "UN MILLON ".($this->cienmiles($nummiero%1000000)); 
        } 
        if ($nummiero >= 2000000 && $nummiero <10000000){ 
            $num_letramm = $this->unidad(Floor($nummiero/1000000))." MILLONES ".($this->cienmiles($nummiero%1000000)); 
        } 
        if ($nummiero < 1000000) 
            $num_letramm = $this->cienmiles($nummiero); 
        return $num_letramm; 
    } 

    public function decmillon($numerodm){ 
        if ($numerodm == 10000000) 
            $num_letradmm = "DIEZ MILLONES"; 
        if ($numerodm > 10000000 && $numerodm <20000000){ 
            $num_letradmm = $this->decena(Floor($numerodm/1000000))."MILLONES ".($this->cienmiles($numerodm%1000000)); 
        } 
        if ($numerodm >= 20000000 && $numerodm <100000000){ 
            $num_letradmm = $this->decena(Floor($numerodm/1000000))." MILLONES ".($this->millon($numerodm%1000000)); 
        } 
        if ($numerodm < 10000000) 
            $num_letradmm = $this->millon($numerodm); 
        return $num_letradmm; 
    } 

    public function cienmillon($numcmeros){ 
        if ($numcmeros == 100000000) 
           $num_letracms = "CIEN MILLONES"; 
        if ($numcmeros >= 100000000 && $numcmeros <1000000000){ 
            $num_letracms = $this->centena(Floor($numcmeros/1000000))." MILLONES ".($this->millon($numcmeros%1000000)); 
        } 
        if ($numcmeros < 100000000) 
            $num_letracms = $this->decmillon($numcmeros); 
        return $num_letracms; 
    } 

    public function milmillon($nummierod){ 
        if ($nummierod >= 1000000000 && $nummierod <2000000000){ 
            $num_letrammd = "MIL ".($this->cienmillon($nummierod%1000000000)); 
        } 
        if ($nummierod >= 2000000000 && $nummierod <10000000000){ 
            $num_letrammd = $this->unidad(Floor($nummierod/1000000000))." MIL ".($this->cienmillon($nummierod%1000000000)); 
        } 
        if ($nummierod < 1000000000) 
            $num_letrammd = $this->cienmillon($nummierod); 
        return $num_letrammd; 
    } 

}//Fin mifacturacion


class Collection
{
    private $items=array();

    public function addItem($obj){
        $this->items[]=$obj;
    }

    /**  
    * Get all the items of the Collection  
    *  
    * @return array  
    */  
    public function all()  
    {  
        return $this->items;  
    } 
}

class detalle{
    public $cantidad;          
    public $uni_medida;              
    public $no_identifi;                
    public $descripcion;
    public $precio_uni;
    public $importe;
}
