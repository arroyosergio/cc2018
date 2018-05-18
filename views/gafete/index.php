 <div class="info-container">
       <div class="row">
        <?php include  MENULATERAL;?>
           
            <!-- Main Content -->
			<div class="container container-misarticulos">
				<div class="row row-misarticulos">
					<div class="col-sm-12 mis-articulos">
						<br>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Generaci&oacute;n de gafetes</h3>
							</div>
							<div class="panel-body">
								<?php echo $this->tblArticulosPagados; ?>
							</div>
							<div class="panel-footer">
								<b><em>Nota: </em></b><br><i class="glyphicon glyphicon-ok"> </i>&nbsp;Para descargar los gafetes de los asistentes relacionados con el art&iacute;culo dar clic en el id correspondiente. 
							</div>
						</div>
					</div>
				</div>
			</div>   
            <!-- termine frame-container -->   
            
				<div id="modal-generar-gafetes" class="modal fade" tabindex="-1" role="dialog">
						<div class="modal-lg modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Asistentes del art&iacute;culo</h4>
								</div>
								<div id="lst-asistentes" class="modal-body">
								   
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
								</div>
							</div><!-- /.modal-content --> 
						</div><!-- /.modal-dialog -->
			</div><!-- /.modal --> 
    </div>

</div>


