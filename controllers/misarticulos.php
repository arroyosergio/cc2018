<?php

class Misarticulos extends Controller {

     function __construct() {
          parent::__construct();
          Session::init();
          $logged = Session::get("sesion");
          if (!$logged) {
               Session::destroy();
               header("location: index");
               exit;
          }
          $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/plugins/datatable/jquery.datatables.min.css',
            'views/misarticulos/css/misarticulos.css',
		    'views/misarticulos/css/menu.css'
          );
          $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'public/plugins/datatable/jquery.datatables.min.js',
			'views/misarticulos/js/misarticulos.js'
          );
     }

     function index() {
          $this->view->selectPaises = $this->selectPaises();
          $this->view->tblArticulos = $this->tablaArticulos();
          $this->view->render("misarticulos/index");
     }

     function selectPaises() {
          $responseDB = $this->model->get_paises();
          $select = '';
          $select = '<option value="">Selecciona uno</optioin>';
          foreach ($responseDB as $pais) {
               $select .= '<option value="' . $pais['pais_id'] . '">' . utf8_encode($pais['pais_nombre']) . '</optioin>';
          }
          return $select;
     }

     function getEstados() {
          $codigoPais = $_POST['codigo'];
          $responseDb = $this->model->get_estados($codigoPais);
          $select = '<option value="">Selecciona uno</optioin>';
          foreach ($responseDb as $estado) {
               $select .= '<option value="' . $estado['est_nombre'] . '">' . utf8_encode($estado['est_nombre']) . '</optioin>';
          }
          echo $select;
     }

     function getCiudades() {
          $codigoEstado = $_POST['codigo'];
          $responseDB = $this->model->get_ciudades($codigoEstado);
          $select = '<option value="">Selecciona uno</optioin>';
          foreach ($responseDB as $ciudad) {
               $select .= '<option value="' . $ciudad['Name'] . '">' . $ciudad['Name'] . '</optioin>';
          }
          echo $select;
     }

     function tablaArticulos() {
          $idAutor = $this->model->get_id_autor(Session::get('id'));
          $responseDB = $this->model->get_articulos($idAutor);
          $tabla = '';
          if (!$responseDB) {
               $tabla = '<h2 class="text-center">No tienes nig&uacute;n art&iacute;culo registrado</h2>';
          } else {
               $tabla .= '<table class="table table-striped table-hover" id="tbl-articulos">' .
				       '<col width="3%">'.
  					   '<col width="55%">'.
				   	   '<col width="18%">'.
				       '<col width="6%">'.
				       '<col width="6%">'.
				       '<col width="6%">'.
				       '<col width="6%">'.
                       '<thead>' .
                       '<tr>' .
                       '<th class="">Id</th>' .
                       '<th>Nombre</th>' .
                       '<th>&Aacute;rea tem&aacute;tica</th>' .
                       '<th class="text-center">Recibido</th>' .
                       '<th class="text-center">Dictaminado</th>' .
                       '<th class="text-center">Aviso de cambio</th>' .
                       '<th class="text-center">Edici&oacute;n</th>' .
                       '</tr>' .
                       '</thead>';
               $tabla .= '<tbody>';
               foreach ($responseDB as $articulo) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="td-tabla">' . $articulo['artId'] . '</td>';
                    $tabla .= '<td class="td-tabla">' . $articulo['artNombre'] . '</td>';

                    switch ($articulo['artAreaTematica']) {
                         case 'CAYS':
                              $articulo['artAreaTematica'] = 'Ciencias administrativas y sociales';
                              break;
                         case 'EFC':
                              $articulo['artAreaTematica'] = 'Experiencia en formaci&oacute;n CA';
                              break;
                         case 'CA':
                              $articulo['artAreaTematica'] = 'Ciencias agropecuarias';
                              break;
                         case 'CNYE':
                              $articulo['artAreaTematica'] = 'Ciencias naturales y exactas';
                              break;
                         case 'CIYT':
                              $articulo['artAreaTematica'] = 'Ciencias de ingenier&iacute;a y tecnolog&iacute;a';
                              break;
                         case 'E':
                              $articulo['artAreaTematica'] = 'Educaci&oacute;n';
                              break;
                    }
                    $tabla .= '<td class="td-tabla">' . $articulo['artAreaTematica'] . '</td>';
//                $tabla .= '<td class="text-center">' . $articulo['artRecibido'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $articulo['artRecibido'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $articulo['artDictaminado'] . '</td>';
                    $tabla .= '<td class="text-center td-tabla">' . $articulo['artAvisoCambio'] . '</td>';
                    if ($articulo['artAvisoCambio'] == 'si') {
                        $tabla .= '<td class="text-center"><a href="registroarticulo?id='.$articulo['artId'].'"><span class="glyphicon glyphicon-pencil"></span> Editar</a></td>'; 
                    }else{
                        $tabla .= '<td class="text-center td-tabla"></td>';
                    }
                    
                    /*if ($articulo['art_validacion_deposito'] == 'si') {
                        $tabla .= '<td class="text-center"><a href="gafete?id='.$articulo['artId'].'"><span class="glyphicon glyphicon-share-alt"></span> Generar</a></td>'; 
                    }else{
                        $tabla .= '<td class="text-center td-tabla"></td>';
                    }*/
                    
                    $tabla .= '</tr>';
               }
               $tabla .= '</tbody>';
               $tabla .= '</table>';
          }
          return $tabla;
     }

