var  numAutores = 0;
var ulrUpfile="";

$(document).ready(function () {
    activarOpcionMenu();
    $("#pais").change(function () {
        dependencia_estado();
//        dependencia_ciudad();
    });
//    $("#estado").change(function () {
//        dependencia_ciudad();
//    });
    $("#estado").attr("disabled", true);
//    $("#ciudad").attr("disabled", true);
    var opcion = $('#tipo-institucion').val();
    $('#input-tipo-institucion').val(opcion);
	
	var filesUpload=document.getElementById("archivo");
	fileList=document.getElementById("file-list");
	$("#cancelar").hide();
	$("#cargar").hide();
});

function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#li-mis-articulos').addClass('active');
}

function dependencia_estado() {
    var code = $("#pais").val();
    $.post('registroarticulo/getEstados', {codigo: code}, function (response) {
        $('#estado option').remove();
        $('#estado').attr('disabled', false);
        $('#estado').append(response);
    });
}

//function dependencia_ciudad() {
//    var code = $("#estado").val();
//    $.post('registroarticulo/getCiudades', {codigo: code}, function (response) {
//        $('#ciudad option').remove();
//        $('#ciudad').attr('disabled', false);
//        $('#ciudad').append(response);
//    });
//}

$('#btn-agregar-autor').click(function () {
    $('#modal-registro-autor').on('shown.bs.modal', function () {
        $('#nombre').focus();
    });
    var opcion = $('#tipo-institucion').val();
    $('#input-tipo-institucion').val(opcion);
	$('#tipo-movimiento').val('insertar');
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-full-width",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.info('Los datos capturados serviran para generar las constancias');
    $('#modal-registro-autor').modal('show');
});

$('.borrar-autor').click(function (){
    return false;
});

$('#btn-aceptar-registro').click(function () {
    var autorContacto = $('input[name="auto-contacto"]:checked').val();
    var idArticulo = $("#id-autor-autores").val();
    $.post('registroarticulo/asignarAutorContacto', {idAutor: autorContacto, idArticulo:idArticulo}, function (response) {
        $(location).attr('href', 'misarticulos');
    });
});


