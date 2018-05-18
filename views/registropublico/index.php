<br>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Registro de asistencia del p&uacute;blico</div>
		</div>
		<form id="form-datos-pago" action="registropublico/registroDatosPago" method="post">
			<div class="panel-body">
                <table class="table">
                    <tr>
                        <td><h4>Asistentes</h4></td>
                        <td><a id="btn-nuevo-asistente" class="btn btn-link pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar asistente</a></td> 
                    </tr>
                </table>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Nombre</th>
								<th class="text-center">Instituci&oacute;n</th>
								<th class="text-center">Tipo asistente</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tabla-asistentes">
							<?php echo $this->tablaAsistentes; ?>
						</tbody>
					</table>
				</div>
				<h4>Datos de facturaci&oacute;n</h4><h5> (Datos obligatorios solo en caso de requerir factura)</h5>
				<hr>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Nombre o raz&oacute;n social:</label>
							<input id="razon-social" type="text" class="form-control" name="razon-social"/>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">RFC:</label>
							<input id="rfc" type="text" name="rfc" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="">Calle:</label>
							<input id="calle" type="text" name="calle" class="form-control"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="">N&uacute;mero:</label>
							<input id="numero" type="text" name="numero" class="form-control"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="">Colonia o localidad:</label>
							<input id="colonia" type="text" name="colonia" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="">Municipio:</label>
							<input id="municipio" type="text" name="municipio" class="form-control"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="">Estado:</label>
							<input id="estado" type="text" name="estado" class="form-control"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="">C&oacute;digo postal:</label>
							<input id="codigo-postal" type="text" name="codigo-postal" class="form-control"/>
						</div>
					</div>
				</div>
				<h4>Datos del dep&oacute;sito</h4><h5> (Datos obligatorios)</h5>
				<hr>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Banco:</label>
							<input id="banco" class="form-control" name="banco" type="text" placeholder="dato obligario"/>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Tipo de pago:</label>
							<select id="tipo-pago" name="tipo-pago" class="form-control">
								<option value="deposito">Dep&oacute;sito</option>
								<option value="transferencia">Transferencia</option>
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Informaci&oacute;n del dep&oacute;sito:</label>
							<input id="info-deposito" class="form-control" name="info-deposito" type="text" placeholder="dato obligario"/>
							<p class="help-block"><b>Lugar de dep&oacute;sito</b> si es dep&oacute;sito en efectivo, o tranferencia ingrese el <b>N&Uacute;MERO DE FOLIO</b> o la <b>CLAVE DE RASTREO</b></p>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="">Fecha:</label>
							<input id="fecha" class="form-control datapicker" name="fecha" type="text" placeholder="dato obligario"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="from-group">
							<label for="">Hora:</label>
							<select name="hora" id="hora" class="form-control">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="from-group">
							<label for="">Minuto:</label>
							<select name="minuto" id="minuto" class="form-control">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
								<option value="32">32</option>
								<option value="33">33</option>
								<option value="34">34</option>
								<option value="35">35</option>
								<option value="36">36</option>
								<option value="37">37</option>
								<option value="38">38</option>
								<option value="39">39</option>
								<option value="40">40</option>
								<option value="41">41</option>
								<option value="42">42</option>
								<option value="43">43</option>
								<option value="44">44</option>
								<option value="45">45</option>
								<option value="46">46</option>
								<option value="47">47</option>
								<option value="48">48</option>
								<option value="49">49</option>
								<option value="50">50</option>
								<option value="51">51</option>
								<option value="52">52</option>
								<option value="53">53</option>
								<option value="54">54</option>
								<option value="55">55</option>
								<option value="56">56</option>
								<option value="57">57</option>
								<option value="58">58</option>
								<option value="59">59</option>
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<label for="">Número de sucursal:</label>
						<input type="text" class="form-control" id="num-sucursal" name="num-sucursal"/>
					</div>
					<div class="col-sm-3">
						<label for="">Número de transacción:</label>
						<input type="text" class="form-control" id="num-transaccion" name="num-transaccion"/>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Correo para contacto o envio de factura:</label>
							<input id="correo" type="text" name="correo" class="form-control" placeholder="dato obligatorio"/>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label for="">Cantidad a pagar:</label>
							<input id="monto" class="form-control" name="monto" type="text" disabled value="<?php echo $this->monto; ?>"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<p><b>Nota: </b>Se les solicita verificar  que el comprobante coincida con la cantidad a pagar indicada antes de enviar la informaci&oacute;n. Selecciona y carge el archivo del comprobante despu&eacute;s de hacer clic en aceptar.</p>
					</div>
				</div>
			</div>
			<div class="panel-footer text-right">
				<button class="btn btn-success" id="btn-aceptar-form-pago"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Aceptar</button>
			</div>
		</form>
        <div>
            <br>
            <h4 class="text-primary">Documento de pago escaneado.</h4>
                <div class="row">
                    <div class="col-sm-12">
                        <form id="uploadfile" enctype="multipart/form-data" action="registroarticulo/updloadFile" method="POST">
                             <div class="row row-files">
                                  <div class="col-sm-6 col-md-6">
                                      <ul id="file-list">
                                            <li class="no-items"> Ningun archivo cargado! </li>
                                      </ul>
                                  </div>
                                  <div class="lista-archivos col-sm-6 col-md-6">
                                        <p class="help-block">Solo se permiten archivos pdf.</p>
                                 </div>
                             </div>  
                             <br>
                            <div id="container-btn-files" class="form-group">
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>Seleccionar archivo</span>
                                    <input type="file" id="archivo" name="archivo"/>
                                </span>							
                                <button id="cargar" type="submit" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-upload"></i>
                                    <span>Iniciar carga</span>
                                </button>									
                                 <input id="id-articulo-file" name="id-articulo-file" type="text" class="hidden"/>
                            </div>	
                        </form>	
                    </div>
                </div>
                
            </div>
	</div>
	<div id="cargando" class="hidden centrado" style="position: absolute; top: 50%; left: 50%;  transform: translate(-50%, -50%);"><img src="./public/img/cargando.gif" alt=""></div>

	<div class="row">
		<div class="col-sm-12">
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
	</div> <!--.row-->

