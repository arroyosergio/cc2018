 <div class="info-container">
   <div class="row">
	<?php include  MENUADMIN;?>

	<div class="container container-listatrabajos">
		<div class="row row-cartas">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Control de cartas</h3>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<?php echo $this->tblCartas; ?>
						</div>
					</div>
					<div class="panel-footer"></div>
				</div>
			</div>
		</div>
		 <div id="modal-detalles-cartas" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Detalles de cartas</h4>
					</div>
					<div class="modal-body">
					   <div id="cartas" class="tab-pane" role="tabpanel">
							  <div id="div-carta-originalidad" class="form-group col-sm-6" ></div>
							  <div id="div-carta-derechos" class="form-group col-sm-6"></div>
							  <div id="div-validacion-derechos">
								 <label class="radio-inline">
									 <input type="checkbox" name="validacion-cartas" id="chkvalidacion-cartas" />
								 </label>&nbsp;&nbsp;
								<label><b>Validar cartas de Originalidad y Ceci&oacute;n de derechos.</b></label>				 
							  </div>
							  <div id="div-cambios-cartas">
								 <label class="radio-inline">
									 <input type="checkbox" name="act-cambios-cartas" id="chkact-cambios-cartas" />
								 </label>&nbsp;&nbsp;
							     <label><b>Activar cambios para control de cartas.</b></label>								 
							  </div>							  
							  <div id="div-enviar-correo">
								 <div >
								  <label><b>Correo de validacion para las cartas/activaci&oacute;n de cambios </b></label>
								  <textarea class="form-control" rows="4"  id="comentario_val_cartas" name="comentario_val_cartas" autofocus></textarea>
								 </div>
								 <br/>
								 <div class="text-right">
								 <button class="btn btn-primary" id="btn_enviar"><i class="glyphicon glyphicon-ok"></i> Enviar</button>
								 </div>
							  </div>
							  <hr>
						   <div>
						   	<label for=""><b>Carta de aceptaci&oacute;n:</b></label>
						   </div>
						    <form id="uploadfile" enctype="multipart/form-data" action="cartas/subir_carta_aceptacion" method="POST">
							  	<div class="form-group">
									<span class="btn btn-success fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Seleccionar archivo</span>
										<input type="file" id="archivo" name="archivo">
									</span>							
	
									<button id="cargar" name="cargar" type="submit" class="btn btn-primary">
										<i class="glyphicon glyphicon-upload"></i>
										<span>Iniciar carga</span>
									</button>									
									<input id="id-articulo-file" name="id-articulo-file" type="text" class="hidden">
									<p class="help-block" >Solo se permiten archivos con extensi&oacute;n .pdf</p>
								  </div>
								  <div class="row">
									<div class="col-sm-12" >
										  <div id="file-list"><!-- COLOCA LAS CARACTERISTICAS DEL ARCHIVOS SELECCIONADO-->
										</div>
									   <div id="documentos-actuales" class="row"></div>
									</div>
							  	</div>
						   </form>
					   </div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
					</div>
				</div><!-- /.modal-content --> 
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal --> 
	   </div>
	 </div>
</div>