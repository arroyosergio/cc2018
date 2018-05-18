<div class="info-container">
    <div class="row">
        <?php include  MENUADMIN;?>
        <div class="container container-misarticulos">
            <div class="row row-misarticulos">
                <div class="col-sm-12 mis-articulos">
                    <br/>
                    <div class="panel panel-default ">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dep&oacute;sitos validados, control de facturación.</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php echo $this->tblDepositosValidados; ?>
                            </div>
                        </div>
                        <div class="panel-footer"></div>
                    </div>
                    
                    <div id="modal-detalles-facturacion" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Control de facturaci&oacute;n</h4>
                            </div>
                            <div class="modal-body">
                                <input id="id-deposito" name="id-deposito" type="text" class="hidden">
                                <input id="id-articulo" type="text" name="id-articulo" class="hidden">
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
                                <h4>Factura</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""><b>Archivo pdf</b></label>
                                            <p id="archivopdf" class="form-control-static"></p>
                                            <div id="file-list-pdf"><!-- COLOCA LAS CARACTERISTICAS DEL ARCHIVOS SELECCIONADO-->
										    </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""><b>Archivo xml</b></label>
                                            <p id="archivoxml" class="form-control-static"></p>
                                            <div id="file-list-xml"><!-- COLOCA LAS CARACTERISTICAS DEL ARCHIVOS SELECCIONADO-->
										    </div>                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <form id="form-subir-pdf" action="" method="post">
                                        <div class="form-group">
                                        <span class="btn btn-success fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Seleccionar archivo</span>
										<input type="file" id="archivo_pdf" name="archivo_pdf">
                                        </span>	
                                        <button id="cargar_pdf" type="submit" class="btn btn-primary">
										    <i class="glyphicon glyphicon-upload"></i>
										    <span>Iniciar carga</span>
										</button>
                                        <input id="id-art-pdf" name="id-art-pdf" type="text" class="hidden">
                                        </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <form id="form-subir-xml" action="" method="post">
                                        <div class="form-group">
                                        <span class="btn btn-success fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Seleccionar archivo</span>
										<input type="file" id="archivo_xml" name="archivo_xml">
                                        </span>	
                                        <button id="cargar_xml" type="submit" class="btn btn-primary">
											<i class="glyphicon glyphicon-upload"></i>
											<span>Iniciar carga</span>
										</button>
                                        <input id="id-art-xml" name="id-art-xml" type="text" class="hidden">
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"> Cerrar</button>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                </div>
            </div>
        </div>
    </div>
</div>