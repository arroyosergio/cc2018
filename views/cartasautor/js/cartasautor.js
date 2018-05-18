$(document).ready(function () {
	
     activarOpcionMenu();
	fileListOrigen=document.getElementById("file-carta-origen");
	fileListCesion=document.getElementById("file-carta-derechos");
});

//===============================================
//METODO PARA VALIDAR Y SELECCIONAR EL ARCHIVO
//===============================================
$('#input-carta-originalidad').change(function () {
	var li = document.createElement("li"),
		progressBarContainer = document.createElement("div"),
		progressBar = document.createElement("div"),		
    	div = document.createElement("div");
	var fileInfo;	
	progressBarContainer.className = "progress-bar-container";
	progressBar.className = "progress-bar";
	progressBar.id="progressBar";
	
	li.appendChild(div);
	
	var file = $("#input-carta-originalidad")[0].files[0];
    var fileName = file.name;
    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
   if(fileExtension=="pdf"){
	   $("#file-carta-origen").empty();
	   //$("#cancelar").show("slow");
	   $("#cargar_originalidad").show("slow");
		// Present file info and append it to the list of files
		fileInfo = "<div><strong>Nombre:</strong> " + file.name + "</div>";
		fileInfo += "<div><strong>Tamano:</strong> " + parseInt(file.size / 1024, 10) + " kb</div>";
		fileInfo += "<div><strong>Tipo:</strong> " + file.type + "</div>";
		div.innerHTML = fileInfo;
	    fileListOrigen.appendChild(li);

		progressBarContainer.appendChild(progressBar);
		li.appendChild(progressBarContainer);
	   
   } else{
	  toastr.options.closeButton = true;
      toastr.error("Tipo de archivo incorrecto ");
   }
});

//==============================================
//METODO PARA VALIDAR Y SELECCIONAR EL ARCHIVO
//==============================================
$('#input-carta-derechos').change(function () {
	var li_der = document.createElement("li"),
		progressBarContainerDer = document.createElement("div"),
		progressBarDer = document.createElement("div"),		
    	div_der = document.createElement("div");
	var fileInfo;	
	progressBarContainerDer.className = "progress-bar-container";
	progressBarDer.className = "progress-bar";
	progressBarDer.id="id_progressBarDer";
	
	li_der.appendChild(div_der);
	
	var file = $("#input-carta-derechos")[0].files[0];
    var fileName = file.name;
    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
	
   if(fileExtension=="pdf"){
	   $("#file-carta-derechos").empty();
	   //$("#cancelar").show("slow");
	   $("#cargar_cesion").show("slow");
		// Present file info and append it to the list of files
		fileInfo = "<div><strong>Nombre:</strong> " + file.name + "</div>";
		fileInfo += "<div><strong>Tamano:</strong> " + parseInt(file.size / 1024, 10) + " kb</div>";
		fileInfo += "<div><strong>Tipo:</strong> " + file.type + "</div>";
		div_der.innerHTML = fileInfo;
	    fileListCesion.appendChild(li_der);
		progressBarContainerDer.appendChild(progressBarDer);
		li_der.appendChild(progressBarContainerDer);
	   
   } else{
	  toastr.options.closeButton = true;
      toastr.error("Tipo de archivo incorrecto ");
   }
});

//======================================================
//METODOS PARA INICIAR LA CARGA DEL ARCHIVO SELECCIONADO
//======================================================
$("#form-subir-cartas-origen").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("progressBar");
    //información del formulario
    var formData = new FormData($(this)[0]);
    //hacemos la petición ajax  
    $.ajax({
        url: 'cartasautor/subirCartaOriginalidad',
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
			if (response === 'true') {
                toastr.options.closeButton = true;
                toastr.success("El archivo se cargo correctamente...");
    			$("#cargar_originalidad").hide("slow");
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

$("#form-subir-cartas-derechos").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("id_progressBarDer");
    //información del formulario
    var formData = new FormData($(this)[0]);
    //hacemos la petición ajax  
    $.ajax({
        url: 'cartasautor/subirCartaCesionDerechos',
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
			if (response === 'true') {
                toastr.options.closeButton = true;
                toastr.success("El archivo se cargo correctamente...");
    			$("#cargar_cesion").hide("slow");
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

//==========================================================================
//FIN DE METODOS PARA CARGAR CARTAS DE ORIGINALIDAD Y CESION DE DERECHOS
//===========================================================================

$("#tbl-articulos tbody tr td.td-tabla").click(function (event) {

	$('#input-carta-originalidad').val('');
	$('#input-carta-derechos').val('');
	
	$("#cargar_cesion").hide();
	$("#cargar_originalidad").hide();

	$('#input-carta-originalidad').attr('disabled','disabled');
	$('#input-carta-derechos').attr('disabled','disabled');
	
	$("#file-carta-origen").text('Ningun archivo seleccionado...');
	$("#file-carta-derechos").text('Ningun archivo seleccionado...');
     var registro = $(this).parent('tr');
     $('#tbl-ver-articulo-autores').empty();
     $('#ver-articulo-nombre').empty();
	
     var id = registro.find("td").eq(0).html();

	 $('#id-articulo-original').val(id);
	 $('#id-articulo-cesion').val(id);
	
	$.post('cartasautor/getDetallesArticulo', {id: id}, function (data) {
	}).done(function(data){
	      //funcion necesaria para poder utilizar los atributos JSON como propiedades
     	  var  objJson = jQuery.parseJSON(data); 
		  if (objJson.cambio === 'si') {
			   $('#input-carta-originalidad').removeAttr('disabled');
			   $('#input-carta-derechos').removeAttr('disabled');
		  }	

		  if (objJson.cambio === 'no') {
				$('#btn-aceptar-cartas').hide();	  
		  }	
	});
	//=====================================================================
	//METODO PARA OBTENER LAS CARTAS DE ORIGINALIDAD Y CESION DE DERECHOS
	//EN CASO QUE YA ESTAS CARGADAS EN LA PLATAFORMA
	//=====================================================================
	$.post('cartasautor/getCartas',{id:id},function(response){
          $('#documentos-actuales').empty();
          $('#documentos-actuales').html(response);
     });
     $('#modal-ver-articulo').on('shown.bs.modal', function () {
     }).modal('show');
     
});



$("#btn-aceptar-cartas").click(function(Event){
  	var id=$('#id-articulo-original').val();
	
	 $.ajax({
		 url: 'cartasautor/update_status_cambios',
		 method: "POST",
		 data: {idArticulo:id,
				status:'no'
			   },
		 //una vez finalizado correctamente
		 success: function (response) {
			  if (response) {
				   toastr.options.closeButton = true;
				   toastr.success("Proceso realizado...");
				   $('#modal-ver-articulo').modal('hide');
			  } else  {
				   toastr.options.closeButton = true;
				   toastr.error("Proceso no realizado...");
			  }
		 }
	});
});



function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnCtrlCartas').addClass('active');
}
