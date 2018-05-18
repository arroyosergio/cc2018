 <div class="info-container">
       <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <!-- <div class="absolute-wrapper"> </div> -->
        <!-- Menu -->
        <?php include  MENULATERAL;?>
           
            <!-- Main Content -->
			<div class="container container-misarticulos">
				<!--<ol class="breadcrumb">
					<li><a href="index">Inicio</a></li>
					<li class="active">Mis art&iacute;culos</li>
				</ol> -->
				<div class="row row-misarticulos">
					<div class="col-sm-12 mis-articulos">
						<!-- <h5>Para poder validar su participaci&oacute;n favor de completar el formato de registro. <a href="registroasistencia">IR AL FORMATO</a></h5> -->
						<br>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Art&iacute;culos dictaminados</h3>
							</div>
							<div class="panel-body">
								<?php echo $this->tblArticulos; ?>
							</div>
							<div class="panel-footer">
								<b><em>Nota: </em></b><br><i class="glyphicon glyphicon-ok"> </i>&nbsp;Para descargar y cargar las carta de cesi&oacute;n de derechos y originalidad del art&iacute;culo dar clic en el id correspondiente. 
							</div>
						</div>
					</div>
				
				</div>

				<div id="modal-ver-articulo" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
					<!-- <form action="misarticulos/updateArticulo" method="post" id="form-update-articulo"> -->
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Cartas de originalidad y Cesi&oacute;n de derechos</h4>
								</div>
								<div class="modal-body">
								
									<br>
									<h4 class="text-primary">1.-Descargue los documentos para su llenado.</h4>
									<div class="row" name ="cartaaceptacion" id="cartaaceptacion-archivo">
									<!-- link de la carta de originalidad -->
										<div class="col-sm-6 col-md-6">
											<a href="docs/docs_Autor/Originalidad.docx" class="btn btn-link"><i class="glyphicon glyphicon-save-file"></i> Carta de originalidad</a>
											
										</div>
										<div class="col-sm-6 col-md-6">
											<a href="docs/docs_Autor/Derechos.docx" class="btn btn-link"><i class="glyphicon glyphicon-save-file"></i> Carta de cesi&oacute;n de derechos</a>
										</div>
									</div>
									<br>
										<h4 class="text-primary">2.-Suba los documentos escaneados.</h4>
										<div class="row">
											<div class="col-sm-6">
											<form id="form-subir-cartas-origen" action="" method="post">
												<div class="form-group">
													<label for=""><b>Carta de originalidad:</b></label>
													<div class="col-sm-12 col-md-12">
											  			<ul id="file-carta-origen">Ningun archivo seleccionado... </ul>
										  			</div>													
													<div id="container-btn-files" class="form-group">
														<span id="btnCarta-origen" class="btn btn-success fileinput-button">
															<i class="glyphicon glyphicon-plus"></i>
															<span>Seleccionar archivo</span>
															<input type="file" id="input-carta-originalidad" name="input-carta-originalidad" disabled>
														</span>							
														 <!--<button id="cancelar" type="reset" class="btn btn-warning">
															<i class="glyphicon glyphicon-ban-circle"></i>
															<span>Cancelar carga</span>
														</button>-->
														<button id="cargar_originalidad" type="submit" class="btn btn-primary">
															<i class="glyphicon glyphicon-upload"></i>
															<span>Iniciar carga</span>
														</button>									
														 <input id="id-articulo-original" name="id-articulo-original" type="text" class="hidden">
													</div>	
													<p class="help-block">Solo se permiten archivos con extensi&oacute;n .pdf</p>
												</div>
											 </form>
											</div>
											<div class="col-sm-6">
											<form id="form-subir-cartas-derechos" action="" method="post">
												<div class="form-group">
													<label for=""><b>Carta de cesi&oacute;n de derechos:</b></label>
													<div class="row">
											  			<ul id="file-carta-derechos">Ningun archivo seleccionado... </ul>
										  			</div>													
													<div id="container-btn-files" class="form-group">
														<span id="btnCarta-derechos" class="btn btn-success fileinput-button">
															<i class="glyphicon glyphicon-plus"></i>
															<span>Seleccionar archivo</span>
															<input type="file" id="input-carta-derechos" name="input-carta-derechos" disabled>
														</span>							
														 <!--<button id="cancelar" type="reset" class="btn btn-warning">
															<i class="glyphicon glyphicon-ban-circle"></i>
															<span>Cancelar carga</span>
														</button>-->
														<button id="cargar_cesion" type="submit" class="btn btn-primary">
															<i class="glyphicon glyphicon-upload"></i>
															<span>Iniciar carga</span>
														</button>	
														 <input id="id-articulo-cesion" name="id-articulo-cesion" type="text" class="hidden">
													</div>														
													<p class="help-block">Solo se permiten archivos con extensi&oacute;n .pdf</p>
												</div>
												</form>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<h4 class="doc-cargados  text-primary">:: Documentos cargados ::</h4>
												<div id="documentos-actuales" class="row"></div>
											</div>
										</div>
										<div class="text-right">
											<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
											<button id="btn-aceptar-cartas" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
										</div>

									</div>
							</div>
						</div>
					<!-- </form> -->
				</div>
			</div>   
                    
            <!-- termine frame-container -->   
        </div>
        
    </div>

</div>










