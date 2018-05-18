 <div class="info-container">
       <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <!-- <div class="absolute-wrapper"> </div> -->
        <!-- Menu -->

      <?php 
 	  $role=Session::get('perfil');      
      if($role=='administrador'){
		include  MENUADMIN;  
	  }
      if($role=='autor'){
    	include  MENULATERAL;
	  }  
      if($role=='editor'){
    	include  MENUEDITOR;
	  }   ?>
    
    
    <div class="container-fluid">
        <div class="side-body">
          <!-- Frame-container contiene los elementos para desplegar en el marco  -->
			<div id="frame-cmbio-pwd" class="frame-container">
				<div class="frame container-fluid">
                        <form id="form-cambiar-password" action="cambiarpwd/cambiarpasswd" method="post">
                            <div class="frame-title row">
                                <div class="logo-frame col-lg-1 col-sm-1"><i  data-toggle="tooltip" title="Perfil del Autor " class="fa fa-plug fa-2x animated bounceIn" aria-hidden="true"></i>
                                </div>
                                <div class="frame-title-text text-left logo-frame col-lg-11 col-sm-11"><p>Cambiar contraseña</p>
                                </div>
                            </div>
						    <div class="frame-message row">
						     <div class="col-lg-12 col-sm-12">
							     <!-- Inicio del formulario -->
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
														<div class="form-group">
															<label for=""><b>Contrase&ntilde;a actual:</b></label>
															<input id="pass_contra" type="password" class="form-control" name="password-actual" autocomplete="off"  autofocus>
														</div>
														<div class="form-group">
															<label for=""><b>Nueva Contrase&ntilde;a:</b></label>
															<input type="password" class="form-control" name="nuevo-password" autocomplete="off" >
														</div>
														<div class="form-group">
															<label for=""><b>Repetir contrase&ntilde;a:</b></label>
															<input type="password" class="form-control" name="rpassword" autocomplete="off" >
														</div>                                                     
                                                    </div>

                                                    </div>
                                                    
                                                </div>
                                            </div>
                                    <div class="frame-bottons row frame-buttons">
                                        <div class=" text-right col-lg-12 col-sm-12">
                                             <button id="btn-guardar" type="submit" data-toggle="tooltip" title="Guardar"  class="btn btn-default" ><i class="fa fa-save fa-2x" aria-hidden="true"></i></button> 
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </form>
                </div>   
                
            </div>
        
        </div>
    
        <br>
        <!-- termine frame-container -->   
        </div>


        
    </div>

</div>


