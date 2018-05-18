$(document).ready(function () {
     activarOpcionMenu();
     $("#pais").change(function () {
          dependencia_estado();
     });
     $("#add-autor-pais").change(function () {
          registro_autor_dependencia_estado();
     });
//    Obtiene el tipo de institucion cuando se edita un autor
     var opcion = $('#tipo-institucion').val();
     $('#input-tipo-institucion').val(opcion);

//    Obtiene el tipo de institucion cuando se agrega un autor
     var tipoInstAdd = $('#add-autor-tipo-institucion').val();
     $('#add-autor-input-tipo-institucion').val(tipoInstAdd);
	//=======================================================
	//LLAMADO DE FUNCION PARA OBTENER LA VALIDACION 
	//DE LLENADO LOS DATOS DEL PERFIL
	//=======================================================
	validar_perfil();

});




$('#tipo-institucion').change(function () {
     var opcion = $(this).val();
     $('#input-tipo-institucion').val(opcion);
     if (opcion === 'otro') {
          $('#lbl-input-tipo-institucion').removeClass('hidden');
          $('#input-tipo-institucion').removeClass('hidden');
          $('#input-tipo-institucion').val('');
     } else {
          $('#input-tipo-institucion').val(opcion);
          $('#lbl-input-tipo-institucion').addClass('hidden');
          $('#input-tipo-institucion').addClass('hidden');
     }
});

$('#add-autor-tipo-institucion').change(function () {
     var opcion = $(this).val();
     $('#add-autor-input-tipo-institucion').val(opcion);
     if (opcion === 'otro') {
          $('#add-autor-lbl-input-tipo-institucion').removeClass('hidden');
          $('#add-autor-input-tipo-institucion').removeClass('hidden');
          $('#add-autor-input-tipo-institucion').val('');
     } else {
          $('#add-autor-input-tipo-institucion').val(opcion);
          $('#add-autor-lbl-input-tipo-institucion').addClass('hidden');
          $('#add-autor-input-tipo-institucion').addClass('hidden');
     }
});

function dependencia_estado() {
     var code = $("#pais").val();
     $.post('misarticulos/getEstados', {codigo: code}, function (response) {
          $('#estado option').remove();
          $('#estado').attr('disabled', false);
          $('#estado').append(response);
     });
}

function registro_autor_dependencia_estado() {
     var code = $("#add-autor-pais").val();
     $.post('misarticulos/getEstados', {codigo: code}, function (response) {
          $('#add-autor-estado option').remove();
          $('#add-autor-estado').attr('disabled', false);
          $('#add-autor-estado').append(response);
     });
}

