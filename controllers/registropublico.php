<?php

/*
 * Resgistrar asistencia de publico en general.
 */
class registropublico extends Controller {

    /*
     * Crea un objeto para registrar un asistente.
     */
    function __construct() {
        parent::__construct();
        Session::init();
        $logged = 
        Session::get("sesion");
        
        //Inicia sesion como visitante para poder utilizar atributos de sesion
        if (!$logged) {
            Session::set('sesion', TRUE);
            Session::set('id', 'visitante');
            Session::set('perfil', 'visitante');
            Session::set('usuario', 'visitante');	
        }
       
        //Carga las hojas de estilo.
        $this->view->css = array(
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/registroarticulo/css/registroarticulo.css',
		    'views/registropublico/css/menu.css',
            'public/plugins/datapicker/bootstrap-datepicker.min.css',
        );
        
        //Carga de los scripts
        $this->view->js = array(
            'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datapicker/bootstrap-datepicker.min.js',
            'public/plugins/datapicker/bootstrap-datepicker.es.min.js',
            'views/registropublico/js/registropublico.js'
        );
    }//Fin __construct

    /*
     * Renderizar la pagina.
     */
    function index() {
    	$this->view->tablaAsistentes = $this->getAsistentesPublico(true);
    	$this->view->monto = $this->getMonto(true);
        $this->view->render("registropublico/index");
    }//Fin index

    /*
     * Crear y persiste un nuevo asistente
     */
    function nuevoAsistente() {
        $nombre = $_POST['nombre-asistente'];
        $institucion = $_POST['institucion'];
        $tipoAsistente = $_POST['tipo-asistente'];
        $idRegistroPublico = Session::get('idRegistroPublico');
        
        //Valida la existencia de no mas 10 asistentes.
        if($idRegistroPublico){
            
        	$arNombre= explode(" ", $nombre);
        	$nombre_sin_esp="";
            
            foreach($arNombre as $datos => $valor){ 
                 $nombre_sin_esp.=$valor; 
            } 
            
        	$asist_encontrado=$this->model->get_buscar_asistente(strtoupper($nombre_sin_esp));
        	if($asist_encontrado){
        		echo 'error-replicado';
        		return;
        	}
            
        	$cantRegistro=$this->model->get_total_asistentes_pub($idRegistroPublico);
        	if($cantRegistro >10){
        		echo 'error-cantidad';
        		return;
        	}
        }

        //Valida la completitud de los asistentes.
        if (!empty($nombre)  && !empty($tipoAsistente)) {
            
            
            //En caso de no existir un identificador para el registro, se crea.
            if(!$idRegistroPublico){
            	$responseDB = $this->model->nuevo_registro();
                $idRegistroPublico = Session::get('idRegistroPublico');
            }
            
            //Persistimos los datos del asistente.
            $asistente = array(
            		'nombre' => $nombre,
            		'institucion' => $institucion,
            		'tipoAsistente' => $tipoAsistente,
            		'reg_id' => $idRegistroPublico
            );
            
            $responseDB = $this->model->regitro_asistente($asistente);
            
            if ($responseDB) {
                echo 'true';
            } else {
                echo 'error-query';
            }
        } else {
             echo 'error-null';
        }
    }//Fin nuevoAsistente
    
    /*
     * Trae las información de los asistente del registro publico.
     */
    function getAsistentesPublico($get = FALSE) {
        $idRegistroPublico = Session::get('idRegistroPublico');

        
        //Verificamos la existen de un identificador del registro publico, para solicitar los asistentes
        //para presentarlos
    	if(!$idRegistroPublico){
    		$response = '';
    	}else{
            
            
    		$responseDB = $this->model->get_asistentes_publico($idRegistroPublico);
        
    		if (!$responseDB) {
    			$response = '';
    		} else {
    			$response = '';
    			foreach ($responseDB as $asistente) {
    				$response .= '<tr>';
    				$response .= '<td class="text-center">' . $asistente['reg_nombre'] . '</td>';
    				$response .= '<td class="text-center">' . $asistente['reg_institucion'] . '</td>';
    				$response .= '<td class="text-center">' . $asistente['reg_tipo'] . '</td>';
    				$response .= '<td class="text-center"><label id="editar|' . $asistente['id'] . '" class="btn btn-link"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</label></td>';
    				$response .= '<td class="text-center"><label id="borrar|' . $asistente['id'] . '" class="btn btn-link my-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Borrar</label></td>';
    				$response .= '</tr>';
    			}
    		}
    	}
        
        if ($get) {
            return $response;
        } else {
            echo $response;
        }
    }//Fin getAsistentesPublico

    /*
     * Recupera la información de un asistente en particular
     */
    function getDatosAsistente() {
        $id = $_POST['id'];
        $response = '';
        
        if (!empty($id)) {
            $responseDB = $this->model->get_datos_asistente($id);
            if (!$responseDB) {
                echo 'error-registro';
            } else {
                $asistente = array(
                    'id' => $responseDB['id'],
                    'nombre' => $responseDB['reg_nombre'],
                    'institucion' => $responseDB['reg_institucion'],
                    'tipo' => $responseDB['reg_tipo']
                );
                echo json_encode($asistente);
            }
        }
    }//Fin getDatosAsistente