//    function registroArticulo() {
//        $nombreArticulo = strtoupper($_POST['nombre']);
//        $area = $_POST['area-tematica'];
//        $tipo = $_POST['tipo-articulo'];
//        $file = $_FILES['archivo']['name'];
//        if (empty($nombreArticulo) || empty($area) || empty($tipo)) {
//            echo 'error-null';
//        } else {
//            $existeArticulo = $this->model->existe_articulo($nombreArticulo);
//            if ($existeArticulo) {
//                echo 'error-articulo-repetido';
//            } else {
//                if (empty($file)) {
//                    echo 'error-archivo';
//                } else {
//                    $formatoArchivo = explode('.', $file);
//                    $formatoArchivo = end($formatoArchivo);
//                    if ($formatoArchivo != 'docx' && $formatoArchivo != 'doc') {
//                        echo 'error-formato-archivo';
//                    } else {
//                        $articulo = array(
//                            'nombre' => $nombreArticulo,
//                            'archivo' => $file,
//                            'area' => $area,
//                            'tipo' => $tipo
//                        );
//                        $responseDB = $this->model->registro_articulo($articulo);
//                        
//                        if (!$responseDB) {
//                            echo 'error-registro';
//                        } else {
//                            $idArticulo = $responseDB;
////                            $file = $idArticulo . $file;
////                            error_log($file);
////                            mkdir(DOCS.$idArticulo, 0777, TRUE);
//                            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], DOCS . $file)) {
//                                echo 'error-subir-archivo';
//                            } else {
//                                $idAutor = $this->model->get_id_autor(Session::get('id'));
//                                $responseDB = $this->model->registro_autor_articulo($idArticulo, $idAutor);
//                                echo $idArticulo;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//    }

     function updateArticulo() {
//        Valida que esta activo el cambio
          $response = '';
          $id = $_POST['id'];
          $cambio = $this->model->validacion_cambio_articulo($id);
          if ($cambio) {
               $nombre = $_POST['nombre'];
               $area = $_POST['area'];
               $tipo = $_POST['tipo'];
               if (!empty($nombre) && !empty($area) && !empty($tipo)) {
                    $responseDB = $this->model->update_articulo($id, $nombre, $area, $tipo);
                    if ($responseDB) {
                        $this->model->update_estatus_cambios($id, 'no');
                         $response = 'true';
                    } else {
                         $response = 'error-update';
                    }
               } else {
                    $response = 'error-null';
               }
          } else {
               $response = 'error-validacion';
          }
          echo $response;
     }

     function updateVersionArchivo() {
          $response = '';
          $idArticulo = $_POST['idArticulo'];
          if (!empty($idArticulo)) {
               $validacionCambio = $this->model->validacion_cambio_articulo($idArticulo);
               if ($validacionCambio) {
                    $file = $_FILES[0]['name'];
                    $formatoArchivo = explode('.', $file);
                    $formatoArchivo = end($formatoArchivo);
                    if ($formatoArchivo != 'docx' && $formatoArchivo != 'doc') {
                         echo 'error-formato-archivo';
                    } else {
                         $nuevaVersion = $this->model->get_version_articulo($idArticulo);
                         $nuevaVersion += 1;
                         $file = $nuevaVersion . '_' . $file;
                         if (!move_uploaded_file($_FILES[0]['tmp_name'], DOCS . $idArticulo . '/V' . $file)) {
                              echo 'error-subir-archivo';
                         } else {
                              $this->model->registro_version_articulo($idArticulo, $idArticulo . '/V' . $file);
                              $this->model->update_estatus_cambios($idArticulo, 'no');
                              echo 'true';
                         }
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

     function registroAutor() {
          $idArticulo = $_POST['id'];
          $nombre = $_POST['nombre'];
          $apellidoPaterno = $_POST['apellido-paterno'];
          $apellidoMaterno = $_POST['apellido-materno'];
          $ciudad = $_POST['ciudad'];
          $estado = $_POST['estado'];
          $pais = $_POST['pais'];
          $correo = $_POST['correo'];
          $gradoAcademico = $_POST['grado-academico'];
          $institucionProcedencia = $_POST['institucion-procedencia'];
          $tipoInstitucion = $_POST['tipo-institucion'];
          $asistenciaCica = $_POST['asistencia-cica'];
          if (!empty($nombre) && !empty($apellidoPaterno) && !empty($apellidoMaterno) && !empty($ciudad) && !empty($estado) && !empty($pais) && !empty($gradoAcademico) && !empty($institucionProcedencia) && !empty($tipoInstitucion) && !empty($asistenciaCica) && !empty($correo)) {
               if ($this->comprobarCorreo($correo)) {
                    $autor = array(
                        'nombre' => $nombre,
                        'apellidoPaterno' => $apellidoPaterno,
                        'apellidoMaterno' => $apellidoMaterno,
                        'institucionProcedencia' => $institucionProcedencia,
                        'ciudad' => $ciudad,
                        'estado' => $estado,
                        'pais' => $pais,
                        'correo' => $correo,
                        'gradoAcademico' => $gradoAcademico,
                        'tipoInstitucion' => $tipoInstitucion,
                        'asistenciaCica' => $asistenciaCica
                    );
                    $totalAutores = $this->model->get_total_autores($idArticulo);
                    if ($totalAutores < 4) {
//                  Verifica si el autor no esta registrado para ese articulo
                         $existeAutor = $this->model->existe_autor_articulo($idArticulo, $nombre, $apellidoPaterno, $apellidoMaterno);
                         if ($existeAutor) {
                              echo 'error-autor-registrado';
                         } else {
                              $responseDB = $this->model->registro_autor($autor);
                              if (!$responseDB) {
                                   echo 'error-registro';
                              } else {
                                   $responseDB = $this->model->registro_autor_articulo($idArticulo, $responseDB);
                                   $this->model->update_estatus_cambios($idArticulo, 'no');
                                   echo 'true';
                              }
                         }
                    } else {
                         echo 'error-numero-autores';
                    }
               } else {
                    echo 'error-correo';
               }
          } else {
               echo 'error-null';
          }
     }

     function getAutoresArticulo() {
          $idArticulo = $_POST['id'];
          $response = '';
          if (!empty($idArticulo)) {
               $responseDB = $this->model->get_autores_articulo($idArticulo);
               $cambio = $this->model->validacion_cambio_articulo($idArticulo);
               $tabla = '';
               foreach ($responseDB as $autor) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="hidden">' . $autor['autId'] . '</td>';
                    $tabla .= '<td><i class="glyphicon glyphicon-user"></i> ' . $autor['autNombre'] . ' ' . $autor['autApellidoPaterno'] . ' ' . $autor['autApellidoMaterno'] . '</td>';
                    $tabla .= '</td>';
                    if ($autor['autId'] != Session::get('idAutor')) {
                         if ($cambio) {
                              $tabla .= '<td class="text-right"><a href="#" id="editar|' . $autor['autId'] . '"><i class="glyphicon glyphicon-edit"></i> Editar</a></td>';
                              $tabla .= '<td class="text-right"><a href="#" id="borrar|' . $autor['autId'] . '"><i class="glyphicon glyphicon-trash"></i> Borrar</a></td>';
                         } else {
                              $tabla .= '<td class="text-right"></td>';
                              $tabla .= '<td class="text-right"></td>';
                         }
                    }
                    $tabla .= '</tr>';
               }
               $response = $tabla;
          }
          echo $response;
     }
	
     function get_validar_perfil() {
        $idAutor = Session::get('idAutor');
        $response = $this->model->get_validar_perfil($idAutor);		 
        echo $response;
		 return false;
     }	
	
	
     function get_show_AutoresArticulo() {
          $idArticulo = $_POST['id'];
          $response = '';
          if (!empty($idArticulo)) {
               $responseDB = $this->model->get_autores_articulo($idArticulo);
               //$cambio = $this->model->validacion_cambio_articulo($idArticulo);
               $tabla = '';
               foreach ($responseDB as $autor) {
                    $tabla .= '<tr>';
                    $tabla .= '<td class="hidden">' . $autor['autId'] . '</td>';
                    $tabla .= '<td><i class="glyphicon glyphicon-user"></i> ' . $autor['autNombre'] . ' ' . $autor['autApellidoPaterno'] . ' ' . $autor['autApellidoMaterno'] . '</td>';
                    $tabla .= '</td>';
                    $tabla .= '</tr>';
               }
               $response = $tabla;
          }

          echo $response;
     }
	
	function get_show_DetallesArticulo(){
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_detalles_articulo($idArticulo);
          $archivo = $this->model->get_ultima_version_archivo($idArticulo);
		  $archivo = explode('/', $archivo);
		  if(count($archivo)>1){
		  	$archivo = $archivo[1];
		  }else{
		  	$archivo = "Ningun archivo cargado...";
		  }
        
          $response = array(
              'nombre' => $responseDB['artNombre'],
              'area' => $responseDB['artAreaTematica'],
              'archivo' => $archivo,
              'tipo' => $responseDB['artTipo']
          );
		echo json_encode($response);
	}
	
     function getDetallesArticulo() {
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_detalles_articulo($idArticulo);
          $archivo = $this->model->get_ultima_version_archivo($idArticulo);
          $archivo = explode('/', $archivo);
          $archivo = $archivo[1];
          $response = array(
              'nombre' => $responseDB['artNombre'],
              'area' => $responseDB['artAreaTematica'],
              'archivo' => $archivo,
              'tipo' => $responseDB['artTipo'],
              'cambio' => $responseDB['artAvisoCambio'],
              'dictaminado' => $responseDB['artDictaminado']
          );

//        Llamado de las cartas del articulo
          $cartaSecion = $this->model->existe_carta_cesion($idArticulo);
          if (!$cartaSecion) {
               $response['cartaDerechos'] = '';
          } else {
               $cartaSecion = $cartaSecion['doc_carta_cesion_der'];
               $cartaSecion = explode('/', $cartaSecion);
               if(count($cartaSecion)>2){
               		$cartaSecion = $cartaSecion[1];
               }
               else {
               	$cartaSecion = $cartaSecion[0];
               }
               $response['cartaDerechos'] = $cartaSecion;
          }

          $cartaOriginalidad = $this->model->existe_carta_originalidad($idArticulo);
          if (!$cartaOriginalidad) {
               $response['cartaOriginalidad'] = '';
          } else {
               $cartaOriginalidad = $cartaOriginalidad['doc_carta_originalidad'];
               $cartaOriginalidad = explode('/', $cartaOriginalidad);
               if(count($cartaOriginalidad)>2){
               		$cartaOriginalidad = $cartaOriginalidad[1];
               }else{
               		$cartaOriginalidad = $cartaOriginalidad[0];
               }
               
               $response['cartaOriginalidad'] = $cartaOriginalidad;
          }
          echo json_encode($response);
     }

     function getDetallesAutor() {
          $idAutor = $_POST['id'];
          $response = '';
          if (!empty($idAutor)) {
               $responseDB = $this->model->get_detalles_autor($idAutor);
               if (!$responseDB) {
                    $response = 'error-consulta';
               } else {
                    $autor = array(
                        'nombre' => $responseDB['autNombre'],
                        'apellidoPaterno' => $responseDB['autApellidoPaterno'],
                        'apellidoMaterno' => $responseDB['autApellidoMaterno'],
                        'correo' => $responseDB['autCorreo'],
                        'institucion' => $responseDB['autInstitucionProcedencia'],
                        'ciudad' => $responseDB['autCiudad'],
                        'estados' => $this->model->get_estados($responseDB['autPais']),
                        'estado' => $responseDB['autEstado'],
                        'pais' => $responseDB['autPais'],
                        'gradoAcademico' => $responseDB['autGradoAcademico'],
                        'tipoInstitucion' => $responseDB['autTipoInstitucion'],
                        'asistenciaCica' => $responseDB['autAsistenciaCica']
                    );
                    $estadosPais = $this->model->get_estados($responseDB['autPais']);
                    $estados = array();
                    foreach ($estadosPais as $estado) {
                         array_push($estados, utf8_encode($estado['est_nombre']));
                    }
                    $autor['estados'] = $estados;
                    $response = $autor;
               }
          } else {
               $response = 'error-null';
          }
          echo json_encode($response);
     }

     function updateAutor() {
          $response = '';
          $idAutor = $_POST['id'];
          $nombre = $_POST['nombre'];
          $apellidoPaterno = $_POST['apellido-paterno'];
          $apellidoMaterno = $_POST['apellido-materno'];
          $ciudad = $_POST['ciudad'];
          $estado = $_POST['estado'];
          $pais = $_POST['pais'];
          $correo = $_POST['correo'];
          $gradoAcademico = $_POST['grado-academico'];
          $institucionProcedencia = $_POST['institucion-procedencia'];
          $tipoInstitucion = $_POST['tipo-institucion'];
          $asistenciaCica = $_POST['asistencia-cica'];
          if (!empty($idAutor) && !empty($nombre) && !empty($apellidoPaterno) && !empty($apellidoMaterno) && !empty($ciudad) && !empty($estado) && !empty($pais) && !empty($gradoAcademico) && !empty($institucionProcedencia) && !empty($tipoInstitucion) && !empty($asistenciaCica) && !empty($correo)) {
               if ($this->comprobarCorreo($correo)) {
                    $autor = array(
                        'id' => $idAutor,
                        'nombre' => $nombre,
                        'apellidoPaterno' => $apellidoPaterno,
                        'apellidoMaterno' => $apellidoMaterno,
                        'institucionProcedencia' => $institucionProcedencia,
                        'ciudad' => $ciudad,
                        'estado' => $estado,
                        'pais' => $pais,
                        'correo' => $correo,
                        'gradoAcademico' => $gradoAcademico,
                        'tipoInstitucion' => $tipoInstitucion,
                        'asistenciaCica' => $asistenciaCica
                    );
                    $responseDB = $this->model->update_autor($autor);
                    if (!$responseDB) {
                         $response = 'error-update';
                    } else {
                        $this->model->update_estatus_cambios(Session::get('idArticulo'), 'no');
                         $response = 'true';
                    }
               } else {
                    $response = 'error-correo';
               }
          } else {
               $response = 'error-null';
          }

          echo $response;
     }

     function borrarRegistroArticulo() {
          $idArticulo = $_POST['id'];
          $autores = $this->model->get_autores_articulo($idArticulo);
          $archivo = $this->model->get_nombre_archivo($idArticulo);
          $idAutores = array();
          $idUsuarioAutor = $this->model->get_id_autor(Session::get('id'));
          try {
               $this->model->borrar_relacion_autor_articulo($idArticulo);
               if (count($autores) > 1) {
                    foreach ($autores as $autor) {
                         if ($autor['autId'] != $idUsuarioAutor) {
                              $this->model->borrar_autor($autor['autId']);
                         }
                    }
               }
               $this->model->borrar_articulo($idArticulo);
               try {
                    unlink(DOCS . $archivo);
               } catch (Exception $exc) {
                    error_log('No se puede borrar el archivo');
                    error_log($exc->getTraceAsString());
               }
               echo 'true';
          } catch (Exception $exc) {
               echo 'false';
          }
     }

     function borrarAutor() {
          $idAutor = $_POST['idAutor'];
          $idArticulo = $_POST['idArticulo'];
          $response = '';
          if (!empty($idArticulo) && !empty($idAutor)) {
               $validacionCambio = $this->model->validacion_cambio_articulo($idArticulo);
               if ($validacionCambio) {
                    $responseBD = $this->model->borrar_autor($idAutor);
                    if (!$responseBD) {
                         $response = 'error-borrar';
                    } else {
                         $this->model->borrar_autor_articulo($idAutor, $idArticulo);
                         $response = 'true';
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

     function subirCartaOriginalidad() {
          $response = '';
          $idArticulo = $_POST['idArticulo'];
          if (!empty($idArticulo)) {
               $validacionCambio = $this->model->get_estatus_dictaminado($idArticulo);
               if ($validacionCambio) {
                    $existeCarta = $this->model->existe_carta_originalidad($idArticulo);
                    if ($existeCarta != FALSE) {
                         try {
                              unlink(DOCS . $existeCarta['doc_carta_originalidad']);
                         } catch (Exception $exc) {
                              error_log($exc->getTraceAsString());
                         }
                    }
                    $file = $_FILES[0]['name'];
                    $formatoArchivo = explode('.', $file);
                    $formatoArchivo = end($formatoArchivo);
                    if ($formatoArchivo != 'pdf') {
                         echo 'error-formato-archivo';
                    } else {
                         if (!move_uploaded_file($_FILES[0]['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                              echo 'error-subir-archivo';
                         } else {
                              $this->model->registro_carta_originalidad($idArticulo, $idArticulo . '/' . $file);
                              echo 'true';
                         }
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

     function subirCartaCesionDerechos() {
          $response = '';
          $idArticulo = $_POST['idArticulo'];
          if (!empty($idArticulo)) {
               $validacionCambio = $this->model->get_estatus_dictaminado($idArticulo);
               if ($validacionCambio) {
                    $existeCarta = $this->model->existe_carta_cesion($idArticulo);
                    if ($existeCarta != FALSE) {
                         try {
                              unlink(DOCS . $existeCarta['doc_carta_cesion_der']);
                         } catch (Exception $exc) {
                              error_log($exc->getTraceAsString());
                         }
                    }
                    $file = $_FILES[0]['name'];
                    $formatoArchivo = explode('.', $file);
                    $formatoArchivo = end($formatoArchivo);
                    if ($formatoArchivo != 'pdf') {
                         echo 'error-formato-archivo';
                    } else {
                         if (!move_uploaded_file($_FILES[0]['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                              echo 'error-subir-archivo';
                         } else {
                              $this->model->registro_carta_cesion_derechos($idArticulo, $idArticulo . '/' . $file);
                              echo 'true';
                         }
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

     function subirReciboPago() {
          $response = '';
          $idArticulo = $_POST['idArticulo'];
          if (!empty($idArticulo)) {
               $validacionCambio = $this->model->get_estatus_recibo_pago($idArticulo);
               if (!$validacionCambio) {
                    $existeRecibo = $this->model->existe_recibo_pago($idArticulo);
                    if ($existeRecibo != FALSE) {
                         try {
                              
                         } catch (Exception $exc) {
                              error_log($exc->getTraceAsString());
                         }
                    }
                    $file = $_FILES[0]['name'];
                    $formatoArchivo = explode('.', $file);
                    $formatoArchivo = end($formatoArchivo);
                    if ($formatoArchivo != 'pdf') {
                         echo 'error-formato-archivo';
                    } else {
                         if (!move_uploaded_file($_FILES[0]['tmp_name'], DOCS . $idArticulo . '/' . $file)) {
                              echo 'error-subir-archivo';
                         } else {
                              $this->model->registro_recibo_pago($idArticulo, $idArticulo . '/' . $file);
                              echo 'true';
                         }
                    }
               } else {
                    $response = 'error-validacion';
               }
          } else {
               $response = 'error-null';
          }
          echo $response;
     }

     function getCartas() {
          $idArticulo = $_POST['id'];
          $cartaOriginalidad = $this->model->existe_carta_originalidad($idArticulo);
          $cartaDerechos = $this->model->existe_carta_cesion($idArticulo);
          $response = '<div class="col-sm-6">';
          $response .= '<h5 class="text-primary">Carta de originalidad:</h5>';
          if (!$cartaOriginalidad) {
               $response .= '<p>No hay ningún documento</p>';
          } else {
               $cartaOriginalidad = explode('/', $cartaOriginalidad['doc_carta_originalidad']);
               if(count($cartaOriginalidad)>1)
               		$response .= '<p>' . $cartaOriginalidad[1] . '</p>';
               else 
               	$response .= '<p>' . $cartaOriginalidad[0] . '</p>';
          }
          $response .= '</div>';
          
          $response .= '<div class="col-sm-6">';
          $response .= '<h5 class="text-primary">Carta de cesión de derechos:</h5>';
          if (!$cartaDerechos) {
               $response .= '<p>No hay ningún documento</p>';
          } else {
               $cartaDerechos = explode('/', $cartaDerechos['doc_carta_cesion_der']);
               if(count($cartaDerechos)>1)
               	$response .= '<p>' . $cartaDerechos[1] . '</p>';
               else 
               	$response .= '<p>' . $cartaDerechos[0] . '</p>';
          }
          $response .= '</div>';
          echo $response;
     }

     function getEstatusCartas() {
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_estatus_cartas($idArticulo);
          echo $responseDB;
     }

     function getEstatusRecibo() {
          $idArticulo = $_POST['id'];
          $responseDB = $this->model->get_estatus_recibo($idArticulo);
          echo $responseDB;
     }

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
     }
     //========================CARTA DE ACEPTACION==============================
     function getCartaAceptacion() {
     	$idArticulo = $_POST['id'];
     	$responseDB = $this->model->existe_carta_aceptacion($idArticulo);
     	$response = array(
     			'archivo' => $responseDB['doc_carta_aceptacion']
     	);
     	echo json_encode($response);
     }
     //=======================CARTA DE ACEPTACION==================================
     //=======================VALIDA QUE EL ARTICULO SE HALLA PRESENTADO PARA GENERAR CONSTANCIA===========
     function validarPresentacionArt() {
     	$idArticulo = $_GET['id'];
     	$response = $this->model->validarPresentacionArt($idArticulo);
    	echo $response;
     }
}
