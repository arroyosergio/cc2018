<?php

/*
 * Registro de los asistentes colaboradores de un articulo.
 */
class Registroasistencia extends Controller {

    /*
     * Crea intencias del servicio.
     */
    function __construct() {
        parent::__construct();
        
        //Inicia sesion
        Session::init();
        $logged = Session::get("sesion");
        if (!$logged) {
            Session::destroy();
            header("location: index");
            exit;
        }
        
        //cargar las hojas de estilo
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'public/plugins/datapicker/bootstrap-datepicker.min.css',
            'views/registroasistencia/css/menu.css',
            'views/registroasistencia/css/registroasistencia.css'
            
        );
        
        //Carga los scripts
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datapicker/bootstrap-datepicker.min.js',
            'public/plugins/datapicker/bootstrap-datepicker.es.min.js',
            'public/plugins/timepicker/wickedpicker.js',
            'views/registroasistencia/js/registroasistencia.js'
        );
    }//Fin __construct

    /*
     * Renderiza la pagina
     */
    function index() {
        $this->view->datosArticulo = $this->getDatosArticulo($_GET['id']);
        $this->view->tablaAsistentes = $this->getAsistentesArticulo(TRUE);
        $this->view->monto = $this->getMonto(true);
        $this->view->render("registroasistencia/index");
    }

    /*
     * Recupera los datos del artículo.
     */
    function getDatosArticulo($id) {
        //Recupera los datos persistidos del articulo
        $responseDB = $this->model->get_datos_articulo($id);
        
        $articulo = array(
            'id' => $responseDB['artId'],
            'nombre' => $responseDB['artNombre'],
            'tipo' => $responseDB['artTipo'],
        );
        
        //Guardamos el id del articulo en la sesion para su posterior uso
        Session::set('idArticulo', $articulo['id']);
        
        //Armamos la seccion del documento html donde sera mostrada la informacion
        $response = '<div class="row">';
        $response .= '<div class="col-sm-4">';
        $response .= '<label for="">Id del art&iacute;culo:</label>';
        $response .= '<p class="form-static-control">' . $articulo['id'] . '</p>';
        $response .= '</div>';
        $response .= '<div class="col-sm-4">';
        $response .= '<label for="">Nombre del art&iacute;culo:</label>';
        $response .= '<p class="form-static-control">' . $articulo['nombre'] . '</p>';
        $response .= '</div>';
        $response .= '<div class="col-sm-4">';
        $response .= '<label for="">Modalidad de presentaci&oacute;n:</label>';
        $response .= '<p class="form-static-control">' . strtoupper($articulo['tipo']) . '</p>';
        $response .= '</div>';
        $response .= '</div>';

        return $response;
    }//Fin getDatosArticulo

    /*
     * Recupera a los asistentes parte del articulo
     */
    function getAsistentesArticulo($get = FALSE) {
        $idArticulo = Session::get('idArticulo');
        //Recuperamos la informacion persistida.
        $responseDB = $this->model->get_asistentes_articulo($idArticulo);
        
        
        //En caso de que existan, se crea una tabla html para mostrarla.
        if (!$responseDB) {
            $response = '';
        } else {
            $response = '';
            foreach ($responseDB as $asistente) {
                $response .= '<tr>';
                $response .= '<td class="text-center">' . $asistente['asi_nombre'] . '</td>';
                $response .= '<td class="text-center">' . $asistente['asi_institucion'] . '</td>';
                $response .= '<td class="text-center">' . $asistente['asi_tipo'] . '</td>';
                if ($this->getEstatusCambios(TRUE) == 'si') {
                    $response .= '<td class="text-center"><label id="editar|' . $asistente['asi_id'] . '" class="btn btn-link my-link"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</label></td>';
                    $response .= '<td class="text-center"><label id="borrar|' . $asistente['asi_id'] . '" class="btn btn-link my-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Borrar</label></td>';
                } else {
                    $response .= '<td class="text-center"></td>';
                    $response .= '<td class="text-center"></td>';
                }
                $response .= '</tr>';
            }
        }
        if ($get) {
            return $response;
        } else {
            echo $response;
        }
    }//Fin getAsistentesArticulo
    
    /*
     * Crea un nuevo asistente
     */
    function nuevoAsistente() {
        //Bajamos los datos del asistente de los parametros
        $nombre = $_POST['nombre-asistente'];
        $institucion = $_POST['institucion'];
        $tipoAsistente = $_POST['tipo-asistente'];
        
        //Verificamos la completitud de los datos, en caso afirmativo lo persistimos.
        if (!empty($nombre) && !empty($institucion) && !empty($tipoAsistente)) {
            //Armamos el arreglo para pasarlo como objeto.
            $asistente = array(
                'nombre' => $nombre,
                'institucion' => $institucion,
                'tipoAsistente' => $tipoAsistente
            );
            //Solicitamos a la capa de datos para que los persista.
            $responseDB = $this->model->regitro_asistente($asistente);
            
            //Se evalua la respuesta afirmativa
            if ($responseDB) {
                echo 'true';
            } else {
                echo 'error-query';
            }
        } else {
            echo 'error-null';
        }
    }//nuevoAsistente

    
    /*
     * Se recupera la iformacion de un asistente.
     */
    function getDatosAsistente() {
        //Se baja de los parametros de la petición el iidentificador del asistente.
        $id = $_POST['id'];
        $response = '';
        
        //En caso te contar con una identificador,se recupera la informacion, en caso negativo se notifica la ausencia
        if (!empty($id)) {
            $responseDB = $this->model->get_datos_asistente($id);
            
            if (!$responseDB) {
//                    No se encontro el registro
                echo 'error-registro';
            } else {
                $asistente = array(
                    'id' => $responseDB['asi_id'],
                    'nombre' => $responseDB['asi_nombre'],
                    'institucion' => $responseDB['asi_institucion'],
                    'tipo' => $responseDB['asi_tipo']
                );
                echo json_encode($asistente);
            }
        }
    }//Fin getDatosAsistente

    /*
     * Eliminar un asistente.
     */
    function borrarAsistente() {
        //Bajamos el parametro de la peticion
        $id = $_POST['id'];
        $response = '';
        
        //En caso de tener un id, lo eliminamos.
        if (!empty($id)) {
            $responseDB = $this->model->borrar_asistente($id);
            if ($responseDB) {
                $response = 'true';
            } else {
                $response = 'false';
            }
        } else {
            error_log('ERROR: borrar asistente id vacio');
            $response = 'error-query';
        }
        echo $response;
    }//Fin borrarAsistente

    /*
     * Actualizar los datos de un asistente
     */
    function updateAsistente() {
        //Bajamos los datos del asisten de la petición
        $id = $_POST['id'];
        $nombre = $_POST['nombre-asistente'];
        $institucion = $_POST['institucion'];
        $tipo = $_POST['tipo-asistente'];
        
        //Se revisa la completitud de los datos, se persiste, en caso contrario se notifica
        if (!empty($id) && !empty($nombre) && !empty($institucion) && !empty($tipo)) {
            $asistente = array(
                'id' => $id,
                'nombre' => $nombre,
                'institucion' => $institucion,
                'tipo' => $tipo
            );
            
            $responseDB = $this->model->update_asistente($asistente);
            
            if (!$responseDB) {
                echo 'error';
            } else {
                echo 'true';
            }
        } else {
            echo 'error-null';
        }
    }//Fin updateAsistente

    /*
     * Guarda los datos de un pago
     */
    function registroDatosPago() {
        //Verificacion de la completitud de los datos
        if (!empty($_POST['correo']) &&
                !empty($_POST['tipo-pago']) &&
                !empty($_POST['razon-social']) &&
                !empty($_POST['rfc']) &&
                !empty($_POST['calle']) &&
                !empty($_POST['numero']) &&
                !empty($_POST['colonia']) &&
                !empty($_POST['municipio']) &&
                !empty($_POST['estado']) &&
                !empty($_POST['codigo-postal']) &&
                !empty($_POST['banco']) &&
                !empty($_POST['info-deposito']) &&
                !empty($_POST['fecha']) &&
                !empty($_POST['hora']) &&
                !empty($_POST['minuto']) &&
                !empty($_POST['num-sucursal']) &&
                !empty($_POST['num-transaccion'])) {

            $idArticulo = Session::get('idArticulo');
            
            //Se agruman la inforacion del deposito
            $deposito = array(
                'banco' => $_POST['banco'],
                'tipoPago' => $_POST['tipo-pago'],
                'info' => $_POST['info-deposito'],
                'monto' => $this->getMonto(true),
                'fecha' => $_POST['fecha'],
                'hora' => "$_POST[hora]:$_POST[minuto]",
                'numSucursal' => $_POST['num-sucursal'],
                'numTransaccion' => $_POST['num-transaccion'],
                'idArticulo' => $idArticulo
            );

            //Se agrupan los datos de facturacion
            $facturacion = array(
                'correo' => $_POST['correo'],
                'razonSocial' => $_POST['razon-social'],
                'rfc' => $_POST['rfc'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'colonia' => $_POST['colonia'],
                'municipio' => $_POST['municipio'],
                'estado' => $_POST['estado'],
                'cp' => $_POST['codigo-postal'],
                'idArticulo' => $idArticulo
            );
            
            //Se valida el formato de correo.
            $correoValido = $this->comprobarCorreo($facturacion['correo']);
            
            //Se identifica la cantidad de asistentes.
            $asistentesGeneral = $this->model->get_total_asistentes($idArticulo, 'general');
            $asistentesPonente = $this->model->get_total_asistentes($idArticulo, 'ponente');
            $asistentesCoautor = $this->model->get_total_asistentes($idArticulo, 'coautor');

            //Se calcula el numero tota de asistentes.
            $totalAsistentes = $asistentesCoautor + $asistentesGeneral + $asistentesPonente;
            
            //Verificamos la presencia de asistentes
            if ($totalAsistentes > 0) {
                //Comprobamos la existencia de una ponente.
                $validacionPonente = $this->model->get_total_asistentes($idArticulo, 'ponente');
                
                if ($validacionPonente > 0) {
                    if ($correoValido) {
                        $responseDB = $this->model->registro_datos_deposito($deposito);
                        
                        if ($responseDB) {
                            $responseDB = $this->model->registro_datos_facturacion($facturacion);
                            
                            if ($responseDB) {
                                $this->updateEstatusAsistencia('si');
                                echo 'true';
                            } else {
                                echo 'false';
                            }
                        } else {
                            echo 'false';
                        }
                    } else {
                        echo 'error-correo';
                    }
                } else {
                    echo 'error-ponente';
                }
            } else {
                echo 'error-num-asistentes';
            }
        } else {
            echo 'error-null';
        }
    }//Fin registroDatosPago
    
    
    /*
     * Recupera la informacion del comprobante de pago.
     */
    function getComprobantePago() {
        //Identificamos el articulo
     	$idArticulo = Session::get('idArticulo');
     	$response = '<p>';
        
        //En caso de tener un numero de articulo, recuperamos los datos guardados.
        if (!empty($idArticulo)) {
     		$comprobante = $this->model->get_comprobante($idArticulo);
            
            //En caso de tener informacion, indicamos el archivo, en caso contrario, informamos la ausencia.
     		if (!$comprobante) {
     			$response .= "<li class='no-items'> Ningun archivo cargado! </li>";
     		}else{
     			$response .= '<a target="_blank" href="docs/'.$comprobante.'"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>';
     		}
     	}else{
     		error_log('Error no se esta mandando el id del art&iacute;culo : getCartaDerechos');
     	}
        
     	$response .= '</p>';
     	echo $response;
     }//Fin getComprobantePago

    /*
     * Actualiza los datos de un pago
     */
    function updateDatosPago() {
        $idArticulo = Session::get('idArticulo');
        
        $comprobante = $this->model->get_comprobante($idArticulo);
        
        //Comprobamos la completitud de los datos.
        if (!empty($_POST['correo']) &&
                !empty($_POST['tipo-pago']) &&
                !empty($_POST['razon-social']) &&
                !empty($_POST['rfc']) &&
                !empty($_POST['calle']) &&
                !empty($_POST['numero']) &&
                !empty($_POST['colonia']) &&
                !empty($_POST['municipio']) &&
                !empty($_POST['estado']) &&
                !empty($_POST['codigo-postal']) &&
                !empty($_POST['banco']) &&
                !empty($_POST['info-deposito']) &&
                !empty($_POST['fecha']) &&
                !empty($_POST['hora']) &&
                !empty($_POST['minuto']) &&
                !empty($_POST['num-sucursal']) &&
                !empty($_POST['num-transaccion'])) {

            //Agrupamos los datos del deposito.
            $deposito = array(
                'banco' => $_POST['banco'],
                'sucursal' => $_POST['num-sucursal'],
                'transaccion' => $_POST['num-transaccion'],
                'tipoPago' => $_POST['tipo-pago'],
                'info' => $_POST['info-deposito'],
                'monto' => $this->getMonto(true),
                'fecha' => $_POST['fecha'],
                'hora' => "$_POST[hora]:$_POST[minuto]",
                'idArticulo' => $idArticulo
            );
            
            //Agrupamos los datos de facturacion
            $facturacion = array(
                'correo' => $_POST['correo'],
                'razonSocial' => $_POST['razon-social'],
                'rfc' => $_POST['rfc'],
                'calle' => $_POST['calle'],
                'numero' => $_POST['numero'],
                'colonia' => $_POST['colonia'],
                'municipio' => $_POST['municipio'],
                'estado' => $_POST['estado'],
                'cp' => $_POST['codigo-postal'],
                'idArticulo' => $idArticulo
            );

            //Se comprueba el formato del correo
            $correoValido = $this->comprobarCorreo($facturacion['correo']);
            //Recuperamos los datos de los asistentes.
            $asistentesGeneral = $this->model->get_total_asistentes($idArticulo, 'general');
            $asistentesPonente = $this->model->get_total_asistentes($idArticulo, 'ponente');
            $asistentesCoautor = $this->model->get_total_asistentes($idArticulo, 'coautor');

            //calculo del total de asistentes.
            $totalAsistentes = $asistentesCoautor + $asistentesGeneral + $asistentesPonente;
            
            //Comprobamos, la existencia de asistentes.
            if ($totalAsistentes > 0) {
                
                //Aseguramos la presencia de un ponente.
                $validacionPonente = $this->model->get_total_asistentes($idArticulo, 'ponente');
                
                if ($validacionPonente > 0) {
                    if ($correoValido) {
                        //actualizamos los datos del deposito
                        $responseDB = $this->model->update_datos_deposito($deposito);
                        
                        if ($responseDB) {
                            //Actualizacion de los datos de facturacion
                            $responseDB = $this->model->update_datos_facturacion($facturacion);
                            
                            if ($responseDB) {
                                //Actuamos el nuevo estado de la asitencia
                                $this->updateEstatusAsistencia('si');
                                $this->model->update_estatus_cambios($idArticulo, 'no');
                                echo 'true';
                            } else {
                                echo 'false';
                            }
                        } else {
                            echo 'false';
                        }
                    } else {
                        echo 'error-correo';
                    }
                } else {
                    echo 'error-ponente';
                }
            } else {
                echo 'error-num-asistentes';
            }
        } else {
            echo 'error-null';
        }
    }//Fin updateDatosPago
    
    
    /*
     * Actualizacion del estado de la asistencia
     */
    function updateEstatusAsistencia($estatus) {
        $idArticulo = Session::get('idArticulo');
        $this->model->update_estatus_asitencia($idArticulo, $estatus);
    }//Fin updateEstatusAsistencia

    /*
     * Recupera el datos del estado de cambio permitod de la asistencia
     */
    function getEstatusCambios($get = FALSE) {
        $idArticulo = Session::get('idArticulo');
        $responseDB = $this->model->get_estatus_cambios($idArticulo);
        
        if ($get) {
            return $responseDB;
        } else {
            echo $responseDB;
        }
    }//Fin getEstatusCambios

    /*
     * Recupera el datos del estado del registro
     */
    function getEstatusRegistro($get = FALSE) {
        $idArticulo = Session::get('idArticulo');
        $responseDB = $this->model->get_estatus_registro($idArticulo);
        
        if ($get) {
            return $responseDB;
        } else {
            echo $responseDB;
        }
    }//Fin getEstatusRegistro

    /*
     * Calcula el monto a pagar
     */
    function getMonto($get = FALSE) {
        $idArticulo = Session::get('idArticulo');

        //Recupera laos asistentes.
        $asistentesPonente = $this->model->get_total_asistentes($idArticulo, 'ponente');
        $asistentesGeneral = $this->model->get_total_asistentes($idArticulo, 'general');
        $asistentesCoautor = $this->model->get_total_asistentes($idArticulo, 'coautor');
        
        //Identifica las fecha de pagos, para disponibilidad de despuestos
        $fecha_actual = new DateTime('now', new DateTimeZone('America/Mexico_City'));
        $fecha_actual->format('Y-m-d');
        $fechaAgosto = new DateTime(FECHADESCUENTOAUTORES, new DateTimeZone('America/Mexico_City')); 
        $monto = 0;
        
        if ($fecha_actual <= $fechaAgosto) {
            $monto = CUOTAAUTORDESCUENTO * $asistentesPonente;
            $monto += CUOTAPUBLICODESCUENTO * $asistentesGeneral;
            $monto += CUOTAPUBLICODESCUENTO * $asistentesCoautor;
        } else {
            $monto = CUOTAAUTOR * $asistentesPonente;
            $monto += CUOTAPUBLICO * $asistentesGeneral;
            $monto += CUOTAPUBLICO * $asistentesCoautor;
        }
        if ($get) {
            return $monto;
        } else {
            echo $monto;
        }
    }//Fin getMonto

    /*
     * Recupera la informacion del deposito
     */
    function getDatosDeposito() {
        $idArticulo = Session::get('idArticulo');
        $responseDB = $this->model->get_datos_deposito($idArticulo);
        
        //Confirmamos la existencia de datos previos, para armar la respuesta json o informar su ausencia.
        if (!$responseDB) {
            error_log("No hay registro de datos del deposito: fnc getDatosDeposito");
            $response = 'false';
        } else {
            $horaMinuto = explode(':', $responseDB['dep_hora']);
            $deposito = array(
                'id' => $responseDB['dep_id'],
                'banco' => $responseDB['dep_banco'],
                'sucursal' => $responseDB['dep_sucursal'],
                'transaccion' => $responseDB['dep_transaccion'],
                'tipoPago' => $responseDB['dep_tipo'],
                'info' => $responseDB['dep_info'],
                'fecha' => $responseDB['dep_fecha'],
                'hora' => $horaMinuto[0],
                'minuto' => $horaMinuto[1],
                'monto' => $responseDB['dep_monto']
            );
            $response = $deposito;
        }
        echo json_encode($response);
    }//Fin getDatosDeposito

    /*
     * Recuperamos la informacion de facturacion
     */
    function getDatosFacturacion() {
        //Recuperamos la informacion persistida.
        $idArticulo =  Session::get('idArticulo');
        $responseDB = $this->model->get_datos_facturacion($idArticulo);
        $response = '';
        
        //Comprobamos su existencia, para armanr recpuesta json, o notificar la ausencia.
        if (!$responseDB) {
            error_log("getDatosFacturacion: posible error con la consulta ó esta vacia");
            $response = 'false';
        } else {
            $facturacion = array(
                'id' => $responseDB['fac_id'],
                'razonSocial' => $responseDB['fac_razon_social'],
                'correo' => $responseDB['fac_correo'],
                'rfc' => $responseDB['fac_rfc'],
                'calle' => $responseDB['fac_calle'],
                'numero' => $responseDB['fac_numero'],
                'colonia' => $responseDB['fac_colonia'],
                'municipio' => $responseDB['fac_municipio'],
                'estado' => $responseDB['fac_estado'],
                'cp' => $responseDB['fac_cp'],
            );
            $response = $facturacion;
        }
        echo json_encode($response);
    }//Fin getDatosFacturacion

    /*
     * Comprobamos el correcto formato de una direccion de correo electronico.
     */
    function comprobarCorreo($correo) {
        $correoCorrecto = 0;
        if ((strlen($correo) >= 6) && (substr_count($correo, '@') == 1) && (substr($correo, 0, 1) != '@') && (substr($correo, strlen($correo) - 1, 1) != '@')) {
            if ((!strstr($correo, "'")) && (!strstr($correo, "\"")) && (!strstr($correo, "\\")) && (!strstr($correo, "\$")) && (!strstr($correo, " "))) {
                if (substr_count($correo, ".") >= 1) {
                    $term_dom = substr(strrchr($correo, '.'), 1);
                    if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom, '@'))) {
                        $antes_dom = substr($correo, 0, strlen($correo) - strlen($term_dom) - 1);
                        $caracter_ult = substr($antes_dom, strlen($antes_dom) - 1, 1);
                        if ($caracter_ult != '@' && $caracter_ult != '.') {
                            $correoCorrecto = 1;
                        }
                    }
                }
            }
        }
        if ($correoCorrecto) {
            return TRUE;
        } else {
            return FALSE;
        }
    }//Fin comprobarCorreo
    
    /*
     * Recibe el archivo del comprobante.
     */
    function updloadFile(){
		$idArticulo = Session::get('idArticulo');
        $file = $_FILES['archivo']['name'];
        
		// Check if file already exists
		if (!file_exists(DOCS.$idArticulo)) {
			mkdir(DOCS.$idArticulo, 0777, TRUE);
		}
		if (file_exists(DOCS .$idArticulo .'/'. $file)) {
			unlink(DOCS .$idArticulo .'/'. $file);
		}
		if (!move_uploaded_file($_FILES['archivo']['tmp_name'], DOCS .$idArticulo .'/'. $file)) {
		    echo 'error-subir-archivo';
		} else {
		    $file = $idArticulo . '/'.$file;
            $responseDB = $this->model->registro_comprobante($idArticulo, $file);
        
            $this->model->update_estatus_cambios($idArticulo, 'no');
            
            if ($responseDB) {            
                echo $idArticulo;
            } else {
                echo 'false';
            }
		}
    }//Fin updloadFile

}//Fin class Registroasistencia
