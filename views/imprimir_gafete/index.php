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
                                	<i  data-toggle="tooltip" title="Perfil del Autor " class="fa fa-address-card fa-2x animated bounceIn" aria-hidden="true"></i>
                                </div>
                                <div class="frame-title-text text-left logo-frame col-lg-11 col-sm-11">
                                	<p>Generaci&oacute;n de gafetes</p>
                                </div>
                            </div>
						    <div class="frame-message row">
							     <!-- Inicio del formulario -->
                                    <div class="panel panel-default">
                                       <form id="form-generar-gafete" method="post" action="imprimir_gafete/generarGafete">
                                         <div class="panel-body">
                                         	<div class="row">
												
													<div class="form-group">
															<label for="">Id del artículo:</label>
															<input name="id" type="text" class="form-control" required>
													</div>
													<div class="form-group">
															<label for="">Nombre del artículo:</label>
															<input name="nombre-articulo" type="text" class="form-control" required>
													</div>
													<div class="form-group">
														<label for="">Nombre del asistente:</label>
														<input name="nombre-asistente" type="text" class="form-control" required>
													</div>
													<div class="form-group">
														<label for="">Tipo de asistente:</label>
														<select name="tipo-asistente" id="" class="form-control">
															<option value="autor">Autor</option>
															<option value="ponente">Ponente</option>
															<option value="general">Público general</option>
														</select>
													</div>														
                                            </div>
                                         </div>
                                    	 <div class="frame-bottons row frame-buttons">
                                        	<div class=" text-right col-lg-12 col-sm-12">
                                             <button id="btn-guardar" type="submit" data-toggle="tooltip" title="Generar"  class="btn btn-default" ><i class="fa fa-download fa-2x" aria-hidden="true"></i></button> 
                                        	</div>
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


