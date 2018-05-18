var ulrUpfile="";

$(document).ready(function () {
    activarOpcionMenu();
    fileListOrigen=document.getElementById("file-comprobante-pago");
    $('.datapicker').datepicker({
        language: "es",
        autoclose: true,
        todayHighlight: true
    });
    
    var filesUpload=document.getElementById("archivo");
	fileList=document.getElementById("file-list");
	$("#cancelar").hide();
	$("#cargar").hide();
    $('input#archivo').prop("disabled", true);
    
});

$('#btn-nuevo-asistente').click(function () {
    $('#modal-nueva-persona input').val('');
    $('#modal-nueva-persona').on('shown.bs.modal', function () {
        $('#nombre-asistente').focus();
    }).modal('show');
});

//Registro de los datos de facturación y del deposito
$('#form-datos-pago').submit(function () {
    var url = $(this).attr('action');
    var formData = new FormData($(this)[0]);
             $.ajax({
                url: 'registropublico/registroDatosPago',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                  jsShowWindowLoad();
                },
                success: function (response) {
                	jsRemoveWindowLoad();
                    switch (response) {
                        case 'true':
                            mostrarAlerta('success', 'Su registro se realizo con éxito. Ahora suba su comprobante de pago.');
                            $('input').attr('disabled', 'disabled');
                            $('select').attr('disabled', 'disabled');
                            $('#btn-nuevo-asistente').addClass('hidden');
                            $('input#archivo').prop("disabled", false);
                            $('.my-link').click(function(e){return false; });
                            break;
                        case 'false':
                            mostrarAlerta('error', 'Ocurrio un problema con su registro.');
                            break;
                        case 'error-formato':
                            mostrarAlerta('error', 'Solo se permite archivos en formato PDF.');
                            break;
                        case 'error-correo':
                            mostrarAlerta('error', 'Ingrese una dirección de correo valida.');
                            break;
                        case 'error-num-asistentes':
                            mostrarAlerta('error', 'Agregue al menos un asistente.');
                            break;
                        case 'error-subir-archivo':
                            mostrarAlerta('error', 'Ocurrio un error al subir su archivo.');
                            break;
                        case 'error-null':
                            mostrarAlerta('error', 'Algunos campos obligatorios se encuentran vac&iacute;os..');
                            break;
                        case 'error-formato-fecha':
                            mostrarAlerta('error', 'Formato de fecha incorrecto');
                            break;
                    }
                },
                error: function () {
                    message = $("<span class='error'>Ha ocurrido un error.</span>");
                }
            });
        //}
    //});
    return false;
});

//Agregar un nuevo asistente
$('#form-nuevo-asistente').submit(function () {
	var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post('registropublico/nuevoAsistente', data, function (response) {
        switch (response) {
            case 'true':
            	getAsistentesPublico();
                getMonto();
                mostrarAlerta('success', 'Se agrego un nuevo asistente.');
                $('#modal-nueva-persona').modal('hide');
                break;
            case 'error-query':
                mostrarAlerta('error', 'No se pudo agregar el nuevo asistente.');
                break;
            case 'error-null':
                mostrarAlerta('error', 'Todos los campos son obligatorios.');
                break;
            case 'error-cantidad':
                mostrarAlerta('error', 'Solo puede capturar como m&aacute;ximo 10 asistentes por registro.');
                break;
            case 'error-replicado':
                mostrarAlerta('error', 'El nombre del asistente, ya esta registrado en como autor.');
                break;
        }
    });
    return false;
});

//Editar asistente
$('#form-editar-asistente').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (response) {
        switch (response) {
            case 'true':
            	getAsistentesPublico();
                $('#modal-editar-persona').modal('hide');
                mostrarAlerta('success', 'Se modifico el asistente.');
                break;
            case 'false':
                mostrarAlerta('error', 'No se puede modificar al asistente.');
                break;
            case 'error-null':
                mostrarAlerta('error', 'Todos los campos son obligatorios.');
                break;
        }
    });
    return false;
});

//Evento del clic en la tabla de asistentes
$('#tabla-asistentes').click(function (event) {
    var id = event.target.id;
    var opcion = '';
    id = id.split('|');
    opcion = id[0];
    id = id[1];
    if (opcion === 'editar') {
        getDatosAsistente(id);
    }
    if (opcion === 'borrar') {
        borrarAsistente(id);
        getMonto();
    }
});


