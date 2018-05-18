	<div class="container main_container">
		<div class="container_title feature-head wow fadeInDown animated"  data-wow-animation-name="fadeInDown" style="visibility: visible; animation-name: fadeInDown;" >
			<h2 class="titulo-reg-autores text-center"> Registro de autores </h2>				  
		</div>
            <!-- Frame-container contiene los elementos para desplegar en el marco  -->
			<div class="frame container-fluid">
					<div class="frame-container">
						<div id="panel panel-default Registro">
						    <form  id="form-nuevo-registro" class="new-placeholder" action="registroautores/nuevoRegistro" method="post">						
								<div class="panel-heading">
									<div class="panel-title">Captura de registro de autores</div>
								</div>						
								<div class="panel-body">				
										<div class="form-group">
											<input id="input-correo" type="text" class="form-control" name="correo" placeholder="Correo" autofocus autocomplete="off">
										</div>
										<div class="form-group">
											<input id="input-pass" type="password" class="form-control" name="password" placeholder="Contrase&ntilde;a">
										</div>
										<div class="form-group">
											<input type="password" class="form-control" name="rpassword" placeholder="Repetir Contrase&ntilde;a">
										</div>
						  		</div>
							  <div class="panel-footer foother-reg-autores text-right">
									<a href="index" type="button" class="btn btn-default btn-responsive"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
								<button class="btn btn-primary btn-responsive"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
							 </div>	
						 </form>				
					 </div>
           			</div>
            </div>    
</div>