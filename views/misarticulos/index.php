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
				<div class="row pull-right">
					<button type="button" id="btn-registro-articulo" class="btn btn-info"><span class="glyphicon glyphicon-file"></span> Registrar articulo</button>			<input id="perfil_valido" name="perfil_valido" type="text" class="hidden">
				</div>
				
				<!-- <div class="row row-misarticulos"> -->
					
						<!-- <h5>Para poder validar su participaci&oacute;n favor de completar el formato de registro. <a href="registroasistencia">IR AL FORMATO</a></h5> -->
						
						<div class="panel panel-default panel-listatrabajos">
							<div class="panel-heading">
								<h3 class="panel-title">Art&iacute;culos registrados</h3>
							</div>
							<div class="panel-body">
								<?php echo $this->tblArticulos; ?>
							</div>
							<div class="panel-footer">
								<b><em>Nota: </em></b><br><i class="glyphicon glyphicon-ok"> </i>&nbsp;Para mostrar informaci&oacute;n del art&iacute;culo dar clic en el id correspondiente. 
								<br><i class="glyphicon glyphicon-ok"> </i>&nbsp;Para poder editar alguno de lo art&iacute;culo, debe dar clic en el link de editar que se muestra a la derecha de la tabla superior. 								                    
							</div>
						</div>
				
					<div class="col-sm-12 datos-deposito">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Datos para realizar el dep&oacute;sito</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for=""><b>Banco:</b></label>
											<p class="form-control-static">Banbaj&iacute;o</p>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for=""><b>No. de cuenta:</b></label>
											<p class="form-control-static">0117760360101</p>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for=""><b>Cuenta clabe:</b></label>
											<p class="form-control-static">030248900003188437</p>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for=""><b>Sucursal:</b></label>
											<p class="form-control-static">Valle de Santiago</p>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer"></div>
						</div>
					</div>					
				

				<div id="modal-ver-articulo" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
					<!-- <form action="misarticulos/updateArticulo" method="post" id="form-update-articulo"> -->
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Detalles del art&iacute;culo</h4>
								</div>
								<div class="modal-body">
									<!-- Menú de las pestañas del artículo 
									<ul class="nav nav-tabs" role="tablist">
										<li id="li-detalles" role="presentation" class="active"><a href="#detalles" aria-controls="detalles" role="tab" data-toggle="tab">Edici&oacute;n del trabajo</a></li>
										<li id="li-cartas" role="presentation" class="hidden"><a href="#cartas" aria-controls="cartas" role="tab" data-toggle="tab">Cartas de originalidad y cesión de derechos</a></li>
										<li id="li-constancia" role="presentation" class="hidden"><a href="#constancia" aria-controls="constancia" role="tab" data-toggle="tab">Descarga de Constancia</a></li>
									</ul>-->
									<div class="tab-content">
										<!-- Div para los detalles del artículo -->
										<div id="detalles" class="tab-pane active" role="tabpanel">
											<form action="misarticulos/updateArticulo" method="post" id="form-update-articulo">
												<br>
												<div class="form-group">
													<label for=""><b>Nombre del art&iacute;culo:</b></label>
													<input id="update-articulo-id" name="id" type="text" class="hidden">
													<input id="ver-articulo-nombre" name="nombre" type="text" class="form-control" disabled>
												</div>
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label for=""><b>&Aacute;rea tem&aacute;tica:</b></label>
															<!-- <input type="text" name="area" class="form-control" id="ver-articulo-area" disabled> -->
															 <select name="area" id="ver-articulo-area" class="form-control" disabled>
																<option value="CAYS">Ciencias administrativas y sociales</option>
																<option value="EFC">Experiencia en formaci&oacute;n CA</option>
																<option value="CA">Ciencias agropecuarias</option>
																<option value="CNYE">Ciencias naturales y exactas</option>
																<option value="CIYT">Ciencias de ingenier&iacute;a y tecnolog&iacute;a</option>
																<option value="E">Educaci&oacute;n</option>
															</select>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label for=""><b>Tipo:</b></label>
															<select name="tipo" id="ver-articulo-tipo" class="form-control" disabled>
																<option value="extenso">Extenso</option>
																<option value="poster">Poster</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for=""><b>Ultima versión:</b></label>
													<div class="row">
														<div class="col-sm-6">
															<p id="ver-articulo-archivo" class="form-control-static"></p>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-sm-6">
															<label for=""><b>Autores:</b></label>
														</div>
													</div>

													<table id="tbl-ver-articulo-autores" class="table">
														<thead>
															<th class="hidden"></th>
															<th></th>
															<th></th>
															<th></th>
														</thead>
														<tbody></tbody>
													</table>
												</div>
												<hr>
												<div class="text-right">
													<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
												</div>
											</form>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					<!-- </form> -->
				</div>
				<div id="cargando" class="hidden centrado"><img src="./public/img/cargando.gif" alt=""></div>
			</div>   
                    
            <!-- termine frame-container -->   
        </div>
        
    </div>

</div>










