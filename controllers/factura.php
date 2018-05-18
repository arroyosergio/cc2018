<?php

class factura extends Controller {
    
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
            'views/factura/css/factura.css',
            'views/factura/css/menu.css'
        );
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
            "views/factura/js/factura.js",
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
        $this->view->render('factura/index');
    }//Fin index
    
    /*
     * Recupera los depositos validados del autor.
     */
    public function getDepositosValidados() {
          //VARIABLE PARA LA DEFINICION DE TIPO DE MONEDA
          setlocale(LC_MONETARY, 'en_US');
          $responseDB = $this->model->get_depositos_validados();
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No tienes nig&uacute;n deposito validado.</h2>';
          } else {
               $tabla .= '<table class="table table-striped table-hover" id="tbl-depositos">' .
				      '<col width="4%">'.
  					  '<col width="47%">'.
				   	  '<col width="15%">'.
				      '<col width="8%">'.
                      '<col width="8%">'.
                      '<col width="6%">'.
                      '<col width="6%">'.
                      '<col width="6%">'.
                       '<thead>' .
                       '<tr>' .
                       '<th class="">Art&iacute;culo</th>' .
                       '<th>Nombre del art&iacute;culo</th>' .
                       '<th class="text-center">RFC</th>' .
                       '<th class="text-center">Fecha dep.</th>' .
                       '<th class="text-center">Monto</th>' .
                       '<th class="text-center">Fact. generada</th>' .
                       '<th class="text-center">Fact. correcta</th>' .
                       '<th class="text-center"></th>' .
                       '</tr>' .
                       '</thead>';
               $tabla .= '<tbody>';
               foreach ($responseDB as $deposito) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="td-tabla">' . $deposito['artid'] . '</td>';
                    $tabla .= '<td class="td-tabla">' . $deposito['artNombre'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $deposito['fac_rfc'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $deposito['dep_fecha'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . number_format( $deposito['dep_monto'],2) . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $deposito['doc_factura_creada'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla"><input type="checkbox" id="'.$deposito['artid'].'" name="doc_fact_correcta" '.($deposito["doc_fact_correcta"]=='si'?'checked':'').'/></td>';                    
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
                    "monto" => number_format($responseDB['dep_monto'],2),
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
 
    //====================================================================
    //FUNCION PARA ACTUALIZAR EL ESTADO DEL CHECKBOX DE FACTURA CORRECTA
    //====================================================================
    public function fncFactCorrecta(){
        $response=$this->model->fnc_fact_correcta($_POST['id'],$_POST['campo'],$_POST['estado']);  
        echo $response;
    }

    
    function subirFacturaPdf() {
        $response = '';
        $idArticulo = $_POST['id-art-pdf'];
        if (!empty($idArticulo)) {
            $existeFacturaPDF = $this->model->existe_factura_pdf($idArticulo);
            if ($existeFacturaPDF['doc_factura_pdf'] != NULL) {
                try {
                    if (file_exists(DOCS . $idArticulo.'/' .$existeFacturaPDF['doc_factura_pdf'])){
                        unlink(DOCS . $idArticulo .'/' . $existeFacturaPDF['doc_factura_pdf']);
                    }
                } catch (Exception $exc) {
                    error_log($exc->getTraceAsString());
                }
            }
            $file = $_FILES['archivo_pdf']['name'];
            $formatoArchivo = explode('.', $file);
            $formatoArchivo = end($formatoArchivo);
            if ($formatoArchivo != 'pdf') {
                echo 'error-formato-archivo';
            } else {
                
                if (!move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                    echo 'error-subir-archivo';
                } else {
                    $this->model->registro_factura_pdf($idArticulo, $idArticulo . '/' . $file);
                    echo 'true';
                }
            }
             
        } else {
             $response = 'error-null';
        }
        echo $response;
   }    


   function subirFacturaXml() {
    $response = '';
    $idArticulo = $_POST['id-art-xml'];
    if (!empty($idArticulo)) {
        $existeFacturaXML = $this->model->existe_factura_xml($idArticulo);
        if ($existeFacturaXML['doc_factura_xml'] != NULL) {
            try {
                if (file_exists(DOCS . $idArticulo.'/' .$existeFacturaXML['doc_factura_xml'])){
                    unlink(DOCS . $idArticulo .'/' . $existeFacturaXML['doc_factura_xml']);
                }
            } catch (Exception $exc) {
                error_log($exc->getTraceAsString());
            }
        }
        $file = $_FILES['archivo_xml']['name'];
        $formatoArchivo = explode('.', $file);
        $formatoArchivo = end($formatoArchivo);
        if ($formatoArchivo != 'xml') {
            echo 'error-formato-archivo';
        } else {
            
            if (!move_uploaded_file($_FILES['archivo_xml']['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                echo 'error-subir-archivo';
            } else {
                $this->model->registro_factura_xml($idArticulo, $idArticulo . '/' . $file);
                echo 'true';
            }
        }
         
    } else {
         $response = 'error-null';
    }
    echo $response;
}    


}//Fin factura