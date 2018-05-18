 <div class="info-container">
       <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <!-- <div class="absolute-wrapper"> </div> -->
        <!-- Menu -->

    <?php include  MENULATERAL;?>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="side-body">
         <br>
          <!-- Frame-container contiene los elementos para desplegar en el marco  -->
			<div class="frame-container">
				<div class="frame container-fluid">
                        <form id="form-info-perfil" action="perfil/guardarCambiosPerfil" method="post">
                            <div class="frame-title row">
                                <div class="logo-frame col-lg-1 col-sm-1"><i  data-toggle="tooltip" title="Perfil del Autor " class="fa fa-address-card fa-2x animated bounceIn" aria-hidden="true"></i></div>
                                <div class="frame-title-text text-left logo-frame col-lg-11 col-sm-11"><p>Perfil de usuario</p></div>
                            </div>
						    <div class="frame-message row">
						     <div class="col-lg-12 col-sm-12">
							     <!-- Inicio del formulario -->
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for=""><b>Nombre:</b></label>
                                                            <input id="nombre" name="nombre" type="text" class="form-control" autofocus>
                                                        </div>	
                                                        <div class="row p-apellidos">
                                                            <div class="col-sm-6 a-paterno">
                                                                <div class="form-group">
                                                                    <label for=""><b>Apellido paterno:</b></label>
                                                                    <input id="apellido-paterno" name="apellido-paterno" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6  a-materno">
                                                                <div class="form-group">
                                                                    <label for=""><b>Apellido materno:</b></label>
                                                                    <input id="apellido-materno" name="apellido-materno" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=""><b>Pa&iacute;s:</b></label>
                                                            <select name="pais" id="pais" class="form-control">
                                                                <!--<?php echo $this->selectPaises; ?>-->
                                                                								<option value="">Selecciona uno</option><option value="144">Afganistán</option><option value="114">Albania</option><option value="18">Alemania</option><option value="98">Algeria</option><option value="145">Andorra</option><option value="119">Angola</option><option value="4">Anguilla</option><option value="147">Antigua y Barbuda</option><option value="207">Antillas Holandesas</option><option value="91">Arabia Saudita</option><option value="5">Argentina</option><option value="6">Armenia</option><option value="142">Aruba</option><option value="1">Australia</option><option value="2">Austria</option><option value="3">Azerbaiyán</option><option value="80">Bahamas</option><option value="127">Bahrein</option><option value="149">Bangladesh</option><option value="128">Barbados</option><option value="9">Bélgica</option><option value="8">Belice</option><option value="151">Benín</option><option value="10">Bermudas</option><option value="7">Bielorrusia</option><option value="123">Bolivia</option><option value="79">Bosnia y Herzegovina</option><option value="100">Botsuana</option><option value="12">Brasil</option><option value="155">Brunéi</option><option value="11">Bulgaria</option><option value="156">Burkina Faso</option><option value="157">Burundi</option><option value="152">Bután</option><option value="159">Cabo Verde</option><option value="158">Camboya</option><option value="31">Camerún</option><option value="32">Canadá</option><option value="130">Chad</option><option value="81">Chile</option><option value="35">China</option><option value="33">Chipre</option><option value="82">Colombia</option><option value="164">Comores</option><option value="112">Congo (Brazzaville)</option><option value="165">Congo (Kinshasa)</option><option value="166">Cook, Islas</option><option value="84">Corea del Norte</option><option value="69">Corea del Sur</option><option value="168">Costa de Marfil</option><option value="36">Costa Rica</option><option value="71">Croacia</option><option value="113">Cuba</option><option value="22">Dinamarca</option><option value="169">Djibouti, Yibuti</option><option value="103">Ecuador</option><option value="23">Egipto</option><option value="51">El Salvador</option><option value="93">Emiratos árabes Unidos</option><option value="173">Eritrea</option><option value="52">Eslovaquia</option><option value="53">Eslovenia</option><option value="28">España</option><option value="55">Estados Unidos</option><option value="68">Estonia</option><option value="121">Etiopía</option><option value="175">Feroe, Islas</option><option value="90">Filipinas</option><option value="63">Finlandia</option><option value="176">Fiyi</option><option value="64">Francia</option><option value="180">Gabón</option><option value="181">Gambia</option><option value="21">Georgia</option><option value="105">Ghana</option><option value="143">Gibraltar</option><option value="184">Granada</option><option value="20">Grecia</option><option value="94">Groenlandia</option><option value="17">Guadalupe</option><option value="185">Guatemala</option><option value="186">Guernsey</option><option value="187">Guinea</option><option value="172">Guinea Ecuatorial</option><option value="188">Guinea-Bissau</option><option value="189">Guyana</option><option value="16">Haiti</option><option value="137">Honduras</option><option value="73">Hong Kong</option><option value="14">Hungría</option><option value="25">India</option><option value="74">Indonesia</option><option value="140">Irak</option><option value="26">Irán</option><option value="27">Irlanda</option><option value="215">Isla Pitcairn</option><option value="83">Islandia</option><option value="228">Islas Salomón</option><option value="58">Islas Turcas y Caicos</option><option value="154">Islas Virgenes Británicas</option><option value="24">Israel</option><option value="29">Italia</option><option value="132">Jamaica</option><option value="70">Japón</option><option value="193">Jersey</option><option value="75">Jordania</option><option value="30">Kazajstán</option><option value="97">Kenia</option><option value="34">Kirguistán</option><option value="195">Kiribati</option><option value="37">Kuwait</option><option value="196">Laos</option><option value="197">Lesotho</option><option value="38">Letonia</option><option value="99">Líbano</option><option value="198">Liberia</option><option value="39">Libia</option><option value="126">Liechtenstein</option><option value="40">Lituania</option><option value="41">Luxemburgo</option><option value="85">Macedonia</option><option value="134">Madagascar</option><option value="76">Malasia</option><option value="125">Malawi</option><option value="200">Maldivas</option><option value="133">Malí</option><option value="86">Malta</option><option value="131">Man, Isla de</option><option value="104">Marruecos</option><option value="201">Martinica</option><option value="202">Mauricio</option><option value="108">Mauritania</option><option value="42">México</option><option value="43">Moldavia</option><option value="44">Mónaco</option><option value="139">Mongolia</option><option value="117">Mozambique</option><option value="205">Myanmar</option><option value="102">Namibia</option><option value="206">Nauru</option><option value="107">Nepal</option><option value="209">Nicaragua</option><option value="210">Níger</option><option value="115">Nigeria</option><option value="212">Norfolk Island</option><option value="46">Noruega</option><option value="208">Nueva Caledonia</option><option value="45">Nueva Zelanda</option><option value="213">Omán</option><option value="19">Países Bajos, Holanda</option><option value="87">Pakistán</option><option value="124">Panamá</option><option value="88">Papúa-Nueva Guinea</option><option value="110">Paraguay</option><option value="89">Perú</option><option value="178">Polinesia Francesa</option><option value="47">Polonia</option><option value="48">Portugal</option><option value="246">Puerto Rico</option><option value="216">Qatar</option><option value="13">Reino Unido</option><option value="65">República Checa</option><option value="138">República Dominicana</option><option value="49">Reunión</option><option value="217">Ruanda</option><option value="72">Rumanía</option><option value="50">Rusia</option><option value="242">Sáhara Occidental</option><option value="223">Samoa</option><option value="219">San Cristobal y Nevis</option><option value="224">San Marino</option><option value="221">San Pedro y Miquelón</option><option value="225">San Tomé y Príncipe</option><option value="222">San Vincente y Granadinas</option><option value="218">Santa Elena</option><option value="220">Santa Lucía</option><option value="135">Senegal</option><option value="226">Serbia y Montenegro</option><option value="109">Seychelles</option><option value="227">Sierra Leona</option><option value="77">Singapur</option><option value="106">Siria</option><option value="229">Somalia</option><option value="120">Sri Lanka</option><option value="141">Sudáfrica</option><option value="232">Sudán</option><option value="67">Suecia</option><option value="66">Suiza</option><option value="54">Surinam</option><option value="234">Swazilandia</option><option value="56">Tadjikistan</option><option value="92">Tailandia</option><option value="78">Taiwan</option><option value="101">Tanzania</option><option value="171">Timor Oriental</option><option value="136">Togo</option><option value="235">Tokelau</option><option value="236">Tonga</option><option value="237">Trinidad y Tobago</option><option value="122">Túnez</option><option value="57">Turkmenistan</option><option value="59">Turquía</option><option value="239">Tuvalu</option><option value="62">Ucrania</option><option value="60">Uganda</option><option value="111">Uruguay</option><option value="61">Uzbekistán</option><option value="240">Vanuatu</option><option value="95">Venezuela</option><option value="15">Vietnam</option><option value="241">Wallis y Futuna</option><option value="243">Yemen</option><option value="116">Zambia</option><option value="96">Zimbabwe</option>

                                                            </select>
                                                        </div>
                                                        <div class="row p-direccion">
                                                            <div class="col-sm-6 d-estado">
                                                                <div class="form-group">
                                                                    <label for=""><b>Estado:</b></label>
                                                                    <select name="estado" id="estado" class="form-control"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 d-ciudad" >
                                                                <div class="form-group">
                                                                    <label for=""><b>Ciudad:</b></label>
                                                                    <input id="ciudad" name="ciudad" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for=""><b>Grado acad&eacute;mico:</b></label>
                                                            <select name="grado-academico" class="form-control" id="grado-academico">
                                                                <option value="licenciatura">Licenciatura</option>
                                                                <option value="maestria">Maestr&iacute;a</option>
                                                                <option value="doctorado">Doctorado</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=""><b>Instituci&oacute;n de procedencia:</b></label>
                                                            <input id="institucion-procedencia" name="institucion-procedencia" type="text" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=""><b>Tipo de instituci&oacute;n:</b></label>
                                                            <select name="" id="tipo-institucion" class="form-control">
                                                                <option value="tecnologica">Tecnol&oacute;gica</option>
                                                                <option value="politecnica">Polit&eacute;cnica</option>
                                                                <option value="autonoma">Aut&oacute;noma</option>
                                                                <option value="instTecnologico">Instituto tecnol&oacute;gico</option>
                                                                <option value="centroInvestifacion">Centro de investigaci&oacute;n</option>
                                                                <option value="otro">Otro</option>
                                                            </select>
                                                            <label id="lbl-input-tipo-institucion" for="" class="hidden"><b>Otra:</b></label>
                                                            <input id="input-tipo-institucion" name="tipo-institucion" class="form-control hidden"></input>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for=""><b>Asistenc&iacute;a a congresos CICA:</b></label>
                                                            <select name="asistencia-cica" id="asistencia-cica" class="form-control">
                                                                <option value="primera">Primera vez</option>
                                                                <option value="segunda">Segunda Vez</option>
                                                                <option value="tercera">Tercera vez</option>
                                                                <option value="todas">Ha asistido a todos los congresos</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <div class="frame-bottons row frame-buttons">
                                        <div class=" text-right col-lg-12 col-sm-12">
                                              <button  id="btn-cancelar" data-toggle="tooltip" title="Cancelar"  class="btn btn-default" ><i class="fa fa-times fa-2x" aria-hidden="true"></i></button>
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