$("#tbl-articulos tbody tr td.td-tabla").click(function (event) {
     var registro = $(this).parent('tr');
     /*$('#li-detalles').addClass('active');
     $('#detalles').addClass('active');*/
     $('#tbl-ver-articulo-autores').empty();
     $('#ver-articulo-nombre').empty();
     /*$('#update-articulo-id').empty();
     $('#btn-agregar-autor').addClass('hidden');
     $('#btn-subir-version').addClass('hidden');
     $('#li-cartas').addClass('hidden');*/
     var id = registro.find("td").eq(0).html();
     var nombre = registro.find("td").eq(1).html();
     var area = registro.find("td").eq(2).html();
     $('#update-articulo-id').val(id);
     $('#ver-articulo-nombre').val(nombre);
     switch (area) {
          case 'Ciencias administrativas y sociales':
               area = 'CAYS';
               break;
          case 'Experiencia en formación CA':
               area = 'EFC';
               break;
          case 'Ciencias agropecuarias':
               area = 'CA';
               break;
          case 'Ciencias naturales y exactas':
               area = 'CNYE';
               break;
          case 'Ciencias de ingeniería y tecnología':
               area = 'CIYT';
               break;
          case 'Educación':
               area = 'E';
               break;
     }
     $('#ver-articulo-area').val(area);
	
	
     $.post('misarticulos/get_show_AutoresArticulo', {id: id}, function (response) {
          $('#tbl-ver-articulo-autores').append(response);
     });
	
	
     //====================CARTA DE ACEPTACION==============================
     /*$.post('misarticulos/getCartaAceptacion', {id: id}, function (datos) {
    	if(datos.archivo!== null){
    		var strCartaAceptacion="<a href='docs/"+ datos.archivo+"' class='btn btn-link'><i class='glyphicon glyphicon-save-file'></i> Carta de aceptaci&oacute;n</a>";
    		$('#cartaaceptacion-archivo').html("<a href='docs/"+ datos.archivo+"' class='btn btn-link'><i class='glyphicon glyphicon-save-file'></i> Carta de aceptaci&oacute;n</a>");
    	}
     },'json');*/
     //====================CONSTANCIA==============================
     /*var strConstancia="<a href='constancia/generar?id="+id+"' class='btn btn-primary'><span class='glyphicon glyphicon-save'></span> descarga de Constancia</a>";*/
 	 //var strConstancia="<button class='genera_constancia' value='12' ><span class='glyphicon glyphicon-file'></span> Generar constancia</button>";
    	//var strConstancia="<button id='generacion_constancia' value='"+id+"' type='submit' class='btn btn-info pull-right'><span class='glyphicon glyphicon-file'></span> Generar constancia</button>";
     /*$('#link_constancia').html(strConstancia);*/
     //=========================FIN DE CARTA DE ACEPTACION==================     
     $.post('misarticulos/get_show_DetallesArticulo', {id: id}, function (data) {
          /*if (data.cambio === 'si') {
               $('#ver-articulo-nombre').removeAttr('disabled');
               $('#ver-articulo-area').removeAttr('disabled');
               $('#ver-articulo-tipo').removeAttr('disabled');
               $('#btn-guardar-cambios').removeClass('hidden');
               $('#btn-subir-version').removeClass('hidden');
               $('#btn-agregar-autor').removeClass('hidden');
          }*/
          /*if (data.dictaminado === 'si') {
               $('#li-cartas').removeClass('hidden');
//               $.post('misarticulos/getEstatusCartas', {id: id}, function (response) {
//                    if (response === 'no') {
                         $('#input-carta-originalidad').removeAttr('disabled');
//                         $('#input-carta-originalidad').val(data.cartaOriginalidad);
                         $('#input-carta-cesion').removeAttr('disabled');
//                         $('#input-carta-cesion').val(data.cartaDerechos);
                         $('#li-detalles').removeClass('active');
                         $('#detalles').removeClass('active');
                         $('#li-cartas').addClass('active');
                         $('#cartas').addClass('active');
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
                         toastr.info('Descarga las formatos, llenalos y sube tus archivos para continuar con el proceso.');
//                    }
//               });
          }*/
          $('#ver-articulo-tipo').val(data.tipo);
          $('#ver-articulo-archivo').html(data.archivo);
     }, 'json');
     /*$.post('misarticulos/getEstatusRecibo', {id: id}, function (response) {
          if (response === 'no') {
               $('#input-recibo-pago').removeAttr('disabled');
          }
     });*/
     /*$.post('misarticulos/getCartas',{id:id},function(response){
          $('#documentos-actuales').empty();
          $('#documentos-actuales').html(response);
     });*/
     //VALIDAR QUE LA PRESENTACION DEL ARTOCULO SE HALLA REALIZA
     /*$.get('misarticulos/validarPresentacionArt',{id:id},function(response){
    	 if(response)
    		 $('#li-constancia').removeClass('hidden');
     });*/
     //$('#modal-ver-articulo').modal('show');

     $('#modal-ver-articulo').on('shown.bs.modal', function () {
     }).modal('show');
     
});


 /*   $("#constancia_pdf").find("form").on("submit", function (event) {
        //event.preventDefault();
		//var buttons = document.getElementById("constancia_pdf");
	    $.get('constancia/generar',{id:$('#generacion_constancia').val()},function(response){
	    	//alert(response);
	    });
	});*/

$('#btn-agregar-autor').click(function () {
     $('#add-id-articulo-autor').val($('#update-articulo-id').val());
     $('#modal-registro-autor').modal('show');
});

//=====================================================================
//FUNCTION PARA VALIDAR QUE LOS DATOS DEL PERFIL ESTEN LLENOS ANTES 
//DE INGRESAR UN ART.
//=====================================================================
$('#btn-registro-articulo').click(function (){
	var response=document.getElementById('perfil_valido').value;	
	if (response === 'FALSE') {
		   toastr.options.closeButton = true;
		   toastr.error("Para poder agregar Art&iacute;culos es necesario ingresar los datos del perfil");
	}else if(response=== 'TRUE'){
		     window.location.href="registroarticulo";   
	}
});