</div>
<form id="form-nuevo-asistente" action="" method="post">  <!-- registropublico/nuevoAsistente --> 
    <div id="modal-nueva-persona" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registro de asistente</h4>
                </div>
                <div class="modal-body">
					<div class="form-group">
						<label for="">Nombre completo:</label>
						<input id="nombre-asistente" class="form-control" name="nombre-asistente" autofocus/>
					</div>
					<div class="form-group">
						<label for="">Tipo de asistente:</label>
						<select name="tipo-asistente" class="form-control">
							<option value="general">Publico general</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Nombre de la instituci&oacute;n educativa:</label>
						<input class="form-control" name="institucion" placeholder="Dato opcional"/>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
                    <button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
<!-- editar asistente -->
<form id="form-editar-asistente" action="registropublico/updateAsistente" method="post">
    <div id="modal-editar-persona" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Editar asistente</h4>
                </div>
                <div class="modal-body">
                	<div class="form-group">
						<label for="">Nombre completo:</label>
						<input type="text" id="id-asistente" class="hidden" name="id-asistente"/>
						<input id="nombre-edit" class="form-control" name="nombre-edit"/>
					</div>
					<div class="form-group">
						<label for="">Tipo de asistente:</label>
						<select id="tipo-asistente" name="tipo-asistente" class="form-control">
							<option value="general">Publico general</option>
						</select>
					</div>
					<div class="form-group">
						<label for="">Nombre de la instituci&oacute;n educativa:</label>
						<input id="institucion" class="form-control" name="institucion"/>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
                    <button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
