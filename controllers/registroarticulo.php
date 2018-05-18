<?php

class Registroarticulo extends Controller {

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
            'views/registroarticulo/css/registroarticulo.css',
		    'views/registroarticulo/css/menu.css'			
        );
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'views/registroarticulo/js/registroarticulo.js'
        );
    }

    function index() {
		if(isset($_GET['id'])){
			$this->view->detalleArticulo= json_decode($this->get_det_art_autor(Session::get('idAutor'),$_GET['id']));
		}else{
			$response = json_encode(array(
				'id'     => "new",
				'nombre' => "",
				'area' => "",
				'archivo' => "",
				'tipo' => ""
			));
			$this->view->detalleArticulo=json_decode($response);
		}
        $this->view->selectPaises = $this->selectPaises();
        $this->view->render("registroarticulo/index");
    }

    function selectPaises() {
        $responseDB = $this->model->get_paises();
        $select = '';
        $select = '<option value="">Selecciona uno</option>';
        foreach ($responseDB as $pais) {
            $select .= '<option value="' . $pais['pais_id'] . '">' . utf8_encode($pais['pais_nombre']) . '</optioin>';
        }
        return $select;
    }

    function getEstados() {
        $codigoPais = $_POST['codigo'];
        $responseDb = $this->model->get_estados($codigoPais);
        $select = '<option value="">Selecciona uno</option>';
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

	
	function updloadFile(){
	
		$idArticulo = $_POST['id-articulo-file'];
        $file = $_FILES['archivo']['name'];
		//VALIDA LA NUEVA VERSION DEL ARTICULO
		$version = $this->model->get_version_articulo($idArticulo);
		$version += 1;
		$file = 'v'.$version . '_' . $file;
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
		    $this->model->registro_version_articulo($idArticulo, $file);
			echo $idArticulo;
		}
		 
		
	}
	
    function registroArticulo() {
		$fecha_actual = new DateTime('now', new DateTimeZone('America/Mexico_City'));
        $fecha_actual->format('Y-m-d');
		
        $fecha_apertura = new DateTime(FECHAAPERTURA, new DateTimeZone('America/Mexico_City'));
		$fecha_cierre = new DateTime(FECHACIERRE, new DateTimeZone('America/Mexico_City'));
		
		if($fecha_actual >= $fecha_apertura && $fecha_actual<= $fecha_cierre){
			$nombreArticulo = strtoupper($_POST['nombre']);
			$area = $_POST['area-tematica'];
			$tipo = $_POST['tipo-articulo'];
			$operacion=$_POST['tipo_operacion'];
			//SE VALIDA LA INSTANCIA DE id-articulo-registro PARA CONOCER SI SE ESTA ACTUALIZADON O INSERTANDO
			$idArticulo= isset($_POST['id-articulo-registro'])?$_POST['id-articulo-registro']:"";

			$articulo = array(
				'nombre' => $nombreArticulo,
				'area' => $area,
				'idArticulo' => $idArticulo,
				'idAutor' => Session::get('idAutor'),
				'tipo' => $tipo
			);

			if(!empty($idArticulo)){
					$existeArticulo = $this->model->existe_articulo($articulo);
					if ($existeArticulo) {
						echo 'error-articulo-repetido';
					} else {
						$responseDB=$this->model->update_articulo($articulo);
						echo 'actualizado';
					}			
			}
			elseif($operacion=="insertar" && empty($idArticulo)){
				$existeArticulo = $this->model->existe_articulo($articulo);
				if ($existeArticulo) {
					echo 'error-articulo-repetido';
				} else {
					$responseDB = $this->model->registro_articulo($articulo);
					if (!$responseDB) {
						echo 'error-registro';
					} else {
						$idArticulo = $responseDB;
							$this->model->registro_tabla_documentos($idArticulo);
							$idAutor = $this->model->get_id_autor(Session::get('id'));
							$responseDB = $this->model->registro_autor_articulo($idArticulo, $idAutor);
							//falta verificar el insert
							echo $idArticulo;
					}
				}
			}elseif($operacion=="actualizar" && empty($idArticulo)){
				echo 'error-actualizacion';
			}
			$this->model->update_estatus_cambios($idArticulo, 'no');			
		}else{
			echo "error-apertura";
		}

	}

    function registroAutor() {
		$tipoMovimiento=$_POST['tipo-movimiento'];
		$idAutor=$_POST['id-autor-autores'];
        $idArticulo=$_POST['id-articulo-autores'];
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
					'id' => $idArticulo, 
					'idAutor' => $idAutor, 
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
                if($tipoMovimiento=="insertar"){
					$totalAutores = $this->model->get_total_autores($idArticulo);
					if ($totalAutores < 4) {
						//Verifica si el autor no esta registrado para ese articulo
						$existeAutor = $this->model->existe_autor_articulo($idArticulo, $nombre, $apellidoPaterno,  $apellidoMaterno);
						if ($existeAutor) {
							echo 'error-autor-registrado';
						} else {
							$responseDB = $this->model->registro_autor($autor);
							if (!$responseDB) {
								echo 'error-registro';
							} else {
								$responseDB = $this->model->registro_autor_articulo($idArticulo, $responseDB);
								echo 'true';
							}
						}
					} else {
						echo 'error-numero-autores';
					}				
				}elseif($tipoMovimiento="actualizar"){
					$responseDB = $this->model->update_autor($autor);
					if (!$responseDB) {
						echo 'error-update';
					} else {
						echo 'true';
					}
				}

            } else {
                echo 'error-correo';
            }
        } else {
            echo 'error-null';
        }
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
	
     function getAutoresArticulo() {
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
                    if ($autor['autId'] != Session::get('idAutor')) {
                         //if ($cambio) {
                              $tabla .= '<td class="text-right"><a href="#" id="editar|' . $autor['autId'] . '"><i class="glyphicon glyphicon-edit"></i> Editar</a></td>';
                              $tabla .= '<td class="text-right"><a href="#" id="borrar|' . $autor['autId'] . '"><i class="glyphicon glyphicon-trash"></i> Borrar</a></td>';
                         //} else {
                         //     $tabla .= '<td class="text-right"></td>';
                         //     $tabla .= '<td class="text-right"></td>';
                        // }
                    }else{
                         $tabla .= '<td class="text-right"></td>';
                         $tabla .= '<td class="text-right"></td>';					
					}
				   
                    $tabla .= '</tr>';
               }
               $response = $tabla;
          }

          echo $response;
     }	
	
 /*   function getAutoresArticulo() {
        $idArticulo = $_POST['id'];
        $response = '';
        if (!empty($idArticulo)) {
            $responseDB = $this->model->get_autores_articulo($idArticulo);
            foreach ($responseDB as $autor) {
                $response .= '<li class="list-group-item" value="'.$autor['autId'].'">';
                $response .= $autor['autNombre'] .' '.$autor['autApellidoPaterno'].' '.$autor['autApellidoMaterno'];
                $response .= '<a href="#" id="borrar-autor" class="pull-right borrar-autor"><i class="glyphicon glyphicon-trash"></i> Borrar<a/>&nbsp;&nbsp;';
                $response .= '<label class="pull-right"><input type="radio" name="auto-contacto" value="'.$autor['autId'].'" checked>Autor de contacto</label>';
                $response .= '</li>';
            }
        }
        
        echo $response;
    }*/
    
	function get_det_art_autor($idAutor,$idArticulo) {
        $responseDB = $this->model->get_det_art_autor($idAutor,$idArticulo);
		$response = array(
			'id'     => $responseDB['artNombre']!=NULL?$idArticulo:NULL,
			'nombre' => $responseDB['artNombre'],
			'area' => $responseDB['artAreaTematica'],
			'archivo' => $responseDB['artArchivo'],
			'tipo' => $responseDB['artTipo']
		);
		$response=json_encode($response);
		return $response;
    }
	
    function getDetallesArticulo() {
        $idArticulo = $_POST['id_articulo'];
        $responseDB = $this->model->get_detalles_articulo($idArticulo);
        $response = array(
            'nombre' => $responseDB['artNombre'],
            'area' => $responseDB['artAreaTematica'],
            'archivo' => $responseDB['artArchivo'],
            'tipo' => $responseDB['artTipo']
        );
        
        echo json_encode($response);
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
                unlink(DOCS.$archivo);
                rmdir(DOCS.$idArticulo);
            } catch (Exception $exc) {
                error_log('No se puede borrar el archivo');
                error_log($exc->getTraceAsString());
            }
            echo 'true';
        } catch (Exception $exc) {
            echo 'false';
        }
    }
    
    function asignarAutorContacto() {
        $idAutor = $_POST['idAutor'];
        $idArticulo = $_POST['idArticulo'];
        $responseDB = $this->model->autor_de_contacto($idArticulo, $idAutor);
    }
    
    
    function comprobarCorreo($correo) {
        $correoCorrecto = 0;
        if ((strlen($correo) >= 6) && (substr_count($correo, '@') == 1) && (substr($correo,0,1) != '@') && (substr($correo, strlen($correo)-1,1) != '@')) {
            if ((!strstr($correo, "'")) && (!strstr($correo, "\"")) && (!strstr($correo, "\\")) && (!strstr($correo, "\$")) && (!strstr($correo, " "))) {
                if (substr_count($correo, ".") >= 1) {
                    $term_dom = substr(strrchr($correo, '.'), 1);
                    if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom, '@'))) {
                        $antes_dom = substr($correo,0, strlen($correo)-strlen($term_dom)-1);
                        $caracter_ult = substr($antes_dom, strlen($antes_dom)-1,1);
                        if ($caracter_ult != '@' && $caracter_ult != '.') {
                            $correoCorrecto = 1;
                        }
                    }
                }
            }
        }
        if ($correoCorrecto) {
            return TRUE;
        }else{
            return FALSE;
        }
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
	 
	function borrarAutor() {
          $idAutor = $_POST['idAutor'];
          $idArticulo = $_POST['idArticulo'];
          $response = '';
          if (!empty($idArticulo) && !empty($idAutor)) {
               //$validacionCambio = $this->model->validacion_cambio_articulo($idArticulo);
               //if ($validacionCambio) {
                    $responseBD = $this->model->borrar_autor($idAutor);
                    if (!$responseBD) {
                         $response = 'error-borrar';
                    } else {
                         $this->model->borrar_autor_articulo($idAutor, $idArticulo);
                         $response = 'true';
                    }
               //} else {
               //     $response = 'error-validacion';
               //}
          } else {
               $response = 'error-null';
          }
          echo $response;
     }
	
    public function borrar_autor_articulo($idAutor, $idArticulo) {
        $query = "DELETE FROM ".
                    "tblAutoresArticulos ".
                "WHERE ".
                    "artId=$idArticulo ".
                "AND ".
                    "autId=$idAutor";
        $data = NULL;
        $sth = $this->db->prepare($query);
        try {
            $sth->execute();
            $data = TRUE;
        } catch (PDOException $exc) {
            error_log($query);
            error_log($exc);
            $data = FALSE;
        }
        return $data;
    }
	
    function fncGetVerArticulos(){
    	$responseDB = $this->model->fncGetVerArticulos($_POST['id_articulo']);
    	$versiones = "<div>";
    	
    	if (!$responseDB) {
    		$versiones.="<div>No exiten art&iacute;culos cargados</div>";
    	}else{ 
			$versiones="<div>Versiones Cargadas:</div><br>";
    		foreach ($responseDB as $articulo) {
    			$versiones.="<div class='row'>".
    					     "<div class='col-md-12'>". strtoupper($articulo['archivo']).
					       "</div></div>";
    		}
    	}
    	$versiones.="</div>";
    	echo ($versiones);
    } 	
	
}