//=====================================================================
//FUNCTION PARA OBTENER LA VALIDACION DEL LLENADO DE DATOS DEL PERFIL 
//=====================================================================
function validar_perfil(){
	// METODO PARA RECUPERAR LA VALIDACION DE PERFIL
	$.post('misarticulos/get_validar_perfil', function (response) {
		document.getElementById('perfil_valido').value=response;
	});
}

$('#form-registro-autor').submit(function () {
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
          if (response === 'true') {
               location.reload();
//            $('#modal-registro-autor').modal('hide');
//            $('#form-registro-autor input').val('');
//            var id = $('#id-articulo').val();
//            $.ajax({
//                url: "misarticulos/getAutoresArticulo",
//                type: 'POST',
//                data: 'id='+id,
//                cache: false,
//                success: function (data) {
//                    $('#detalles-articulo-autores').empty();
//                    $('#detalles-articulo-autores').html(data);
//                }
//            });
          }
     });
     return false;
});

$('#form-update-articulo').submit(function () {
     var url = $(this).attr('action');
     var data = $(this).serialize();
     $.post(url, data, function (response) {
          if (response === 'error-null') {
               toastr.options.closeButton = true;
               toastr.error("Ning&uacute;n campo puede ir vacio.");
          }
          if (response === 'error-update') {
               toastr.options.closeButton = true;
               toastr.error("No se pudo realizar la actualizaci&oacute;n.");
          }
          if (response === 'error-validacion') {
               toastr.options.closeButton = true;
               toastr.error("No se tienen los permisos para realizar el cambio.");
          }
          if (response === 'true') {
               location.reload();
          }
     });
     return false;
});

$('#form-editar-autor').submit(function () {
     var url = $(this).attr('action');
     var data = $(this).serialize();
     $.post(url, data, function (response) {
          if (response === 'error-null') {
               toastr.options.closeButton = true;
               toastr.error("Ning&uacute;n campo puede ir vacio.");
          }
          if (response === 'error-update') {
               toastr.options.closeButton = true;
               toastr.error("No se pudo realizar el registro.");
          }
          if (response === 'error-correo') {
               toastr.options.closeButton = true;
               toastr.error("Ingresa una direcci&oacute;n de correo valida.");
          }
          if (response === 'true') {
//            $('#modal-editar-autor').modal('hide');
//            toastr.options.closeButton = true;
//            toastr.success("Se actualiz&oacute; el autor.");
               location.reload();
          }
     });
     return false;
});

$('#input-carta-originalidad').change(function () {
     var id = $('#update-articulo-id').val();
     var files = event.target.files;
     var data = new FormData();
     $.each(files, function (key, value)
     {
          data.append(key, value);
     });
     data.append('idArticulo', id);
     $.ajax({
          url: 'misarticulos/subirCartaOriginalidad',
          type: 'POST',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
               $('#cargando').removeClass('hidden');
          },
          success: function (response) {
               if (response === 'error-formato-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("El formato del archivo no es valido.");
               }

               if (response === 'error-null') {
                    toastr.options.closeButton = true;
                    toastr.error("No se ha indicado a que art&iacute;culo pertence.");
               }

               if (response === 'error-subir-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("No se pudo subir el archivo.");
               }
               if (response === 'error-validacion') {
                    toastr.options.closeButton = true;
                    toastr.error("No se tienen los permisos para realizar el cambio.");
               }
               if (response === 'true') {
                    $('#cargando').addClass('hidden');
                    toastr.options.closeButton = true;
                    toastr.success("Se subio la carta de originalidad");
               }
               $('#cargando').addClass('hidden');

          },
          error: function () {
               message = $("<span class='error'>Ha ocurrido un error.</span>");
          }
     });
});