    /*
     * Borra un asistente del registro publico.
     */
    function borrarAsistente() {
        $id = $_POST['id'];
        $response = '';
        
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
    }//borrarAsistente

    /*
     * Actualiza los datos de un asistente.
     */
    function updateAsistente() {
        //Baja los datos del asistente de la peticion.
        $id = $_POST['id-asistente'];
        $nombre = $_POST['nombre-edit'];
        $institucion = $_POST['institucion'];
        $tipo = $_POST['tipo-asistente'];
        
        //Verifica la completitud
        if (!empty($id) && !empty($nombre) && !empty($institucion) && !empty($tipo)) {
            //Persistimos los cambios.
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
     * Calcula el monto a pagar
     */ 
    function getMonto($get = FALSE) {
        
        $idRegistroPublico = Session::get('idRegistroPublico');
        //En caso de existir identificador para el registro, calculamos el monto.
    	if(!$idRegistroPublico ){
    		$monto = 0;
    	}else{
            //Recuperamos las fechas y el numero de asistentes.
    		$asistentesGeneral = $this->model->get_total_asistentes($idRegistroPublico, 'general');
    		$fecha_actual = new DateTime('now', new DateTimeZone('America/Mexico_City'));
    		$fecha_actual->format('Y-m-d');
    		$fechaSeptiembre = new DateTime(FECHALIMITEDESCUENTOGENERAL, new DateTimeZone('America/Mexico_City'));  
    		$monto = 0;
            
            //IDentificamos el costo a aplicar, con o sin descuento
    		if ($fecha_actual <= $fechaSeptiembre) {
    			$monto += CUOTAPUBLICODESCUENTO * $asistentesGeneral;;
    		} else {
    			$monto += CUOTAPUBLICO * $asistentesGeneral;
    		}
    	}
        if ($get) {
            return $monto;
        } else {
            echo $monto;
        }
    }//Fin getMonto
    
    
    /*
     * Verificar el rango de las fechas
     */ 
    function validarFechas($fecha)
    {
    	$formatoValido=false;
        $dmy= explode("/", $fecha);
        if(count($dmy)>2)
        {
        	If (checkdate ($dmy[1],$dmy[0],$dmy[2]))
        	$formatoValido= true;
        	else
        		$formatoValido= false;
        }else{
        	$formatoValido= false;
        }
        return $formatoValido;
    }//Fin validarFechas
    
    /*
     * Comprueba el correcto formato del correo.
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
     * Guarda una nuevo registro de pago de asistentes publicos.
     */
    function registroDatosPago() {
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
            
            $idRegistroPublico = Session::get('idRegistroPublico');
                
            $dmy= explode("/", $_POST['fecha']);
     	    $dt = new DateTime($dmy[2].'-'.$dmy[1].'-'.$dmy[0], new DateTimeZone('America/Mexico_City'));

            //Agrupamos los datos del deposito.
            $deposito = array(
                'banco' => $_POST['banco'],
                'sucursal' => $_POST['num-sucursal'],
                'transaccion' => $_POST['num-transaccion'],
                'tipoPago' => $_POST['tipo-pago'],
                'info' => $_POST['info-deposito'],
                'monto' => $this->getMonto(true),
                'fecha' => $dt->format('Y-m-d'),
                'hora' => "$_POST[hora]:$_POST[minuto]",
                'reg_id' => $idRegistroPublico
            );
            error_log(print_r($deposito,true));

            //Agrupamos los datos de factuacion
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
                'reg_id' => $idRegistroPublico
            );
            
            //Recuperamos los asistentes de la resgistro de asistencia
            $correoValido = $this->comprobarCorreo($facturacion['correo']);
            $asistentesGeneral = $this->model->get_total_asistentes($idRegistroPublico, 'general');
            $totalAsistentes =  $asistentesGeneral;
            
            //Verificamos la exstencia de asistentes.
            if ($totalAsistentes > 0) {
                //Aseguramiento la presencia de correo electronico
                if ($correoValido) {
                    //Persistencia de los datos de deposito
                    $responseDB = $this->model->registro_datos_deposito($deposito);
                    if ($responseDB) {
                        //Persistencia de los datos de facturacion
                        $responseDB = $this->model->registro_datos_facturacion($facturacion);
                        if ($responseDB) {
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
                echo 'error-num-asistentes';
            }
        } else {
            echo 'error-null';
        }
    }//Fin registroDatosPago

    
    function updloadFile(){
	
        $idRegistroPublico = Session::get('idRegistroPublico');
        $file = $_FILES['archivo']['name'];
		
		if (file_exists(DOCSDEPOSITOSPUBLICOS .$idRegistroPublico .'_'. $file)) {
			unlink(DOCSDEPOSITOSPUBLICOS .$idRegistroPublico .'_'. $file);
		}
        
		if (!move_uploaded_file($_FILES['archivo']['tmp_name'], DOCSDEPOSITOSPUBLICOS .$idRegistroPublico .'_'. $file)) {
		    echo 'error-subir-archivo';
		} else {
            
            //Guardar el nombre del comporobante en la bd
            $responseDB = $this->model->registro_comprobante($idRegistroPublico, $idRegistroPublico .'_'. $file);
        
            if ($responseDB) {            
                echo $idRegistroPublico;

                //Destruir sesion si es visitante
                $perfil = Session::get("perfil");
                //Inicia sesion como visitante para poder utilizar atributos de sesion
                if ($perfil === 'visitante') {
                    Session::init();
                    Session::destroy();	
                }else{
                    //Eliminar el identificador del registro publico si es autor
                     unset($_SESSION['idRegistroPublico']);
                }
            } else {
                echo 'false';
            }
		}
	}
    
    /*
        
     */

    
    /************************************************************************************/
    /*************************************  No se utilizan ******************************/
    /************************************************************************************/
    
    /* 
    function updateDatosPago() {
        //Verficamos
    	if(!$this->validarFechas($_POST['fecha'])){
    		echo 'error-formato-fecha';
    		return ;
    	}
        
    	$dmy= explode("/", $_POST['fecha']);
     	$fechaDeposito = new DateTime($dmy[1].'/'.$dmy[0].'/'.$dmy[2]);

        if (!empty($_POST['correo']) && !empty($_POST['banco']) && !empty($_POST['info-deposito'])  && !empty($_POST['fecha'])) {
            $deposito = array(
                'banco' => $_POST['banco'],
                'sucursal' => $_POST['num-sucursal'],
                'transaccion' => $_POST['num-transaccion'],
                'tipoPago' => $_POST['tipo-pago'],
                'info' => $_POST['info-deposito'],
                'monto' => $this->getMonto(true),
                'fecha' => $fechaDeposito->format('Y-m-d'),
                'hora' => "$_POST[hora]:$_POST[minuto]",
                'comprobante' => Session::get('idRegistroPublico')."_deposito.pdf",
                //$_FILES['comprobante']['name'],
                'reg_id' => Session::get('idRegistroPublico')
            );
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
                'reg_id' => Session::get('idRegistroPublico')
            );
            $extencion = explode('.', $deposito['comprobante']);
            $extencion = end($extencion);
            $correoValido = $this->comprobarCorreo($facturacion['correo']);
            $asistentesGeneral = $this->model->get_total_asistentes(Session::get('idRegistroPublico'), 'general');
            $totalAsistentes =  $asistentesGeneral ;
            if ($totalAsistentes > 0) 
            {
                    if ($correoValido) 
                    {
                         if (strtolower($extencion) != 'pdf') 
                         {
                                echo 'error-formato';
                         } else {
                                if (!move_uploaded_file($_FILES['comprobante']['tmp_name'], DOCS . 'dep_publico/' . Session::get('idRegistroPublico')."_deposito.pdf")) 
                                {
                                    echo 'error-subir-archivo';
                                    
                                }else
                                {
                                	$responseDB = $this->model->registro_datos_deposito($deposito);
                                	if ($responseDB) {
                                		$responseDB = $this->model->registro_datos_facturacion($facturacion);
                                		if ($responseDB) 
                                		{
                                			Session::destroy();
                                			echo 'true';
                                		} else {
                                			//                                   Error al insertar los datos de facturacion
                                			echo 'false';
                                		}
                                	} else {
                                		//                              Error al consulta datos de deposito
                                		echo 'false';
                                	}                                	
                                    $deposito['comprobante'] =  Session::get('idRegistroPublico')."_deposito.pdf";
                                }
                        }
                  } else {
//                    Correo invalido
                        echo 'error-correo';
                  }
            } else {
//                    Error ningun asistente registrado
                echo 'error-num-asistentes';
            }
        } else {
//               Error de datos incompletos
            echo 'error-null';
        }
    }

    
    function updateEstatusAsistencia($estatus) {
        $this->model->update_estatus_asitencia(Session::get('idArticulo'), $estatus);
    }

    function getEstatusCambios($get = FALSE) {
        $responseDB = $this->model->get_estatus_cambios(Session::get('idArticulo'));
        if ($get) {
            return $responseDB;
        } else {
            echo $responseDB;
        }
    }
    
    function getEstatusRegistro($get = FALSE) {
        $responseDB = $this->model->get_estatus_registro(Session::get('idRegistroPublico'));
        if ($get) {
            return $responseDB;
        }else{
            echo $responseDB;
        }
    }

    function getDatosFacturacion() {
        $responseDB = $this->model->get_datos_facturacion(Session::get('idArticulo'));
        $response = '';
        if (!$responseDB) {
//            Error, consulta vacia 
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
    }
    
    function getDatosDeposito() {
        
        $responseDB = $this->model->get_datos_deposito(Session::get('idArticulo'));
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
                'monto' => $responseDB['dep_monto'],
                'comprobante' => $responseDB['dep_comprobante']
            );
            $response = $deposito;
        }

        echo json_encode($response);
    }
    */	
}//Fin class registropublico
