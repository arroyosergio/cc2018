var blnModif=false;
$(document).ready(function () {
     activarOpcionMenu();
	 fileList=document.getElementById("file-list");
	 $("#cargar").hide();
	
     $('#tbl-cartas').DataTable({
          "language": {
               "search": "Buscar:",
               "zeroRecords": "No se encontraron registros",
               "infoFiltered": "(filtrado de _MAX_ registros)",
               "infoEmpty": "No hay registros disponibles",
               "loadingRecords": "Cargando...",
               "processing": "Procesando..."
          },
		 "orderCellsTop": true,
		 "order": [ 0, 'desc' ],
		 "ordering": true,
		 "responsive": true,
		 "pageLength": 20,
         "lengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]]
     });
     
     $('#chkvalidacion-cartas').click(function (event) {
    	 blnModif=true;
    	 var id = $('#id-articulo-file').val();
    	 var status='no';
    	 if($("#chkvalidacion-cartas").is(':checked'))
    		  status='si'; 
         $.ajax({
             url: 'cartas/updateEstatusCartas',
             type: 'POST',
             //datos del formulario
             data: {"idArticulo":id,
            	    "status":status
                   },
             //una vez finalizado correctamente
             success: function (response) {
                  if (response) {
                       toastr.options.closeButton = true;
                       toastr.success("Proceso realizado...");
                  } else  {
                       toastr.options.closeButton = true;
                       toastr.error("Proceso no realizado...");
                  }
             },
             //si ha ocurrido un error
             error: function (response) {
             }
        });
     });
     
	
     $('#chkact-cambios-cartas').click(function (event) {
    	 blnModif=true;
    	 var id = $('#id-articulo-file').val();
    	 var status='no';
    	 if($("#chkact-cambios-cartas").is(':checked'))
    		  status='si'; 
         $.ajax({
             url: 'cartas/updateCambioCartas',
             type: 'POST',
             //datos del formulario
             data: {"idArticulo":id,
            	    "status":status
                   },
             //una vez finalizado correctamente
             success: function (response) {
                  if (response) {
                       toastr.options.closeButton = true;
                       toastr.success("Proceso realizado...");
                  } else  {
                       toastr.options.closeButton = true;
                       toastr.error("Proceso no realizado...");
                  }
             },
             //si ha ocurrido un error
             error: function (response) {
             }
        });
     });
	
	
     $('#btn_enviar').click(function (event) {
    	 var id = $('#id-articulo-file').val();
    	 var comentario = $('#comentario_val_cartas').val();
         $.ajax({
             url: 'cartas/fncSendComentario',
             type: 'POST',
             //datos del formulario
             data: {"idArticulo":id,
            	    "comentario":comentario
                   },
             //una vez finalizado correctamente
             success: function (response) {
                  if (response === "Correo-ok") {
                       toastr.options.closeButton = true;
                       toastr.success("Correo Enviado...");
                  } else if (response === "Correo-bad") {
                       toastr.options.closeButton = true;
                       toastr.error("Fallo al enviar Correo...");
                  } else if (response === "Correo-no") {
                       toastr.options.closeButton = true;
                       toastr.success("Proceso realizado...");
                  }
             },
             //si ha ocurrido un error
             error: function (response) {
             }
        });
     });
	
	$(this).scrollTop(0);
});

$('.detalles').click(function(){
	$('#modal-detalles-cartas').modal('show').one('hidden.bs.modal', function(e) {
		if(blnModif)
		     location.reload();
		// this handler is detached after it has run once
    });
	var idArticulo=$(this).attr('carta');
    $('#id-articulo-file').val(idArticulo);
    $.post('dashboard/getCartaOriginalidad', {id: $(this).attr('carta')}, function (response) {
         $('#div-carta-originalidad').html(response);
    });
    $.post('dashboard/getCartaDerechos', {id: $(this).attr('carta')}, function (response) {
         $('#div-carta-derechos').html(response);
    });
    $.post('dashboard/getCartaAceptacion',{id:$(this).attr('carta')},function(response){
  	    $('#documentos-actuales').empty();
  	    $('#documentos-actuales').html(response);
  	});
    $('#id_Articulo_carta_aceptacion').html($(this).attr('carta'));
    $.post('cartas/getEstatusCartas',{id:$(this).attr('carta')}, function(response){
         if (response == 'si') {
        	 $("#chkvalidacion-cartas").attr('checked', true);
         }else{
        	 $("#chkvalidacion-cartas").attr('checked', false);
         }
    });
    $.post('cartas/getCambioCartas',{id:$(this).attr('carta')}, function(response){
         if (response == 'si') {
        	 $("#chkact-cambios-cartas").attr('checked', true);
         }else{
        	 $("#chkact-cambios-cartas").attr('checked', false);
         }
    });	
	$("#file-list").empty();
    
}); 


function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btn-ver-cartas').addClass('active');
}

//Validación de cartas
$("#btn-form-validacion-cartas").click(function () {
     var estatus = $("input[name=validacion-cartas]:checked").val();
     $.post('cartas/updateEstatusCartas', {id: idArticulo, estatus: estatus}, function (response) {
          if (response === 'false') {
               toastr.options.closeButton = true;
               $('#detalletrabajo').modal('hide');
               toastr.error("Ocurrio un error al actualizar el estatus de los documentos");
          }
          if (response === 'true') {
               toastr.options.closeButton = true;
               $('#detalletrabajo').modal('hide');
               toastr.success("Se actualizo el status de los documentos");
          }
     });
});

//=================================
//METODO PARA SELECCIONAR ARCHIVO
//=================================
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
	   //$("#cargar").show("slow");
	   $("#cargar").fadeToggle("slow", "linear")
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

//=============================================
//METODO PARA CARGA EL DOCUMENTO SELECCIONADO
//=============================================
$("#uploadfile").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("progressBar");
    //información del formulario
    var formData = new FormData($(this)[0]);
    //hacemos la petición ajax  
    $.ajax({
        url: 'cartas/subir_carta_aceptacion',
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
			if(response==='error-null'){
                toastr.options.closeButton = true;
                toastr.error("Imposible ligar la carta con el art&iacute;culo.");				
			}
            if (response === 'error-archivo') {
                toastr.options.closeButton = true;
                toastr.error("No selecciono nig&uacute;n archivo.");
            }
            if (response === 'error-subir-archivo') {
                toastr.options.closeButton = true;
                toastr.error("No se pudo cargar el archivo.");
            }
            if (response==='true') {
				$("#cargar").hide("slow");
				//$('#tbl-cartas').DataTable().ajax.reload()
                toastr.options.closeButton = true;
                toastr.success("La carta se cargo correctamente...");
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


