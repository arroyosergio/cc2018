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
    function generaFacturaPDF() {
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
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("FACTURA NO.: 12122"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+4);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("FOLIO FISCAL (UIID):"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+8);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("34-3434-3434345"), 0,"C");            
            $this->GeneratePDF->SetXY(130,$fila+12);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("NO. DE SERIE DEL CERTIFICADO DEL SAT:"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+16);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("34-3434-3434345"), 0,"C");               
            $this->GeneratePDF->SetXY(130,$fila+20);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("NO. DE SERIE DEL CERTIFICADO DEL EMISOR"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+24);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("34-3434-3434345"), 0,"C");               
            $this->GeneratePDF->SetXY(130,$fila+28);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "FECHA Y HORA DE CERTIFICACIÓN:"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+32);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("34-3434-3434345"), 0,"C");               
            $this->GeneratePDF->SetXY(130,$fila+36);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "FECHA Y HORA DE EMISIÓN DE CFDI:"), 0,"C");
            $this->GeneratePDF->SetXY(130,$fila+40);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("34-3434-3434345"), 0,"C");    
            $fila=60;
            $col=2;
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "CLIENTE:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+8);
            $this->GeneratePDF->MultiCell(80, 4, mb_strtoupper("RFC:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "DIRECCION:"), 0,"L");
            $col=20;
            $this->GeneratePDF->SetFont('helvetica', '', 8);
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(54, 4, iconv('UTF-8', 'windows-1252', "INSTITUTO TECNOLOGICO DEL SUR DE GTO."), 0,"L");
            
            $this->GeneratePDF->SetXY($col,$fila+8);
            $this->GeneratePDF->MultiCell(54, 4, iconv('UTF-8', 'windows-1252', "rfc-0000-----0000"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(54, 4, iconv('UTF-8', 'windows-1252', "direccion conocida s/n,"), 0,"L");
            $col=74;
            $this->GeneratePDF->SetFont('helvetica', 'B', 8);
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "Régimen fiscal:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Lugar de expedición:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Forma de pago:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Método de pago:"), 0,"L");
            $col=150;
            
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(35, 4, iconv('UTF-8', 'windows-1252', "Fecha de expedicion:"), 0,"L");
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "Clave de moneda:"), 0,"L");
            
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "Caja"), 0,"L");
            //DATOS FISCALES DE EMISOR DE LA FACTURA 
            $col=105;
            $this->GeneratePDF->SetFont('helvetica','', 8);
            //TIPO DE PERSONA
            $this->GeneratePDF->SetXY($col,$fila);
            $this->GeneratePDF->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', "PERSONA MORAL CON FINES NO LUCRATIVOS"), 0,"L");
            //CIUDAD DEL EMISOR
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(45, 4, iconv('UTF-8', 'windows-1252', "VALLE DE SANTIAGO, Guanajuato"), 0,"L");
            //FORMA DE PAGO DE LA FACTURA
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(45, 4, iconv('UTF-8', 'windows-1252', "Pago en una sola exhibición"), 0,"L");
            //TIPO DE PAGO
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(45, 4, iconv('UTF-8', 'windows-1252', "03-Transferencia electrónica de fondos"), 0,"L");
            $col=182;
            //FECHA DE EMICION DE LA FACTURA
            $this->GeneratePDF->SetXY($col,$fila+4);
            $this->GeneratePDF->MultiCell(30, 4, iconv('UTF-8', 'windows-1252', "07 diciembre 2017"), 0,"L");
            //MONEDA UTILIZADA EN EL PAGO
            $this->GeneratePDF->SetXY($col,$fila+12);
            $this->GeneratePDF->MultiCell(30, 4, iconv('UTF-8', 'windows-1252', "MNX"), 0,"L");
            //NO DE CAJA
            $this->GeneratePDF->SetXY($col,$fila+16);
            $this->GeneratePDF->MultiCell(30, 4, iconv('UTF-8', 'windows-1252', "1"), 0,"L");
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
            $this->GeneratePDF->SetXY($col,$fila);
            //CANTIDAD
            $this->GeneratePDF->Cell($w[0],7,"1",'LR',0,'C');
            //UNIDAD DE MEDIDA
            $this->GeneratePDF->Cell($w[1],7,"NA",'LR',0,'C');
            //NO. DE IDENTIFICACION
            $this->GeneratePDF->Cell($w[2],7,"1",'LR',0,'C');
            $texto=utf8_decode("INSCRIPCIÓN AL CONGRESO INTERDISCIPLINARIO DE CUERPOS ACADÉMICOS A CELEBRARSE EN LA UNIVERSIDAD TECNOLÓGICA DEL SUROESTE DE GUANAJUATO LOS DÍAS 27 Y 28 DE SEPTIEMBRE DE 2017  Pnente: ZAIRA VARGAS SOLANO. ID:1210");
            //LA VARIABLE HEIGHT ALMACENA EL INICIO DEL SIGUIENTE INICIO 
            //DEL CONCEPTO A FACTURAR
            $height = (ceil(($this->GeneratePDF->GetStringWidth($texto) / $w[3])) * $line_height);
            $col=4+$w[0]+$w[1]+$w[2];
            $this->GeneratePDF->SetXY($col,$fila+1);
            //COLOCA EL TEXTO DE LA DESCRIPCION DE CADA ELEMENTO FACTURADO
            $this->GeneratePDF->MultiCell($w[3],$line_height,$texto,"0","L");
            $this->GeneratePDF->SetXY(151,$fila);
            //PRECIO UNITARIO
            $this->GeneratePDF->Cell($w[4],7,number_format(20000),'0',0,'R');
            //IMPORTE DEL DETALLE  FACTURADO
            $this->GeneratePDF->Cell($w[5],7,number_format(12222),'0',0,'R');
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
            $this->GeneratePDF->SetXY(23,$fila);
            $this->GeneratePDF->Cell($w[4],7,"CANTIDAD CON LETRA :",0,0,'L');
            //SUBTOTAL DE LA FACTURA
            $this->GeneratePDF->SetXY(150,$fila);
            $this->GeneratePDF->Cell($w[4],7,"SUBTOTAL :",0,0,'R');
            $this->GeneratePDF->SetXY(181,$fila);
            $this->GeneratePDF->Cell($w[4],7,"12.00",0,0,'R');
            //TOTAL DE LA FACTURA
            $fila=201;
            $this->GeneratePDF->SetXY(150,$fila);
            $this->GeneratePDF->Cell($w[4],7,"TOTAL :",0,0,'R');
            $this->GeneratePDF->SetXY(181,$fila);
            $this->GeneratePDF->Cell($w[4],7,"22.00",0,0,'R');
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
            $this->GeneratePDF->MultiCell(160,4,"DBmc7pLCLIC9WerBqssX2TJzp92QgDgfrMuWqaDFxIcH8mgJrBFK+Wu08BJetQVOkpcLTRaiL4FJH/lYXeA5X13M7elqVPpz7QGcJMns1oXTmwkwAfVrP27lNEM37j8OR5C/IdzGngOtGzpd8yQ1XJL6hhf/u7iyG96tCHt0repwGSDNckCTw3pwWEvqhjYaaiVn593OdGSVEVDDY1vus5k+XNitO3OM7ix8fRzz7qetw4R6vwkOLB3MCsXtN5yYKRAGi7b/koX7noj46v07dTqWGFw08ipqA8hAbzy/Ysb3cPZm52uzbXZstmpk2C96oAhdD3D+fjvRaX2ZmT8Q==",1,"L");
            //CADENA DEL SELLO DIGITAL DEL SAT
            $fila=234;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Multicell(160,4,"shzih7DqH87uCNCvrYBs/QVJH3qpDAkvVbIfcV21MPdWN+6Nu8CdeGgOBa7Y84DLfKBOcVtpu9kZyHKgIMOkL61FscXwipyOGyagPvWXuLXGuvXzErVD6dBYUQuYKio1AlH477ANq91m5BfwX03TNpGUQ0fLftnNUiUDjbupDQ1FNHhTif0uX7Cf4yiKV/o/I+sUeDoWJhdmkRji4tyRtNvtgxt5HBLDA14s235Rw==",1,"L");
            //CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT
            $fila=249;
            $this->GeneratePDF->SetXY(4,$fila);
            $this->GeneratePDF->Multicell(160,4,"shzih7DqH87uCNCvrYBs/QVJH3qpDAkvVbIfcV21MPdWN+6Nu8CdeGgOBa7Y84DLfKBOcVtpu9kZyHKgIMOkL61FscXwipyOGyagPvWXuLXGuvXzErVD6dBYUQuYKio1AlH477ANq91m5BfwX03TNpGUQ0fLftnNUiUDjbupDQ1FNHhTif0uX7Cf4yiKV/o/I+sUeDoWJhdmkRji4tyRtNvtgxt5HBLDA14s235Rw==",1,"L");
            //IMPRESION DE CODE-QR
            $fila=215;
            $this->GeneratePDF->Image('http://placehold.it/250x250',170,$fila,40,40,'png');

            $fila=263;
            $this->GeneratePDF->SetXY(4,$fila);
            // Mensaje inferior de la factura
            $this->GeneratePDF->Cell(210,1,utf8_decode('Este documento es una representación impresa de un CFDI'),0,0,"C");
            //No. de pagina
            $this->GeneratePDF->SetXY(200,$fila);
            $this->GeneratePDF->Cell(10,1,utf8_decode('Pág. ').$this->GeneratePDF->PageNo()." de 1",0,0,"C");


            /*foreach($data as $row)
            {
                $this->GeneratePDF->Cell($w[0],6,$row[0],'LR');
                $this->GeneratePDF->Cell($w[1],6,$row[1],'LR');
                $this->GeneratePDF->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
                $this->GeneratePDF->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
                $this->GeneratePDF->Cell($w[4],6,number_format($row[3]),'LR',0,'R');
                $this->GeneratePDF->Cell($w[5],6,number_format($row[3]),'LR',0,'R');
                $this->GeneratePDF->Ln();
            }*/
            //DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
            $this->GeneratePDF->Output('factura_1.pdf','I');
            $this->GeneratePDF->isFinished  = true;
            //$pdf->Output('factura_'.$_GET['id'].'.pdf','D');
        }catch(Exception $e){
            echo ($e->getMessage());    
        }
    }

}//Fin mifacturacion