$('#input-carta-cesion').change(function () {
     var id = $('#update-articulo-id').val();
     var files = event.target.files;
     var data = new FormData();
     $.each(files, function (key, value)
     {
          data.append(key, value);
     });
     data.append('idArticulo', id);
     $.ajax({
          url: 'misarticulos/subirCartaCesionDerechos',
          type: 'POST',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
               $('#cargando').removeClass('hidden');
          },
          success: function (response) {
               if (response === 'error-formato-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("El formato del archivo no es valido.");
               }

               if (response === 'error-null') {
                    toastr.options.closeButton = true;
                    toastr.error("No se ha indicado a que art&iacute;culo pertence.");
               }

               if (response === 'error-subir-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("No se pudo subir el archivo.");
               }
               if (response === 'error-validacion') {
                    toastr.options.closeButton = true;
                    toastr.error("No se tienen los permisos para realizar el cambio.");
               }
               if (response === 'true') {
                    $('#cargando').addClass('hidden');
                    toastr.options.closeButton = true;
                    toastr.success("Se subio la carta de cesi&oacute;n de derechos");
               }
               $('#cargando').addClass('hidden');

          },
          error: function () {
               message = $("<span class='error'>Ha ocurrido un error.</span>");
          }
     });
});

//Subir recibo de pago

$('#input-recibo-pago').change(function () {
     var id = $('#update-articulo-id').val();
     var files = event.target.files;
     var data = new FormData();
     $.each(files, function (key, value)
     {
          data.append(key, value);
     });
     data.append('idArticulo', id);
//     $('#modal-ver-articulo').modal('hide');
     $.ajax({
          url: 'misarticulos/subirReciboPago',
          type: 'POST',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
               $('#cargando').removeClass('hidden');
          },
          success: function (response) {
               if (response === 'error-formato-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("El formato del archivo no es valido.");
               }

               if (response === 'error-null') {
                    toastr.options.closeButton = true;
                    toastr.error("No se ha indicado a que art&iacute;culo pertence.");
               }

               if (response === 'error-subir-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("No se pudo subir el archivo.");
               }
               if (response === 'error-validacion') {
                    toastr.options.closeButton = true;
                    toastr.error("No se tienen los permisos para realizar el cambio.");
               }
               if (response === 'true') {
                    $('#cargando').addClass('hidden');
                    toastr.options.closeButton = true;
                    toastr.success("Se subio la carta de cesi&oacute;n de derechos");
               }
               $('#cargando').addClass('hidden');

          },
          error: function () {
               message = $("<span class='error'>Ha ocurrido un error.</span>");
          }
     });
//     $('#modal-ver-articulo').modal('show');
     return false;
});

//Subir nueva versión del documento
$('#input-nueva-version').change(function () {
     var id = $('#update-articulo-id').val();
     var files = event.target.files;
     var data = new FormData();
     $.each(files, function (key, value)
     {
          data.append(key, value);
     });
     data.append('idArticulo', id);
     $.ajax({
          url: 'misarticulos/updateVersionArchivo',
          type: 'POST',
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
               $('#modal-ver-articulo').modal('hide');
               $('#cargando').removeClass('hidden');
          },
          success: function (response) {
               if (response === 'error-formato-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("El formato del archivo no es valido.");
               }

               if (response === 'error-null') {
                    toastr.options.closeButton = true;
                    toastr.error("No se ha indicado a que art&iacute;culo pertence.");
               }

               if (response === 'error-subir-archivo') {
                    toastr.options.closeButton = true;
                    toastr.error("No se pudo subir el archivo.");
               }
               if (response === 'error-validacion') {
                    toastr.options.closeButton = true;
                    toastr.error("No se tienen los permisos para realizar el cambio.");
               }
               if (response === 'true') {
                    $('#cargando').addClass('hidden');
                    toastr.options.closeButton = true;
                    toastr.success("Se actualiz&oacute; la versi&oacute;n del art&iacute;culo.");
               }

          },
          //si ha ocurrido un error
          error: function () {
               message = $("<span class='error'>Ha ocurrido un error.</span>");
          }
     });
});

$('#tbl-ver-articulo-autores').click(function (e) {
     var id = e.target.id;
     var id = id.split('|');
     if (id[0] === 'editar') {
          $.post('misarticulos/getDetallesAutor', {id: id[1]}, function (response) {
//            Manda el id al campo oculto
               $('#id-articulo-autor').val(id[1]);
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
//            $('#tipo-institucion').val(response.tipoInstitucion);
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
          $('#modal-editar-autor').modal('show');
     }
     if (id[0] === 'borrar') {
          var idArticulo = $('#update-articulo-id').val();
          var idAutor = id[1];
          $.post('misarticulos/borrarAutor', {idArticulo: idArticulo, idAutor: idAutor}, function (response) {
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
                    location.reload();
               }

          });
     }

});



function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnArticulos').addClass('active');
}

/*
[object Object]
*/
/*
[object Object]
*/