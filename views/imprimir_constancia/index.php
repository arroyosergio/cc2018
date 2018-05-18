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
                                	<p>Generaci&oacute;n de constancias</p>
                                </div>
                            </div>
						    <div class="frame-message row">
							     <!-- Inicio del formulario -->
                                    <div class="panel panel-default">
                                       <form id="form-generar-constancia" method="post" action="imprimir_constancia/generarConstancia">
                                         <div class="panel-body">
                                         	<div class="row">
												<div class="form-group">
													<label for="">Nombre del artículo:</label>
													<input id="nombre-articulo" name="nombre-articulo" type="text" class="form-control" autofocus required>
												</div>
												<div class="form-group">
													<label for="">Nombre del 1er. Autor:</label>
													<input name="nombre-autor_1" type="text" class="form-control" >
												</div>
												<div class="form-group">
													<label for="">Nombre del 2do. Autor:</label>
													<input name="nombre-autor_2" type="text" class="form-control" >
												</div>
												<div class="form-group">
													<label for="">Nombre del 3er. Autor:</label>
													<input name="nombre-autor_3" type="text" class="form-control" >
												</div>
												<div class="form-group">
													<label for="">Nombre del 4to. Autor:</label>
													<input name="nombre-autor_4" type="text" class="form-control" >
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