$('#form-registro-autor').submit(function(){
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (response) {
        if (response === 'error-null') {
            toastr.options.closeButton = true;
            toastr.error("Ning&uacute;n campo puede ir vacio.");
        }
        if (response === 'error-registro') {
            toastr.options.closeButton = true;
            toastr.error("No se pudo realizar el registro.");
        }
        if (response === 'error-correo') {
            toastr.options.closeButton = true;
            toastr.error("Ingresa una direcci&oacute;n de correo valida.");
        }
        if (response === 'error-autor-registrado') {
            toastr.options.closeButton = true;
            toastr.error("El autor ya se encuentra registrado.");
        }
        if (response === 'error-numero-autores') {
            toastr.options.closeButton = true;
            toastr.error("Ya no se pueden registar m&aacute;s autores para este articulo.");
        }
        if (response === 'error-update') {
		   toastr.options.closeButton = true;
		   toastr.error("No se pudo actualizar el registro.");
        }		
        if (response === 'true') {
            $('#modal-registro-autor').modal('hide');
            if($('#tipo-movimiento').val()=="insertar"){
				toastr.options.closeButton = true;
				toastr.success("Se ingreso el autor correctamente.");	
				numAutores += 1;
				if (numAutores === 3) {
					$('#btn-agregar-autor').addClass('hidden');
				}		
			}else{
				toastr.options.closeButton = true;
				toastr.success("El autor se actualiz&oacute; correctamente.");				
			}
			
            var id = $('#id-articulo-registro').val();
            $.ajax({
                url: "registroarticulo/getAutoresArticulo",
                type: 'POST',
                data: 'id='+id,
                cache: false,
                success: function (data) {
                    $('#tbl-articulo-autores').html(data);
                }
            });

            $('#form-registro-autor input').val('');
			$('#id-articulo-autores').val(id);
            
        }
    });
    return false;
});


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
   if(fileExtension=="docx" || fileExtension=="doc"){
	   $("#file-list").empty();
	   $("#cancelar").show("slow");
	   $("#cargar").show("slow");
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


function activarOpcionMenu(){
    var id = $('#navbar li.active').attr('id');
    $('#'+id).removeClass('active');
    $('#btnArticulos').addClass('active');
}

$("#uploadfile").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("progressBar");
	var idArticulo  = $("#id-articulo-file").val();
    //información del formulario
    var formData = new FormData($(this)[0]);
    var message = "";
    //hacemos la petición ajax  
    $.ajax({
        url: 'registroarticulo/updloadFile',
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
            //$('#cargando').addClass('hidden');
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
                toastr.success("El archivo se cargo...");
    			$("#container-btn-files").hide("slow");
				$('#modal-autores').removeClass('hidden');
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



/*****************************************************
FUNCTION: Actualiza o Registra nuevo articulo 
******************************************************/
$('#form-registro-articulo').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    
    $.post(url, data, function (response) {

        if(response==='error-apertura'){
			toastr.options.closeButton = true;
			toastr.error("Revise las fechas de apertura y cierre de recepci&oacute;n de art&iacute;culos");
		}
		if (response === 'error-null') {
			toastr.options.closeButton = true;
			toastr.error("Ning&uacute;n campo puede ir vacio.");
		}

		if (response === 'error-articulo-repetido') {
			toastr.options.closeButton = true;
			toastr.error("El nombre del articulo ya esta registrado.");
		}
		
		if (response === 'error-registro') {
			toastr.options.closeButton = true;
			toastr.error("No se pudo realizar el registro.");
		}

		if (response === 'error-actualizacion') {
			toastr.options.closeButton = true;
			toastr.error("Violaci&oacute;n de actualizaci&oacute;n.");
		}		

		if (response === 'actualizado') {
			toastr.options.closeButton = true;
			toastr.success("El Articulo se actualizo correctamente.");
			if($('#tipo_operacion').val()=="actualizar"){
				$("#contenedor-archivo-art").removeClass('hidden');
                var idArticulo=$("#id-articulo-registro").val();
				$("#id-articulo-file").val(idArticulo);
				$("#id-articulo-autores").val(idArticulo);
				$.post('registroarticulo/fncGetVerArticulos', {id_articulo:idArticulo}, function (response) {
					$("#file-list").empty();
					$("#file-list").html(response);
				});
				$('#modal-autores').removeClass('hidden');				
				$.ajax({
					url: "registroarticulo/getAutoresArticulo",
					type: 'POST',
					data: 'id='+idArticulo,
					cache: false,
					success: function (data) {
						$('#tbl-articulo-autores').html(data);
					}
				});
				$('#form-registro-autor input').val('');
				$('#id-articulo-autores').val(idArticulo);

			}
		}
		
		if($.isNumeric( response )){
			$("#contenedor-archivo-art").removeClass('hidden');
			toastr.options.closeButton = true;
			toastr.success("El Articulo se registro correctamente");
			$("#id-articulo-file").val(response);
			$("#id-articulo-registro").val(response);
			$.post('registroarticulo/fncGetVerArticulos', {id_articulo:response}, function (response) {
				$("#file-list").empty();
				$("#file-list").html(response);
			});
		 }
       
    });	
    return false;
});

$('#tipo-institucion').change(function(){
    var opcion = $(this).val();
    $('#input-tipo-institucion').val(opcion);
    if (opcion ==='otro') {
        $('#lbl-input-tipo-institucion').removeClass('hidden');
        $('#input-tipo-institucion').removeClass('hidden');
        $('#input-tipo-institucion').val('');
    }else{
        $('#input-tipo-institucion').val(opcion);
        $('#lbl-input-tipo-institucion').addClass('hidden');
        $('#input-tipo-institucion').addClass('hidden');
    }
});

$('#cancelar-registro').click(function(){
    $('#cargando').removeClass('hidden');
    var idArticulo = $('#id-articulo-autores').val();
    $.post('registroarticulo/borrarRegistroArticulo',{id:idArticulo}, function(response){
        $('#cargando').hide();
        if (response === 'true') {
            $(location).attr('href', 'misarticulos');
        }
    });
});


$('#tbl-articulo-autores').click(function (e) {
     var id = e.target.id;
     var id = id.split('|');
     if (id[0] === 'editar') {
               $.post('registroarticulo/getDetallesAutor', {id: id[1]}, function (response) {
               //**************Manda el id a los campos ocultos ************
			   $('#tipo-movimiento').val('actualizar');
			   $('#id-autor-autores').val(id[1]);
               //***********************************************************
			   $('#nombre').val(response.nombre);
               $('#apellido-paterno').val(response.apellidoPaterno);
               $('#apellido-materno').val(response.apellidoMaterno);
               $('#pais').val(response.pais);
               jQuery.each(response.estados, function (i, val) {
                    $('#estado').append($('<option>', {
                         value: val,
                         text: val
                    }));
               });
               $('#estado').val(response.estado);
               $('#ciudad').val(response.ciudad);
               $('#correo').val(response.correo);
               $('#grado-academico').val(response.gradoAcademico);
               $('#institucion-procedencia').val(response.institucion);
//             $('#tipo-institucion').val(response.tipoInstitucion);
               var otroTipoInstitucion = false;
               $.each(['tecnologica', 'politecnica', 'autonoma', 'instTecnologico', 'centroInvestifacion'], function (index, value) {
                    if (response.tipoInstitucion === value) {
                         otroTipoInstitucion = false;
                         return false;
                    } else {
                         otroTipoInstitucion = true;
                    }
               });
               if (otroTipoInstitucion) {
                    $('#tipo-institucion').val('otro');
                    $('#lbl-input-tipo-institucion').removeClass('hidden');
                    $('#input-tipo-institucion').removeClass('hidden');
                    $('#input-tipo-institucion').val(response.tipoInstitucion);
               } else {
                    $('#tipo-institucion').val(response.tipoInstitucion);
                    $('#input-tipo-institucion').val(response.tipoInstitucion);
                    $('#lbl-input-tipo-institucion').addClass('hidden');
                    $('#input-tipo-institucion').addClass('hidden');
               }

               $('#asistencia-cica').val(response.asistenciaCica);
          }, 'json');
		  $('#modal-registro-autor').on('shown.bs.modal', function () {
          	$('#nombre').focus();
    	  }).modal('show');
     }
     if (id[0] === 'borrar') {
          var idArticulo = $('#id-articulo-registro').val();
          var idAutor = id[1];
          $.post('registroarticulo/borrarAutor', {idArticulo: idArticulo, idAutor: idAutor}, function (response) {
               if (response === 'error-null') {
                    toastr.options.closeButton = true;
                    toastr.error("No se ha indicado a que art&iacute;culo pertence.");
               }

               if (response === 'error-borrar') {
                    toastr.options.closeButton = true;
                    toastr.error("No se pudo borrar al autor.");
               }

               if (response === 'error-validacion') {
                    toastr.options.closeButton = true;
                    toastr.error("No se tienen los permisos para realizar el cambio.");
               }

               if (response === 'true') {
				numAutores--;
				if (numAutores <= 2) {
					$('#btn-agregar-autor').removeClass('hidden');
				}					   
					$.ajax({
						url: "registroarticulo/getAutoresArticulo",
						type: 'POST',
						data: 'id='+idArticulo,
						cache: false,
						success: function (data) {
							$('#detalles-articulo-autores').empty();
							$('#tbl-articulo-autores').empty();
							$('#tbl-articulo-autores').html(data);
						}
					});
               }

          });
     }

});