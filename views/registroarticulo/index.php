 

        <div class="info-container">
       <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <!-- <div class="absolute-wrapper"> </div> -->
        <!-- Menu -->
		 <?php include  MENULATERAL;?>
           
            <!-- Main Content -->
			<div class="container container-registroart">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Registro de artículo</h3>
					</div>
						<div class="panel-body">
				     
					     <div class="contened-art">
						  <form id="form-registro-articulo" action="registroarticulo/registroArticulo" method="post">
							<div class="row">
								<div class="form-group">
									<label for=""><b>Nombre del art&iacute;culo:</b></label>
									<input id="articulo-nombre" type="text" value="<?php echo $this->detalleArticulo->nombre ?>" class="form-control" autofocus="" name="nombre">
								</div>
							
								<div class="col-sm-6 area-tematica">
									<div class="form-group">
										<label for=""><b>&Aacute;rea tematica:</b></label>
										<select name="area-tematica" id="articulo-area-tematica"  class="form-control">
											<option value="CAYS" <?php if ($this->detalleArticulo->area=='CAYS') echo 'selected="selected"'; ?>>Ciencias administrativas y sociales</option>
											<option value="EFC" <?php if ($this->detalleArticulo->area=='EFC') echo 'selected="selected"'; ?>>Experiencia en formaci&oacute;n CA</option>
											<option value="CA" <?php if ($this->detalleArticulo->area=='CA') echo 'selected="selected"'; ?>>Ciencias agropecuarias</option>
											<option value="CNYE" <?php if ($this->detalleArticulo->area=='CNYE') echo 'selected="selected"'; ?>>Ciencias naturales y exactas</option>
											<option value="CIYT" <?php if ($this->detalleArticulo->area=='CIYT') echo 'selected="selected"'; ?>>Ciencias de ingenier&iacute;a y tecnolog&iacute;a</option>
											<option value="E" <?php if ($this->detalleArticulo->area=='E') echo 'selected="selected"'; ?>>Educaci&oacute;n</option>
										</select>
									</div>
								</div>
								<div class="col-sm-6 tipo-articulo">
									<div class="form-group">
										<label for=""><b>Tipo de art&iacute;culo:</b></label>
										<select name="tipo-articulo" id="tipo-articulo" class="form-control">
											<option value="extenso" <?php if ($this->detalleArticulo->tipo=='extenso') echo 'selected="selected"'; ?>>Extenso</option>
											<option value="poster" <?php if ($this->detalleArticulo->tipo=='poster') echo 'selected="selected"'; ?>>Poster</option>
										</select>
									</div>
								</div><br>
                                    <div class="frame-bottons frame-buttons">
                                        <div class=" text-right col-lg-12 col-sm-12">
                                             <button id="btn-guardar" type="submit" data-toggle="tooltip" title="Guardar"  class="btn btn-default" ><i class="fa fa-save fa-2x" aria-hidden="true"></i></button> 
                                             <input id="id-articulo-registro" value="<?php echo $this->detalleArticulo->id=='new'?"":$this->detalleArticulo->id ?>" name="id-articulo-registro" type="text" class="hidden">
										  <input id="tipo_operacion" value="<?php echo $this->detalleArticulo->id=='new'?"insertar":"actualizar"?>" name="tipo_operacion" type="text" class="hidden">
                                        </div>
                                    </div>
                                 </div>
						    </form>
						 </div>
                            <div class="contened-files hidden"  id="contenedor-archivo-art">
                                 <form id="uploadfile" enctype="multipart/form-data" action="registroarticulo/updloadFile" method="POST">
									 <div class="row row-files">
										  <div class="col-sm-6 col-md-6">
											  <ul id="file-list">
													<li class="no-items"> Ningun archivo cargado! </li>
											  </ul>
										  </div>
										  <div class="lista-archivos col-sm-6 col-md-6">
												<p class="help-block">Solo se permiten archivos con extencion .doc o .docx.</p>
												<p class="help-block">Para un mejor rendimiento considere las siguientes indicaciones:</p>
												<p class="help-block"><i class="glyphicon glyphicon-ok"></i>&nbsp;Tama&ntilde;o maximo: 1000 kb. (10MB.)<br>
												<i class="glyphicon glyphicon-ok"></i>&nbsp;Documentos de word 2003 (.doc). <br>
												<i class="glyphicon glyphicon-ok"></i>&nbsp;Use nombres de archivos cortos.
												</p>
										 </div>
									 </div>  
									 <br>
									<div id="container-btn-files" class="form-group">
										<span class="btn btn-success fileinput-button">
											<i class="glyphicon glyphicon-plus"></i>
											<span>Seleccionar archivo</span>
											<input type="file" id="archivo" name="archivo">
										</span>							
										 <!--<button id="cancelar" type="reset" class="btn btn-warning">
											<i class="glyphicon glyphicon-ban-circle"></i>
											<span>Cancelar carga</span>
										</button>-->
										<button id="cargar" type="submit" class="btn btn-primary">
											<i class="glyphicon glyphicon-upload"></i>
											<span>Iniciar carga</span>
										</button>									
										 <input id="id-articulo-file" name="id-articulo-file" type="text" class="hidden">

									</div>	
								</form>							
							</div>
							<div class="form-group hidden " id="modal-autores">
								<label for=""><b>Autores:</b></label>
								<a id="btn-agregar-autor" href="#" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar autor</a>
								<br>
								<br>
								<table id="tbl-articulo-autores" class="table">
									<thead>
										<th class="hidden"></th>
										<th></th>
										<th></th>
										<th></th>
									</thead>
									<tbody></tbody>
								</table>
							</div>								

						

						</div>
						<div class="panel-footer">
							<b><em>Nota: </em></b><br><i class="glyphicon glyphicon-ok"> </i>&nbsp;Favor de capturar los datos correctamente, ya que de aqu&iacute; se tomaran para la generaci&oacute;n de la constancia.
						</div>

				</div>
				<form id="form-registro-autor" action="registroarticulo/registroAutor" method="post">
					<div id="modal-registro-autor" class="modal fade" tabindex="-1" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Registro de autor</h4>
								</div>
								<div class="modal-body">
									<input id="id-autor-autores" name="id-autor-autores" type="text" class="hidden">
									<input id="id-articulo-autores" name="id-articulo-autores" type="text" class="hidden">
									<input id="tipo-movimiento" name="tipo-movimiento" type="text" class="hidden">
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label for=""><b>Nombre:</b></label>
												<input id="nombre" name="nombre" type="text" class="form-control">
											</div>	
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for=""><b>Apellido paterno:</b></label>
												<input id="apellido-paterno" name="apellido-paterno" type="text" class="form-control">
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for=""><b>Apellido materno:</b></label>
												<input id="apellido-materno" name="apellido-materno" type="text" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label for=""><b>Pa&iacute;s:</b></label>
												<select name="pais" id="pais" class="form-control">
													<?php echo $this->selectPaises; ?>
												</select>
											<!-- 	<input id="pais" name="pais" type="text" class="form-control"> -->
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for=""><b>Estado:</b></label>
												<select name="estado" id="estado" class="form-control"></select>
												<!-- <input id="estado" name="estado" type="text" class="form-control"> -->
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for=""><b>Ciudad:</b></label>
												<input id="ciudad" name="ciudad" type="text" class="form-control">
											<!-- <select id="ciudad" name="ciudad" class="form-control"></select> -->
											</div>
										</div>
									</div>
									<div class="form-group">
									<div class="row">

									</div>
										<label for=""><b>Correo:</b></label>
										<input id="correo" name="correo" type="text" class="form-control">
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for=""><b>Grado acad&eacute;mico:</b></label>
												<select name="grado-academico" class="form-control" id="grado-academico">
													<option value="licenciatura">Licenciatura</option>
													<option value="maestria">Maestr&iacute;a</option>
													<option value="doctorado">Doctorado</option>
												</select>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for=""><b>Instituci&oacute;n de procedencia:</b></label>
												<input id="institucion-procedencia" name="institucion-procedencia" type="text" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for=""><b>Tipo de instituci&oacute;n:</b></label>
												<select name="" id="tipo-institucion" class="form-control">
													<option value="tecnologica">Tecnol&oacute;gica</option>
													<option value="politecnica">Polit&eacute;cnica</option>
													<option value="autonoma">Aut&oacute;noma</option>
													<option value="instTecnologico">Instituto tecnol&oacute;gico</option>
													<option value="centroInvestifacion">Centro de investigaci&oacute;n</option>
													<option value="otro">Otro</option>
												</select>
												<br>
												<label id="lbl-input-tipo-institucion" for="" class="hidden"><b>Otra:</b></label>
												<input id="input-tipo-institucion" name="tipo-institucion" class="form-control hidden"></input>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for=""><b>Asistenc&iacute;a a congresos CICA:</b></label>
												<select name="asistencia-cica" id="asistencia-cica" class="form-control">
													<option value="primera">Primera vez</option>
													<option value="segunda">Segunda Vez</option>
													<option value="tercera">Tercera vez</option>
													<option value="todas">Ha asistido a todos los congresos</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
									<button class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				</form>
				<!--<div id="cargando" class="hidden centrado"><img src="./public/img/cargando.gif" alt=""></div>-->
			</div>			
                    
            <!-- termine Main-container -->   
        </div>
        
    </div>

</div>



















