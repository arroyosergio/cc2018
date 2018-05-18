        <div class="info-container">
           <div class="row">
                <?php include  MENUADMIN;?>
                <!-- Main Content -->
                <div class="container-fluid container-principal">
                    <div class="side-body">   
                            <form id="form-nuevo-registro" action="usuario/nuevoRegistro" method="post">
                                <div id="modal-nuevo-registro" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Nuevo Usuario</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for=""><b>Correo:</b></label>
                                                    <input type="text" class="form-control" name="correo" autofocus>
                                                </div>
                                                <div class="form-group">
                                                    <label for=""><b>Contrase&ntilde;a:</b></label>
                                                    <input type="password" class="form-control" name="password">
                                                </div>
                                                <div class="form-group">
                                                    <label for=""><b>Repetir contrase&ntilde;a:</b></label>
                                                    <input type="password" class="form-control" name="rpassword">
                                                </div>
                                                <div class="form-group">
                                                     <label for=""><b>Perfil:</b></label>
                                                    <select name="role" class="form-control">
                                                    <option value="administrador"> Administrador</option>
                                                    <option value="editor"> Editor</option>
                                                    <option value="contabilidad"> Contabilidad</option>
                                                    </select>
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
               
                        
                            <form id="form-editar-registro" action="usuario/editSave" method="post">
                                <div id="modal-editar-registro" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Edicion de Usuario</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for=""><b>Correo:</b></label>
                                                    <input type="hidden" class="form-control" name="usrid" id="usrid">
                                                    <input type="text" class="form-control" name="correo" id="ecorreo" autofocus>
                                                </div>
                                                <div class="form-group">
                                                    <label for=""><b>Contrase&ntilde;a:</b></label>
                                                    <input type="password" class="form-control" name="password">
                                                </div>
                                                <div class="form-group">
                                                    <label for=""><b>Repetir contrase&ntilde;a:</b></label>
                                                    <input type="password" class="form-control" name="rpassword">
                                                </div>
                                                <div class="form-group">
                                                     <label for=""><b>Perfil:</b></label>
                                                    <select name="role" id="erole" class="form-control">
                                                    <option value="administrador"> Administrador</option>
                                                    <option value="editor"> Editor</option>
                                                    <option value="contabilidad"> Contabilidad</option>
                                                    </select>
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
               
                        <div class="panel panel-default ">
                            <div class="panel-heading title-usuarios">
                                <h3 class="panel-title">Usuarios registrados</h3>
                                 
                            </div>
                            <div class="nuevousuario">
                                    <button class="btn btn-nuevousuario btn-success btn-success pull-right" id="btnNuevoUsuario"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Nuevo Usuario</button>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-hover table-condensed table-responsive " id="listaUsuarios" WIDTH="100%" >
                                    <thead>
                                        <tr>
                                        <!--<th>ID</th>-->
                                        <th>CORREO</th>
                                        <th>PERFIL</th>
                                        <th>EDICION</th>
                                        <th>ELIMINACION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    foreach ($this->userList as $key => $value) {
                                        echo '<tr>';
                                        //echo '<td width="10%">'.$value['usuId'].'</td>';
                                        echo '<td width="70%">'.$value['usuCorreo'].'</td>';
                                        echo '<td width="10%">'.$value['usuTipo'].'</td>';
                                        
                                        echo '<td width="10%" class="operaciones"><input type="hidden" class="accion" name="editar" id="editar" value="'.$value['usuId'].'"/><span class="glyphicon glyphicon-pencil"> Editar</span></td>';
                                        
                                        echo '<td width="1%" class="operaciones"><input type="hidden" class="accion" name="eliminar" id="eliminar" value="'.$value['usuId'].'"/><span class="glyphicon glyphicon-remove"> Eliminar</span></td>';
                                        echo '</tr>';
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        <div/>
               
                    </div><!--side-body  Fi del contenedor de informacion de lado derecho-->
                </div><!--container-fluid-->
            </div> <!--row-->
        </div><!--Fin info-container-->