function getAsistentesPublico() {
    $.post('registropublico/getAsistentesPublico', {}, function (response) {
        $('#tabla-asistentes').empty();
        $('#tabla-asistentes').html(response);
        $('#modal-nueva-persona').modal('hide');
    });
}

function getMonto() {
    $.post('registropublico/getMonto', {}, function (response) {
        $('#monto').val(response);
    });
}

function getDatosAsistente(id) {
    $.post('registropublico/getDatosAsistente', {id: id}, function (response) {
        $('#id-asistente').val(response.id);
        $('#nombre-edit').val(response.nombre);
        $('#institucion').val(response.institucion);
        $('#tipo-asistente').val(response.tipo);
    }, 'json');
    $('#modal-editar-persona').on('shown.bs.modal', function () {
    	$('#nombre-edit').focus();
    }).modal('show');	
}

function borrarAsistente(id) {
    $.post('registropublico/borrarAsistente', {id: id}, function (response) {
        switch (response) {
            case 'true':
            	getAsistentesPublico();
                mostrarAlerta('success', 'Se borro el asistente.');
                break;
            case 'false':
                mostrarAlerta('error', 'Ocurrio un problema al borrar al asistente.');
                break;
        }
    });
}

function getEstatusCambios() {
    $.post('registroasistencia/getEstatusCambios', {}, function (response) {
        if (response === 'no') {
            $('input').attr('disabled', 'disabled');
            $('select').attr('disabled', 'disabled');
            $('#btn-nuevo-asistente').addClass('hidden');
            $('#btn-aceptar-form-pago').addClass('hidden');
            mostrarAlerta('info', 'Tu registro ya se realizo con éxito, comunicate con el administrador ante algún error de llenado.');
        }
    });
}

function getEstatusRegistro() {
    $.post('registroasistencia/getEstatusRegistro', {}, function (response) {
        if (response === 'si') {
            getDatosDeposito();
            getDatosFacturacion();
            getEstatusCambios();
        }
    });
}

function getDatosDeposito() {
    $.post('registroasistencia/getDatosDeposito', {}, function (response) {
        $('#banco').val(response.banco);
        $('#tipo-pago').val(response.tipoPago);
        $('#info-deposito').val(response.info);
        $('#fecha').val(response.fecha);
    }, 'json');
}

function getDatosFacturacion() {
    $.post('registroasistencia/getDatosFacturacion', {}, function (response) {
        $('#razon-social').val(response.razonSocial);
        $('#rfc').val(response.rfc);
        $('#calle').val(response.calle);
        $('#numero').val(response.numero);
        $('#colonia').val(response.colonia);
        $('#municipio').val(response.municipio);
        $('#estado').val(response.estado);
        $('#codigo-postal').val(response.cp);
        $('#correo').val(response.correo);
    }, 'json');
}

function mostrarAlerta(tipo, mensaje) {
    toastr.options.closeButton = true;
    switch (tipo) {
        case 'success':
            toastr.success(mensaje);
            break;
        case 'error':
            toastr.error(mensaje);
            break;
        case 'wanrning':
            toastr.warning(mensaje);
            break;
        case 'info':
            toastr.info(mensaje);
            break;
    }
}    

function jsRemoveWindowLoad() {
    // eliminamos el div que bloquea pantalla
    $("#WindowLoad").remove();
 
}
 
function jsShowWindowLoad(mensaje) {
    //eliminamos si existe un div ya bloqueando
    jsRemoveWindowLoad();
 
    //si no enviamos mensaje se pondra este por defecto
    if (mensaje === undefined) mensaje = "Guardando información<br>Espere por favor";
 
    //centrar imagen gif
    height = 20;//El div del titulo, para que se vea mas arriba (H)
    var ancho = 0;
    var alto = 0;
 
    //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
    if (window.innerWidth == undefined) ancho = window.screen.width;
    else ancho = window.innerWidth;
    if (window.innerHeight == undefined) alto = window.screen.height;
    else alto = window.innerHeight;
 
    //operación necesaria para centrar el div que muestra el mensaje
    var heightdivsito = alto/2 - parseInt(height)/2;//Se utiliza en el margen superior, para centrar
 
   //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
    imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div  style='color:#000;margin-top:" + heightdivsito + "px; font-size:20px;font-weight:bold'>" + mensaje + "</div><img  src='./public/img/cargando.gif'></div>";
 
        //creamos el div que bloquea grande------------------------------------------
        div = document.createElement("div");
        div.id = "WindowLoad";
        div.style.width = ancho + "px";
        div.style.height = alto + "px";
        $("body").append(div);
 
        //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
        input = document.createElement("input");
        input.id = "focusInput";
        input.type = "text";
 
        //asignamos el div que bloquea
        $("#WindowLoad").append(input);
 
        //asignamos el foco y ocultamos el input text
        $("#focusInput").focus();
        $("#focusInput").hide();
 
        //centramos el div del texto
        $("#WindowLoad").html(imgCentro);
 
}

