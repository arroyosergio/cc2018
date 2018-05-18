<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php echo $this->tblArticulos; ?>
            casa
        </div>
    </div>

    <form id="form-detalles-articulo" action="articulo/actualizarDetalles" method="post">
        <div id="modal-detalles-articulo" class="modal fade" role="dialog" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                       <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                        <h4 class="modal-title">Registro de art&iacute;culo</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form-actualizar-detalles" action="articulos/actualizarDetalles" method="post">
                            <div class="form-group">
                                <label for=""><b>Nombre del art&iacute;culo:</b></label>
                                <input class="hidden" type="text" id="id-articulo" name="id">
                                <p id="detalles-articulo-nombre" class="form-control-static"></p>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for=""><b>&Aacute;rea tem&aacute;tica:</b></label>
                                        <p id="detalles-articulo-area" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for=""><b>Tipo de art&iacute;culo:</b></label>
                                        <p id="detalles-articulo-tipo"  class="form-control-static"></p>
                                    </div>
                                </div>  
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <div class="col-sm-4">
                                        <label><input  id="checkbox-recibido" type="checkbox" name="Recibido" >Recibido</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label><input type="checkbox" id="checkbox-dictaminado" name="Dictaminado" >Dictaminado</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label><input type="checkbox" id="checkbox-avisodecambio" name="Avisodecambio">Aviso de cambio</label>
                                    </div>      
                                </div>
                            </div>

                            <br></br>
                            <div class="form-group">
                                <label for=""><b>Autores:</b></label>
                                <!--<a id="btn-agregar-autor" href="#" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar autor</a>
                                <br>-->
                                <br>
                                <ul class="list-group" id="detalles-articulo-autores">
                                    <!--<?php echo '<li class="list-group-item">' . Session::get('usuario') . '</li>'; ?> -->
                                </ul>
                            </div>
                    </div> 
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" id="btn-cancelar-registro"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    </form>
</div>