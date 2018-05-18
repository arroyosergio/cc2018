$(document).ready(function () {
    activarOpcionMenu();
    fileListXml=document.getElementById("file-list-xml");
    fileListPdf=document.getElementById("file-list-pdf");
    $('.dataTable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        "language": {
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros",
            "infoFiltered": "(filtrado de _MAX_ registros)",
            "infoEmpty": "No hay registros disponibles",
            "loadingRecords": "Cargando...",
            "processing": "Procesando..."
        }
    });
});
//==============================================
//METODO QUE MUESTRA LOS DATOS DE FACTURACION
//==============================================
$('.detalles').click(function () {
    $("#cargar_xml").hide();
    $("#cargar_pdf").hide();
    document.getElementById("file-list-pdf").innerHTML="";
    document.getElementById("file-list-xml").innerHTML="";
    getDatosDeposito($(this).attr('deposito'));
    getDatosFacturacion($(this).attr('deposito'));
    getDocumentosFacturacion($(this).attr('deposito'));
    $("#id-art-xml").val($(this).attr('deposito')); 
    $("#id-art-pdf").val($(this).attr('deposito')); 
    $('#modal-detalles-facturacion').modal('show');
});

//==============================================
//RECUPERA LOS DATOS DEL DEPOSITO
//==============================================
function getDatosDeposito(idArticulo) {
    $.post('factura/getDatosDeposito', {id: idArticulo}, function (response) {
        $('#monto').empty();
        $('#monto').html(response.monto);
        $('#fecha-deposito').empty();
        $('#fecha-deposito').html(response.fecha);
    }, 'json');
}

//==============================================
//RECUPERA LOS DATOS DE FACTURACION
//==============================================
function getDatosFacturacion(idArticulo) {
    $.post('factura/getDatosFacturacion', {id: idArticulo}, function (response) {
        $('#razon-social').empty();
        $('#razon-social').html(response.razonSocial);
        $('#rfc').empty();
        $('#rfc').html(response.rfc);
        $('#correo-contacto').empty();
        $('#correo-contacto').html(response.correo);
        $('#calle').empty();
        $('#calle').html(response.calle);
        $('#colonia').empty();
        $('#colonia').html(response.colonia);
        $('#numero').empty();
        $('#numero').html(response.numero);
        $('#estado').empty();
        $('#estado').html(response.estado);
        $('#municipio').empty();
        $('#municipio').html(response.municipio);
        $('#cp').empty();
        $('#cp').html(response.cp);
    }, 'json');
}

//==============================================
//RECUPERA LOS ARCHIVOS XML Y PDF DE LA FACTURA
//==============================================
function getDocumentosFacturacion(idArticulo) {
    $.post('factura/getDocumentosFacturacion', {id: idArticulo}, function (response) {
        $('#archivopdf').empty();
        $('#archivoxml').empty();
        if(response.archivopdf){
            $('#archivopdf').html('<a target="_blank" href="./docs/' + response.archivopdf + '"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>' );
        }else{
            $('#archivopdf').html("Inexistente");
        }
        if(response.archivoxml){
            $('#archivoxml').html('<a target="_blank" href="./docs/' + response.archivoxml + '"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>');
        }else{
            $('#archivoxml').html("Inexistente");
        }
    }, 'json');
}

function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#btn-facturas').addClass('active');
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


//======================================
//METODO PARA VALIDAR CHECKBOX
//======================================
$("#tbl-depositos tbody tr  input").click(function (event) {
    var $input = $(this);
    var objCheck = event.target;
    var parametros = {
         "campo": objCheck.name,
         "estado": $input.is(":checked") ? 'si' : 'no',
         "id": objCheck.id
    };
    $.ajax({
         url: 'factura/fncFactCorrecta',
         type: 'POST',
         //datos del formulario
         data: parametros,
         //una vez finalizado correctamente
         success: function (response) {
              if (response == false) {
                   toastr.options.closeButton = true;
                   toastr.error("Problemas al realizar proceso...");
              } else if (response == true) {
                   toastr.options.closeButton = true;
                   toastr.success("Proceso realizado...");
              }
         },
         //si ha ocurrido un error
         error: function (response) {
         }
    });

});

