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
     * Generar la factura.
     */
     public function GenerarFactura(){
        $idArticulo = $_GET['id'];
         
        $this->GenerarCfdiXml($idArticulo);

         /*Persiste el nombre de los archivos de la factura y activa la bandera de factura previamente generada*/
         //$responseDB = registro_documentos_factura($idArticulo, $archivopdf, $archivoxml);
     }//Fin generarFactura
    

    /*
     * Genera el documento XML del CFI
     */
    private function GenerarCfdiXml($idArticulo){
        
        $path=DOCS.$idArticulo.'/';
        $name='factura.xml';
             
        $this->GenerarDatosComprobante($idArticulo);
        $this->GenerarDatosEmisor();
        $this->GenerarDatosReceptor($idArticulo);
        $this->GenerarDatosConceptos($idArticulo);    
        
        $xml=$this->GenerateCFDI->getXML();
        $this->GenerateCFDI->save($path, $name);
        
        header('Content-Type: text/xml');
        echo  ($xml);
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
        $fecha_actual = new DateTime('now', new DateTimeZone('America/Mexico_City'));
        $emisiondate = $fecha_actual->format('Y-m-d').'T'.$fecha_actual->format('h:i:s');
        
        //Generamos los datos del comprobante.
        $datos =[
            'Serie' => '',                          //opcional
            'Folio' => '',                          //opcional
            'Fecha' => $emisiondate,                //Cambiar requerido
            'FormaPago' => '03',                    //Requerido
            'NoCertificado' => '',                  //Requerido
            'CondicionesDePago' => 'I',             //opcional
            'Subtotal' => number_format($responseDB['dep_monto'],2,'.',''), //requerido
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
            'Rfc' => $responseDB['fac_rfc'],
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
    
}//Fin mifacturacion