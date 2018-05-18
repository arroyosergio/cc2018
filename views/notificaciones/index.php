<div class="info-container">
       <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <!-- <div class="absolute-wrapper"> </div> -->
        <!-- Menu -->

    <?php include  MENUADMIN;?>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="side-body">
          <!-- Frame-container contiene los elementos para desplegar en el marco  -->
			<div class="frame-container frame-main-gafete">
				<div class="frame container-fluid">
                    <div class="frame-title row">
                        <div class="logo-frame col-lg-1 col-sm-1">
                            <i  data-toggle="tooltip" title="Perfil del Autor " class="fa fa-envelope fa-2x animated bounceIn" aria-hidden="true"></i>
                        </div>
                        <div class="frame-title-text text-left logo-frame col-lg-11 col-sm-11">
                        	<p>Envio de notificaciones</p>
                        </div>
                        </div>
						<div class="frame-message row">
						    <!-- Inicio del formulario -->
                            <div class="panel panel-default">
								<form action="notificaciones/enviarNotificacion" method="post" id="form-enviar-notificacion">
									<div class="form-group">
										<label for="">Título</label>
										<input type="text" class="form-control" name="titulo" required>
									</div>
									<div class="form-group">
										<label for="">Mensaje</label>
										<input type="text" class="form-control" name="mensaje" required>
									</div>
									<div class="text-right">			<button class="btn btn-success">Enviar</button>
									</div>
								</form>                                       
                            </div>
                        </div>
                	</div>   
            	</div>
        
        </div>

        <!-- termine frame-container -->   
        </div>
        
    </div>

</div>





