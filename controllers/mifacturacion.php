<?php

include('libs/CFDI/Node/Comprobante.php');
include('libs/CFDI/Node/Receptor.php');
include('libs/CFDI/Node/Emisor.php');
include('libs/CFDI/Node/Concepto.php');
  

use CFDI\CFDI;
use CFDI\Node\Concepto;
use CFDI\Node\Receptor;
use CFDI\Node\Emisor;

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
                    'archivoxml' => $responseDB['doc_factura_xml'],
                    'archivopdf' => $responseDB['doc_factura_pdf']
                );
                $response = $documentos;
            }
        }
        echo json_encode($response);
    }//Fin getDatosFacturacion
    
    /*
     *  
     */
    private function registro_documentos_factura($idArticulo, $nameXml, $namePdf ){
        
    }//Fin registro_documentos_factura
    
    /*
     * Generar la factura.
     */
     public function GenerarFactura(){
        $idArticulo = $_GET['id'];
         
        $path=DOCS.$idArticulo.'/';
        $nameXml='factura.xml';
        $namePdf='factura.pdf';
         
        $correcto = $this->GenerarCfdiXml($idArticulo, $path, $nameXml);
        if(!$correcto){
            return false;
        }
         
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
        //parametros para conexion al Webservice (URL de Pruebas)
        $wsdl_url = "https://staging.ws.timbox.com.mx/timbrado_cfdi33/wsdl";
        $wsdl_usuario = "AAA010101000";
        $wsdl_contrasena = "h6584D56fVdBbSmmnB";

        
        // convertir la cadena del xml en base64
        $ruta_xml = $path . $name;
        $documento_xml = file_get_contents($ruta_xml);
        $xml_base64 = base64_encode($documento_xml);        
        
        //crear un cliente para hacer la petición al WS
        $cliente = new SoapClient($wsdl_url, array(
            'trace' => 1,
            'use' => SOAP_LITERAL,
        ));

        //parametros para llamar la funcion timbrar_cfdi
        $parametros = array(
            "username" => $wsdl_usuario,
            "password" => $wsdl_contrasena,
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
            echo "# del error: " . $exception->getCode() . "\n";
            echo "Descripción del error: " . $exception->getMessage() . "\n";
            return false;
        }
    }//Fin TimbrarDocumento
    
    
    

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
            $this->GeneratePDF->SetFont('helvetica', '', 10);
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
            $this->GeneratePDF->Image("./public/images/utsoe_fac.png", 5,$fila,35,20 );
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
            $this->GeneratePDF->SetFont('helvetica', 'B', 9);
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
            //DESCARGA EL ARCHIVO EN EL NAVEGADOR CON EL NOMBRE DE Cica_art_articulo.PDF
            $this->GeneratePDF->Output('factura_1.pdf','I');
            //$pdf->Output('factura_'.$_GET['id'].'.pdf','D');
        }catch(Exception $e){
            echo ($e->getMessage());    
        }
    }

}//Fin mifacturacion