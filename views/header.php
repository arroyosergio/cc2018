<?php Session::init(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title>CICA 2018</title>
    <link rel="icon" type="image/png" href="<?php echo URL; ?>public/images/favicon.png" />
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.min.css" media="all" rel="stylesheet" />
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.0/normalize.min.css" media="all" rel="stylesheet" />
    <link rel="stylesheet"	href="<?php echo URL; ?>public/plugins/toastr/toastr.min.css" />

	<!--<link rel="stylesheet" type="text/css" href="<?php //echo URL; 
     ?>public/bootstrap/css/bootstrap_yeti.min.css">
	<link rel="stylesheet" href="<?php //echo URL; ?>public/css/custom.css"> -->
	
	<!--<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/bootstrap/css/bootstrap_yeti.min.css">
	<link rel="stylesheet" href="<?php echo URL; ?>public/css/custom.css"> -->

	<?php 
    	if (isset($this->css)) {
    		foreach ($this->css as $css) {
    			echo '<link rel="stylesheet" href="'.URL.$css.'">';
    		}
    	}
     ?>
     <link rel="stylesheet"	href="<?php echo URL; ?>public/css/general.css" />
     
</head>
<body>
    <?php
        //Manejo de sesion del usuario, identifica bassicamente el inicio de sesion y recupera el nombre del usuario.
        $banderaSesion = false;
        if (Session::get('sesion')) {
            try {
                $nombreUsuario = Session::get('usuario');
                $banderaSesion = true;
            } catch (Exception $e) {
                $nombreUsuario = '';
            }
        }
        /*else{
            $banderaSesion = true;
            $nombreUsuario = 'test';
        }*/
    ?>
    <div class="container menu_superior">
      <header id="head-section" class="navbar navbar-default site-header" role="banner">
          <nav class="navbar navbar-default navbar-static-top container-fluid ">
             <div class="navbar-header">
                    <a href="<?php echo URL.'public/images/cica_logo.png'; ?>" title="" data-fluidbox>
                        <img class="img-fluid img-responsive logo_header" src="<?php echo URL.'public/images/cica_logo.png'; ?>" title="" alt="" />
                    </a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
             </div>
              <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav head-menu">
                    <li><a href="index">Inicio</a></li>
                    <li id="mConvocatoria" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Convocatoria<span class="caret"></span></a>
                        <ul class="dropdown-menu sub-menu" role="menu" aria-labelledby="dLabel">
                              <li><a href="fechasImportantes"> Fechas importantes </a></li>
                              <li><a href="noticias"> Noticias </a></li>
                        </ul>
                    </li>
                    <li id="mPrograma" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Programa<span class="caret"></span></a>
                        <ul class="dropdown-menu sub-menu" role="menu" aria-labelledby="dLabel">
                              <li><a href="programaGeneral"> General </a></li>
                              <li><a href="programaTalleres"> Talleres</a></li>
                              <li><a href="programaMesas"> Mesas de Trabajo</a></li>
                        </ul>
                    </li>
                    <li><a href="hospedaje">Hospedaje</a></li>	
                    <li id="mAutores" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Autores<span class="caret"></span></a>
                        <ul class="dropdown-menu sub-menu">
                            <li><a href="guia">Guía</a></li>
                            <li><a href="registroautores" >Registro</a></li>
                            <li><a href="./public/movil/cicaNotificaciones.apk" >App CICA (androide)</a></li>
                            
                        </ul>
                    </li>
                    <li id="mAsistentes" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Asistentes<span class="caret"></span></a>
                        <ul class="dropdown-menu sub-menu">
                            <li><a href="informacion">Información</a></li>
                            <li><a href="registropublico">Registro de pago</a></li>
                        </ul>
                    </li>
                    <?php
                        //Si el usuario hay iniciado sesión mostramos el menu de usuario activo, 
                        //en caso contrario, mostramos el menu genérico de inicio de sesión.
                        if ($banderaSesion) {
                    ?> 
                        <li id="mSesion" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $nombreUsuario; ?><span class="caret"></span></a>
                            <ul class="dropdown-menu sub-menu">

							  <?php 
								  if (Session::get('perfil') == 'autor') {
									echo '<li><a href="perfil"><i class="fa fa-address-card" aria-hidden="true"></i> Perfil</a></li>';
								  }
								  if (Session::get('perfil') == 'administrador') {
									echo '<li><a href="dashboard"><i class="fa fa-address-card" aria-hidden="true"></i> Panel de control</a></li>';
								  }
								  if (Session::get('perfil') == 'editor') {
									echo '<li><a href="editores"><i class="fa fa-address-card" aria-hidden="true"></i> Panel de control</a></li>';
								  }		
								  if (Session::get('perfil') == 'contabilidad') {
									echo '<li><a href="depositos"><i class="fa fa-usd" aria-hidden="true"></i> Depositos</a></li>';
								  }								
							   ?>
                                <li role="presentation" class="divider line-divider"></li>
                                <li><a href="index/cerrarSesion"><i class="fa fa-sign-out" aria-hidden="true"></i> logout</a></li>  

                            </ul>
                        </li>
                    <?php  
                        }else{
                    ?>  

                         <li id="mSesion">
							 <a href="#" id="user-login" >
                                <!-- <i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;-->
                              Iniciar sesión</a>                          
                            </ul>

                        </li>
                    <?php
                        }
                    ?>                    
                </ul>
              </div><!--/.nav-collapse -->
          </nav>
        </header>
    </div> <!-- /container menu superior-->
    





