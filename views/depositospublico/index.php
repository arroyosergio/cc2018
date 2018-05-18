 <div class="info-container">
    <div class="row">
        <?php 
		if (Session::get('perfil') == 'contabilidad') {
			include  MENUCONTABLE;
		}
		else if (Session::get('perfil') == 'administrador') {
			include  MENUADMIN;
		}
		?>       
        
        <div class="container container-misarticulos">
            <div class="row row-misarticulos">
                <div class="col-sm-12 mis-articulos">
                   <div class="row pull-right">
                        <button id="exportXLS" class="btn btn-info pull-right"><span class="glyphicon glyphicon-file"></span> Exportar excel</button>
    	           </div>
                    <br/>
                    <br/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dep&oacute;sitos de publico en general</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php echo $this->tblDepositospublico; ?>
                            </div>
                        </div>
                        <div class="panel-footer"></div>
                    </div>
	               <div id="modal-detalles-deposito" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detalles dep&oacute;sito</h4>
                </div>
                <div class="modal-body">
        			<input id="id-deposito" name="id-deposito" type="text" class="hidden">
					<input id="id-articulo" type="text" name="id-articulo" class="hidden">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Banco:</b></label>
								<p id="banco" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Sucursal:</b></label>
								<p id="sucursal" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Transaccion:</b></label>
								<p id="transaccion" class="form-control-static"></p>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Tipo de pago:</b></label>
								<p id="tipo-pago" class="form-control-static"></p>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Detalles:</b></label>
								<p id="detalles" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Fecha dep&oacute;sito:</b></label>
								<p id="fecha-deposito" class="form-control-static"></p>
							</div>
						</div>

				  </div>
				  <div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Hr. de dep&oacute;sito:</b></label>
								<p id="hr-deposito" class="form-control-static"></p>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Monto:</b></label>
								<p id="monto" class="form-control-static"></p>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Comprobante:</b></label>
								<p target="_blank" id="comprobante" class="form-control-static"></p>
							</div>
						</div>
				 </div>

					<h4>Datos de facturaci&oacute;n</h4>
					<hr>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Razon social:</b></label>
								<p id="razon-social" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Rfc:</b></label>
								<p id="rfc" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Correo de contacto:</b></label>
								<p id="correo-contacto" class="form-control-static"></p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Calle:</b></label>
								<p id="calle" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Colonia:</b></label>
								<p id="colonia" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Número:</b></label>
								<p id="numero" class="form-control-static"></p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Estado:</b></label>
								<p id="estado" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>Municipio:</b></label>
								<p id="municipio" class="form-control-static"></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for=""><b>CP:</b></label>
								<p id="cp" class="form-control-static"></p>
							</div>
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
                   <div id="modal-envio-correo" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="form-envio-correo" action="depositospublico/enviarCorreo" method="post">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Envio de comentarios</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input id="id-deposito-correo" name="id-deposito" type="text" class="hidden">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for=""><b>Agregar los comentarios que se le haran llegar al asistente:</b></label>
                                                    <textarea name="comentarios" id="comentarios" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
                                        <button class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>