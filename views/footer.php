        <foother class="foother">
        	<div class="container foother-container">
        		<div class="row">
                    
                    <div class="col-lg-4 col-sm-3   wow fadeInUp animated " data-wow-duration="2.5s" data-wow-delay="4s" data-wow-animation-name="fadeInUp" style="visibility: visible; animation-duration: 2.5s; animation-delay: .1s; animation-name: fadeInUp;">
                        <div class="contact-info">
                            <h4 class="foother_title">Informaci&oacute;n de contacto</h4>
                            <address>
                                <span class="address"><span class="resalted"><strong>Teresa Ram&iacute;rez Cano</strong></span><br>
                                <span><i class="fa fa-user-circle-o" aria-hidden="true"></i> Coordinadora general CICA 2018.</span><br>                               
                                <span class="address"><span class="resalted"><strong>Jose Antonio Gordillo Sosa</strong></span></span><br>
                                <span><i class="fa fa-user-circle-o" aria-hidden="true"></i> Coordinador del comit&eacute; arbitral</span><br><br>
                                <span> <i class="fa fa-phone-square" aria-hidden="true"></i> Tel&eacute;fono : (456) 64 371 80 
                                <br>
                                Extensiones:  106 y 121 </span>
                                <p><i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Email :  <a href="mailto:correo@cica.com">contacto@cica2018.mx</a></p>
                            </address>
                        </div>
                    </div>
        			<div class="col-lg-2 col-sm-2 wow fadeInUp animated" data-wow-duration="2.5s" data-wow-delay="4s" data-wow-animation-name="fadeInUp" style="visibility: visible; animation-duration: 2.5s; animation-delay: .5s; animation-name: fadeInUp;">
                        <div class="navigation">
                            <a href="index"><h4>CICA 2018</h4></a>
                            <p><a href="programaGeneral">Programa general</a></p>
                            <p><a href="fechasImportantes">Fechas importantes</a></p>
                            <p><a href="guia">Autores</a></p>
                            <p><a href="informacion">Asistentes</a></p>
                            <p><a href="noticias">Noticias</a></p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-5 wow fadeInUp animated" data-wow-duration="2.5s" data-wow-delay="4s" data-wow-animation-name="fadeInUp" style="visibility: visible; animation-duration: 2.5s; animation-delay: .9s; animation-name: fadeInUp;">
                          <!--<h4>Gobierno del Estado de Guanajuato</h4>-->
                            <br/>
                            
                          <table>
                                <tr>
                                    <td>
                                        <div>
                                            <img src="<?php echo URL.'public/images/logo4.png'; ?>" alt="" title=""height="400" width="400" class="img-fluid img-responsive" />
                                        </div>        
                                    </td>
                                    <td>  
                                    </td>
                                    <td>
                                        <div>
                                            <img hspace="50" src="<?php echo URL.'public/images/logo1.png'; ?>" alt="" title="" height="300" width="400" class="img-fluid img-responsive" />
                                        </div>        
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <div>
                                            <br/>
                                            <img vspace="30" align="right" src="<?php echo URL.'public/images/logo2.png'; ?>" alt="" title=""height="500" width="500" class="img-fluid img-responsive" />
                                        </div>
                                        
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <div>
                                            <br/>
                                            <img hspace="50" src="<?php echo URL.'public/images/logo3.png'; ?>" alt="" title=""height="300" width="250" class="img-fluid img-responsive" />
                                        </div>
                                    </td>
                                </tr>
                        </table>
                    </div>
        		</div>
        	</div>
        </foother>
        <foother class="foother-small">
        	<div class="container small-foother-container">
        		<div class="row">
        			<div class="col-md-12">
        				<div class="copyright text-center">
							<p>Copyright 2018 Congreso Interdisciplinario de Cuerpos Acad&eacute;micos </p>
                            <p>
                                <i data-toggle="tooltip" title="HTML5" class="fa fa-html5" aria-hidden="true"></i>
                                <i data-toggle="tooltip" title="Chrome" class="fa fa-chrome" aria-hidden="true"></i>
                                <i data-toggle="tooltip" title="Firefox" class="fa fa-firefox" aria-hidden="true"></i>
                                <i data-toggle="tooltip" title="Safari" class="fa fa-safari" aria-hidden="true"></i>
                            </p>
        				</div>
        			</div>
        		</div>
        	</div>
        </foother>
        
    <!--  MODAL LOGIN -->
    <div id="modal-login" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
					<span class="modal-title" id="ModalLongTitle">Control de acceso</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
                </div>
                <div class="modal-body">
					<form id="form-login" action="index/login" class="new-placeholder" method="post">
						<div class="modal-body">
							<div class="form-group">
								<input type="text" id="login-correo" name="correo" placeholder="Correo" class="form-control" autocomplete="off" >
							</div>
							<div class="form-group">
								<input type="password" id="login-password" name="password" placeholder="Contrase&ntilde;a" class="form-control" autocomplete="off" >
							</div>
							<a href="resetpassword">¿Recordar contrase&ntilde;a?</a>
						</div>
						<div class="modal-footer" >
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
							<button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Aceptar</button>
						</div>
					</form>
				</div>
            </div>
        </div>
    </div>
             
              
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.min.js"></script>
        <script src="<?php echo URL; ?>public/plugins/jquery-2.1.4.min.js"></script>
        <script src="<?php echo URL; ?>public/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo URL; ?>public/js/general.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.min.js"></script>
        <?php 
            if (isset($this->js)) {
                foreach ($this->js as $js) {
                    echo '<script src="'.URL.$js.'"></script>';
                }
            }
         ?>
        <script>
                $(function () {
                    // You can use any kind of selectors for jQuery Fluidbox
                    $('.main_container a[data-fluidbox]').fluidbox();
                    $('.navbar-header a[data-fluidbox]').fluidbox();
                });
                $(function () {
                     $("#imagenesInsitucionales").responsiveSlides({
                        auto: true,
                        pager: false,
                        nav: false,
                        speed: 800
                      });
                });
        </script>
    </body>
</html>