function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#li-regPublico').addClass('active');
}

$("#cancelar").click(function(){
	$("#file-list").empty();
	$("#cancelar").hide("slow");
	$("#cargar").hide("slow");
	fileList.innerHTML="<li class='no-items'> Ningun archivo cargado! </li>";
});


$(':file').change(function () {
	var li = document.createElement("li"),
		progressBarContainer = document.createElement("div"),
		progressBar = document.createElement("div"),		
    	div = document.createElement("div");
	var fileInfo;	
	progressBarContainer.className = "progress-bar-container";
	progressBar.className = "progress-bar";
	progressBar.id="progressBar";
	
	li.appendChild(div);
	
	var file = $("#archivo")[0].files[0];
    var fileName = file.name;
    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
   if(fileExtension=="pdf"){
	    $("#file-list").empty();
	    $("#cancelar").show("slow");
	    $("#cargar").show("slow");
        $('#btn-aceptar-comprobante').show();
		// Present file info and append it to the list of files
		fileInfo = "<div><strong>Nombre:</strong> " + file.name + "</div>";
		fileInfo += "<div><strong>Tamano:</strong> " + parseInt(file.size / 1024, 10) + " kb</div>";
		fileInfo += "<div><strong>Tipo:</strong> " + file.type + "</div>";
		div.innerHTML = fileInfo;
	    fileList.appendChild(li);
	   

		progressBarContainer.appendChild(progressBar);
		li.appendChild(progressBarContainer);
	   
   } else{
	  toastr.options.closeButton = true;
      toastr.error("Tipo de archivo incorrecto ");
   }
});


$("#uploadfile").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("progressBar");
	var idArticulo  = $("#id-articulo-file").val();
    //información del formulario
    var formData = new FormData($(this)[0]);
    var message = "";
    //hacemos la petición ajax  
    $.ajax({
        url: 'registropublico/updloadFile',
        type: 'POST',
        // Form data
        //datos del formulario
        data: formData,
        //necesario para subir archivos via ajax
        cache: false,
        contentType: false,
        processData: false,
        //mientras enviamos el archivo
        beforeSend: function () {
			progressBar.style.width =  "0%";
        },
		// this part is progress bar
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					percentComplete = parseInt(percentComplete * 100);
     			    progressBar.style.width = percentComplete+'%';
					progressBar.innerHTML = percentComplete+ " %"; 
				}
			}, false);
			return xhr;
		},

        //una vez finalizado correctamente
        success: function (response) {
            if (response === 'error-archivo') {
                toastr.options.closeButton = true;
                toastr.error("No selecciono nig&uacute;n archivo.");
            }
            if (response === 'error-subir-archivo') {
                toastr.options.closeButton = true;
                toastr.error("No se pudo cargar el archivo.");
            }
            if ($.isNumeric(response)) {
				$('#id-articulo-autores').val(response);
				$('#modal-autores').removeClass('hidden');
                toastr.options.closeButton = true;
                toastr.success("El archivo se cargo con éxito. Gracias por tu interés por asistir.");
    			$("#container-btn-files").hide("slow");
				$('#modal-autores').removeClass('hidden');
                var delay =2000;
                setTimeout(function(){
                    $(location).attr('href', 'index');
                },delay);
                
			}
        },
        //si ha ocurrido un error
        error: function () {
                toastr.options.closeButton = true;
                toastr.error("Un error a ocurrido!<br>Intentelo mas tarde...");
        }
    });
    return false;	
});

