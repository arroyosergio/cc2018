<?php
//NAMESPACE DE LA LIBRERIA PARA GENERER CFDI
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
     * Generar la factura.
     */
     public function generarFactura(){
        $idArticulo = $_GET['id'];
        $this->GenerarCfdiXml($idArticulo);
        //GenerarCfdiXml($idArticulo);
         /*Persiste el nombre de los archivos de la factura y activa la bandera de factura previamente generada*/
         //$responseDB = registro_documentos_factura($idArticulo, $archivopdf, $archivoxml);
     }//Fin generarFactura
    

    private function GenerarCfdiXml ($idArticulo){
        $path=DOCS.$idArticulo.'/';
        $name='factura.xml';
        $this->GenerateCFDI->addData([
            'Serie' => 'A',
            'Folio' => 'A0101',
            'Fecha' => '2017-06-17T03:00:00',
            'FormaPago' => '01',
            'NoCertificado' => '00000000000000000000',
            'CondicionesDePago' => '',
            'Subtotal' => '',
            'Descuento' => '0.00',
            'Moneda' => 'MXN',
            'TipoCambio' => '1.0',
            'Total' => '',
            'TipoDeComprobante' => 'I',
            'MetodoPago' => 'PUE',
            'LugarExpedicion' => '64000',
        ]);  
        
        $this->GenerateCFDI->add(new Emisor([
            'Rfc' => 'XAXX010101000',
            'Nombre' => 'Florería SA de CV',
            'RegimenFiscal' => '601',
        ]));
        $this->GenerateCFDI->add(new Receptor([
            'Rfc' => 'XEXX010101000',
            'Nombre' => 'Orlando Charles',
            'ResidenciaFiscal' => 'USA',
            'NumRegIdTrib' => '121585958',
            'UsoCFDI' => 'G01',
        ]));
        $this->GenerateCFDI->add(new Concepto([
            'ClaveProdServ' => '10317352',
            'NoIdentificacion' => 'UT421510',
            'Cantidad' => '12',
            'ClaveUnidad' => 'H87',
            'Unidad' => 'Pieza',
            'Descripcion' => 'Arreglo de 12 tulipanes rojos recién cortados',
            'ValorUnitario' => '66.00',
            'Importe' => '792.00',
            'Descuento' => '5.00',
        ]));
        $xml=$this->GenerateCFDI->getXML();
        $this->GenerateCFDI->save($path, $name);
        //header('Content-Type: text/xml');
        //echo  ($xml);
    
    }
   

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