 <div class="info-container">
       <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <!-- <div class="absolute-wrapper"> </div> -->
        <!-- Menu -->

    <?php include  MENUADMIN;?>

   <div class="container container-principal">
       <!-- <ol class="breadcrumb">
            <li><a href="index">Inicio</a></li>
            <li class="active">Dashboard</li>
        </ol> -->
        <div class="row row-gen-doc pull-right">
            <div class="col-sm-12">
                <button type="button" id="exportXLS" class="btn btn-info"><span class="glyphicon glyphicon-file"></span> Exportar excel</button>
                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-generar-gafete"><span class="glyphicon glyphicon-share-alt"></span> Generar Gafete</button>
                <button type="button" id="btn_Generar_Constancia" class="btn btn-success"><span class="glyphicon glyphicon-share-alt"></span> Generar Constancia</button> -->
            </div>
        </div>
        <br>
        <div class="panel panel-default panel-listatrabajos">
            <div class="panel-heading">
                <h3 class="panel-title">Art&iacute;culos registrados</h3>
            </div>
            <div class="panel-body">
                <table id="listaTrabajos" class="table table-responsive hover stripe">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ARCHIVO</th>
                            <th>ARTICULO</th>
                            <th>AUTOR</th>
                            <th>CORREO</th>
                            <th>TIPO</th>
                            <th>ENTREGADO</th>
                            <th>DICTAMINADO</th>
                            <th>ACTIVAR CAMBIOS</th>
                            <!--<th>VALIDAR DEPOSITO</th>-->
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                        foreach ($this->listaTrabajos as $key => $value) { 
                            echo '<tr>';
                            echo '<td class="id">'.$value['ID'].'</td>';
                            echo '<td class="archivo"><span class="glyphicon glyphicon-floppy-save" id="'.$value['ID'].'"></span></td>';
                            echo '<td class="titulo"><p id="seleccionar">'.$value['ARTICULO'].'</p></td>';
                            echo '<td class="autor"><p id="seleccionar">'.$value['AUTOR'].'</p></td>';
                            echo '<td class="email"><p id="seleccionar">'.$value['CORREO'].'</p></td>';
                            echo '<td class="tipo"><p id="seleccionar">'.$value['TIPO'].'</p></td>';
                            echo '<td class="recibido"><p><input type="checkbox" name="Recibido" id='.$value['ID'].' '.($value['RECIBIDO']=='si'?'checked':'').'/></p></td>';
                            echo '<td class="dictaminado"><p><input type="checkbox" name="Dictaminado" id='.$value['ID'].' '.($value['DICTAMINADO']=='si'?'checked':'').'/></p></td>';
                            echo '<td class="aviso"> <p><input type="checkbox" name="AvisoCambio" id='.$value['ID'].' '.($value['AVISOCAMBIO']=='si'?'checked':'').'/></p></td>';
                           // echo '<td class="deposito"> <p><input type="checkbox" name="_validacion_deposito" id='.$value['ID'].' '.($value['DEPOSITO']=='si'?'checked':'').'/></p></td>';
                            echo '</tr>'; 
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <b><em>Nota:</em></b> Para ver las opciones del art&iacute;culo dar doble clic en el registro de la tabla.
            </div>
        </div>
        <!-- MODAL PARA LA DESCARGA DE ARCHIVOS-->
        <div class="modal fade" id="descargaArchivos" role="dialog" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Versiones del Articulo</h4>
                    </div>
                    <div class="modal-body">
                        <div id="versiones" class="form-control-static"><!--DETALLES DEL ARTICULO--></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL PARA LOS DETALLES DEL ARTICULO -->
        <div id="detalletrabajo" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Detalles del art&iacute;culo</h4>
                    </div>
                    <div class="modal-body">
						<div id="detalles">
						<br>
							<div id="datosarticulo"></div>
							<h4>Autores:</h4>
							<hr>
							<div id="datosautores" class="container-fluid" style="overflow-x:scroll; white-space: nowrap;"></div>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <!--  MODAL PARA LA CAPTURA DE COMENTARIOS -->
        <div id="dialog-form" title="Insertar Comentario">
            <form id="frmComentarios" name="frmComentarios">
                <div id="modal-comentarios" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">COMENTARIOS</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <textarea class="form-control" rows="4" id="comentario1" name="comentario1" autofocus></textarea>
                                    <input type="hidden" id="idArticulo" name="idArticulo" value="">
                                    <input type="hidden" id="campo" name="campo" value="">
                                </div>
                            </div>
                            <div class="modal-footer" >
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
                                <button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
                                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--MODAL PARA LA GENERACION DE GAFETES-->
        <div id="" title="">
            <form id="form-generar-gafete" method="post" action="dashboard/generarGafete">
                <div id="modal-generar-gafete" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Generar Gafete</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Id del artículo:</label>
                                    <input name="id" type="text" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre del artículo:</label>
                                    <input name="nombre-articulo" type="text" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre del asistente:</label>
                                    <input name="nombre-asistente" type="text" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Tipo de asistente:</label>
                                    <select name="tipo-asistente" id="" class="form-control">
                                        <option value="autor">Autor</option>
                                        <option value="ponente">Ponente</option>
                                        <option value="general">Público general</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer" >
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
                                <button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Generar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
       <!-- MODAL PARA LA GENERACION DE CONSTANCIAS-->
        <div id="Gen_constancia" title="">
            <form id="frm-generar-constancia" method="post" action="dashboard/generarConstancia">
                <div id="modal-generar-constancia" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Generar Constancia</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Nombre del artículo:</label>
                                    <input id="nombre-articulo" name="nombre-articulo" type="text" class="form-control" autofocus required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre del 1er. Autor:</label>
                                    <input name="nombre-autor_1" type="text" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre del 2do. Autor:</label>
                                    <input name="nombre-autor_2" type="text" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre del 3er. Autor:</label>
                                    <input name="nombre-autor_3" type="text" class="form-control" >
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre del 4to. Autor:</label>
                                    <input name="nombre-autor_4" type="text" class="form-control" >
                                </div>
                            </div>
                            <div class="modal-footer" >
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
                                <button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Generar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

	</div>
        <!-- termine frame-container -->   
        
    </div>

</div>
