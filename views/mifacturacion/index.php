 <div class="info-container">
    <div class="row">
        <?php include  MENULATERAL;?>
        <div class="container container-misarticulos">
            <div class="row row-misarticulos">
                <div class="col-sm-12 mis-articulos">
                    <br/>
                    <div class="panel panel-default ">
                        <div class="panel-heading">
                            <h3 class="panel-title">Dep&oacute;sitos validados, generaci&oacute;n de factura electr&oacute;nica.</h3>
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
                                <h4 class="modal-title">Factura electr&oacute;nica</h4>
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
                                <h4>Datos de Deposito</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""><b>Fecha dep&oacute;sito:</b></label>
                                            <p id="fecha-deposito" class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""><b>Monto:</b></label>
                                            <p id="monto" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                                <h4>Factura</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for=""><b>Archivo pdf</b></label>
                                            <p id="archivopdf" class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for=""><b>Archivo xml</b></label>
                                            <p id="archivoxml" class="form-control-static"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <button id="generar"type="button" class="btn btn-danger"><i class="glyphicon glyphicon glyphicon-tasks"></i> Generar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
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