//===============================================================
//METODO PARA LIMPIAR LA PROPIEDAD VALUE Y
//PARA PODER SELECCIONAR MAS DE UNA VEZ EL MISMO ARCHIVO
//===============================================================
$('#archivo_pdf').click(function (event) {
    event.target.value=null;
});


//=================================
//METODO PARA SELECCIONAR ARCHIVO
//=================================
$('#archivo_pdf').change(function () {
	var li_pdf = document.createElement("li"),
		progressBarContainer_Pdf = document.createElement("div"),
		progressBar_Pdf = document.createElement("div"),		
    	div_Pdf = document.createElement("div");
	var fileInfo;	
	progressBarContainer_Pdf.className = "progress-bar-container";
	progressBar_Pdf.className = "progress-bar";
    progressBar_Pdf.id="progressBar_pdf";
    div_Pdf.id="div_pdf";
	var file = $("#archivo_pdf")[0].files[0];
    var fileName = file.name;
    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
   if(fileExtension=="pdf"){
	   $("#file-list-pdf").empty();
	   $("#cargar_pdf").fadeIn("slow", "linear")
		// Present file info and append it to the list of files
		fileInfo = "<div><strong>Nombre:</strong> " + file.name + "</div>";
		fileInfo += "<div><strong>Tamano:</strong> " + parseInt(file.size / 1024, 10) + " kb</div>";
		fileInfo += "<div><strong>Tipo:</strong> " + file.type + "</div>";
        div_Pdf.innerHTML = fileInfo;
        fileListPdf.appendChild(div_Pdf);
        progressBarContainer_Pdf.appendChild(progressBar_Pdf);
        div_Pdf.appendChild(progressBarContainer_Pdf);
	   
   } else{
	  toastr.options.closeButton = true;
      toastr.error("Tipo de archivo incorrecto ");
   }
});

//===============================================================
//METODO PARA LIMPIAR LA PROPIEDAD VALUE Y
//PARA PODER SELECCIONAR MAS DE UNA VEZ EL MISMO ARCHIVO
//===============================================================
$('#archivo_xml').click(function (event) {
    event.target.value=null;
});

//=================================
//METODO PARA SELECCIONAR ARCHIVO
//=================================
$('#archivo_xml').change(function () {
    
	var progressBarContainer = document.createElement("div"),
		progressBar = document.createElement("div"),		
    	div = document.createElement("div");
	var fileInfo;	
	progressBarContainer.className = "progress-bar-container";
	progressBar.className = "progress-bar";
	progressBar.id="progressBar";
	var file = $("#archivo_xml")[0].files[0];
    var fileName = file.name;
    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
   if(fileExtension=="xml"){
	    $("#file-list-xml").empty();
	    $("#cargar_xml").fadeIn("slow", "linear")
		// Present file info and append it to the list of files
		fileInfo = "<div><strong>Nombre:</strong> " + file.name + "</div>";
		fileInfo += "<div><strong>Tamano:</strong> " + parseInt(file.size / 1024, 10) + " kb</div>";
		fileInfo += "<div><strong>Tipo:</strong> " + file.type + "</div>";
		div.innerHTML = fileInfo;
	    fileListXml.appendChild(div);
		progressBarContainer.appendChild(progressBar);
		div.appendChild(progressBarContainer);
	   
   } else{
	  toastr.options.closeButton = true;
      toastr.error("Tipo de archivo incorrecto ");
   }
});

//======================================================
//METODOS PARA INICIAR LA CARGA DEL ARCHIVO PDF 
//======================================================
$("#form-subir-pdf").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("progressBar_pdf");
    //información del formulario
    var formData = new FormData($(this)[0]);
    //hacemos la petición ajax  
    $.ajax({
        url: 'factura/subirFacturaPdf',
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
    			$("#cargar_pdf").hide("slow");
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


//======================================================
//METODOS PARA INICIAR LA CARGA DEL ARCHIVO XML 
//======================================================
$("#form-subir-xml").submit(function(event){
	event.preventDefault();
	var progressBar = document.getElementById("progressBar");
    //información del formulario
    var formData = new FormData($(this)[0]);
    //hacemos la petición ajax  
    $.ajax({
        url: 'factura/subirFacturaXml',
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
    			$("#cargar_xml").hide("slow");
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
