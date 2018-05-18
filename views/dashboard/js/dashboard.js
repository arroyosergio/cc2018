var idArticulo = '';
$(document).ready(function () {
     activarOpcionMenu();
	$(this).scrollTop(0);
     $('#listaTrabajos').DataTable({
          "language": {
               "search": "Buscar:",
               //"orderCellsTop": true,//modificado
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

     //EVITA LA SELECCION DE TEXTO AL DAR DOBLE CLICK 
     $("body").css("-webkit-user-select", "none");
     $("body").css("-moz-user-select", "none");
     $("body").css("-ms-user-select", "none");
     $("body").css("-o-user-select", "none");
     $("body").css("user-select", "none");

     //OBTIENE LOS DATOS DEL ARTICULO, ASI COMO LOS AUTORES QUE PERTENECEN AL MISMO
     var table = $('#listaTrabajos').DataTable();
     $('#listaTrabajos tbody').on('dblclick', 'tr', function () {
          var data = table.row(this).data();
          idArticulo = data[0];
          $.post('dashboard/fncGetDetTrabajo', {id: data[0]}, function (response) {
               $('#datosarticulo').html(response);
          });
          $.post('dashboard/fncGetDetTrabajoAutores', {idArticulo: data[0]}, function (response) {
               $('#datosautores').html(response);
               $('#detalletrabajo').modal('show');
          });
          /*$.post('dashboard/getCartaOriginalidad', {id: data[0]}, function (response) {
               $('#div-carta-originalidad').html(response);
          });
          $.post('dashboard/getCartaDerechos', {id: data[0]}, function (response) {
               $('#div-carta-derechos').html(response);
          });*/
          //=========================CARTA ACEPTACION ==================
          /*$.post('dashboard/getCartaAceptacion',{id:idArticulo},function(response){
        	    $('#documentos-actuales').empty();
        	    $('#documentos-actuales').html(response);
        	});*/
          //============================================================
          /*$.post('dashboard/getEstatusCartas',{id:idArticulo}, function(response){
               var estatusCartas = $('input:radio[name=validacion-cartas]');
               if (response === 'si') {
                    estatusCartas.filter('[value=si]').prop('checked',true);
               }else{
                    estatusCartas.filter('[value=no]').prop('checked',true);
               }
          });*/
          /*$.post('dashboard/getEstatusRecibo',{id:idArticulo}, function(response){
               var estatusCartas = $('input:radio[name=validacion-recibo]');
               if (response === 'si') {
                    estatusCartas.filter('[value=si]').prop('checked',true);
               }else{
                    estatusCartas.filter('[value=no]').prop('checked',true);
               }
          });*/
          /*$.post('dashboard/getReciboPago',{id:idArticulo}, function(response){
               $('#div-recibo-pago').html(response);
          });*/
          

     });
});


//===================================================
//METODOS PARA GENERAR CONSTANCIA EN FORMA MANUAL 
//===================================================
$('#frm-generar-constancia').submit(function () {
 	var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post('dashboard/generarConstancia', data, function (response) {
        toastr.options.closeButton = true;
        toastr.success("Se genero correctamente la Constancia...");
    });
    $('#modal-generar-constancia').modal('hide');
 });

$('#btn_Generar_Constancia').click(function () {
	$('#modal-generar-constancia input').val('');
    $('#modal-generar-constancia').on('shown.bs.modal', function () {
        $('#nombre-articulo').focus();
    }).modal('show');
});
//  FIN DE METODOS PARA GENERA CONSTANCIAS

//=============================
//METODO PARA EXPORTACION A EXCEL
//=============================
$("#exportXLS").click(function (event) {
     $.ajax({
          url: 'dashboard/fncExportaExcell',
          //una vez finalizado correctamente
          success: function (response) {
               var loc = window.location;
               var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
               path = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
               window.location.href = path + 'xls/ArtCica.xlsx';
          },
          //si ha ocurrido un error
          error: function (request, status, error) {
              console.error('error ocurrido: '+error);
          }
     });
});

//=================================
//METODO PARA DESCARGAR ARCHIVO
//=================================
$("#listaTrabajos tbody span").click(function (event) {
     var parametros = {
          "ID": event.target.id
     };

     $.ajax({
          url: 'dashboard/fncGetVerArticulos',
          type: 'POST',
          //datos del formulario
          data: parametros,
          //una vez finalizado correctamente
          success: function (response) {
               $('#descargaArchivos').modal('show');
               $('#versiones').html(response);
          }
     });
     //OBTIENE LA URL DEL PROYECTO
     //var loc = window.location;
     //var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
     //var path=loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
     //$('#descargaArchivos').modal('show');
     //versiones="<div class='row'><div class='col-md-1'><span class='glyphicon glyphicon-floppy-save'></div><div class='col-md-6'>"+
     //          "<a href='"+path+'docs/'+event.target.id+"'><b>"+event.target.id+"</b></a></div></div>";
     //$('#versiones').html(response);
     //window.location.href = path+'docs/'+event.target.id;  //redirecciona
});

//=========================================
//METODO PARA ENVIAR COMENTARIO AL AUTOR
//=========================================
$('#dialog-form').find("form").on("submit", function (event) {
     event.preventDefault();
     $.ajax({
          url: 'dashboard/fncSendComentario',
          type: 'POST',
          //datos del formulario
          data: $('#frmComentarios').serialize(),
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
               $('#modal-comentarios').modal('hide');
          },
          //si ha ocurrido un error
          error: function (response) {
          }
     });
});

//======================================
//METODO PARA VALIDAR CHECKBOX
//======================================
$("#listaTrabajos tbody tr  input").click(function (event) {
     var $input = $(this);
     var objCheck = event.target;
     if (objCheck.name == "Dictaminado" && $input.is(":checked")) {
          $('#modal-comentarios').on('shown.bs.modal', function () {
               $('#comentario1').text("Le informamos que su trabajo fue aceptado. Le solicitamos descargar las cartas de originalidad y cesión de derechos y subirlos firmados y escaneados en formato PDF.");
               $('#idArticulo').val(objCheck.id);
               $('#campo').val(objCheck.name);
               $('#comentario1').focus();
          }).modal({backdrop: "static"});
     } else if (objCheck.name == "AvisoCambio" && $input.is(":checked")) {
          $('#modal-comentarios').on('shown.bs.modal', function () {
               $('#comentario1').text("");
               $('#idArticulo').val(objCheck.id);
               $('#campo').val(objCheck.name);
               $('#comentario1').focus();
          }).modal({backdrop: "static"});
     }
     var parametros = {
          "campo": 'art' + objCheck.name,
          "estado": $input.is(":checked") ? 'si' : 'no',
          "id": objCheck.id
     };
     $.ajax({
          url: 'dashboard/fncUpdateTrabajo',
          type: 'POST',
          //datos del formulario
          data: parametros,
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

//=================================
//METODO PARA GENERAR GAFETE
//=================================
$('#form-generar-gafete').submit(function(){
    var action = $(this).attr('action');
    var data = $(this).serialize();
});

//=======================================
//METODO PARA ACTIVAR LA OPCION DEL MENU
//=======================================
function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btn-articulos').addClass('active');
}


// Validacion de cartas
/*$("#btn-form-validacion-cartas").click(function () {
     var estatus = $("input[name=validacion-cartas]:checked").val();
     $.post('dashboard/updateEstatusCartas', {id: idArticulo, estatus: estatus}, function (response) {
          if (response === 'false') {
               toastr.options.closeButton = true;
               $('#detalletrabajo').modal('hide');
               toastr.error("Ocurrio un error al actualizar el estatus de los documentos");
          }
          if (response === 'true') {
               toastr.options.closeButton = true;
               $('#detalletrabajo').modal('hide');
               toastr.success("Se actualizo el estus de los documentos");
          }
     });

});*/

// Validacion de recibo
/*$("#btn-form-validacion-recibo").click(function () {
     var estatus = $("input[name=validacion-recibo]:checked").val();
     $.post('dashboard/updateEstatusRecibo', {id: idArticulo, estatus: estatus}, function (response) {
          if (response === 'false') {
               toastr.options.closeButton = true;
               $('#detalletrabajo').modal('hide');
               toastr.error("Ocurrio un error al actualizar el estatus de los documentos");
          }
          if (response === 'true') {
               toastr.options.closeButton = true;
               $('#detalletrabajo').modal('hide');
               toastr.success("Se actualizo el estus de los documentos");
          }
     });

});*/


/*$('#btn-cancelar-usuario').click(function (event) {
     event.preventDefault();
     $('#modal_Capturar_usuario').modal('hide');
});*/



/*$('#prueba a').click(function (e) {
     e.preventDefault();
     $(this).tab('show');
});*/

//=================================================================
//Subir carta de aceptacion
//=================================================================
/*$('#input-carta-aceptacion').change(function (event) {
   var id = idArticulo;//$('#update-articulo-id').val();
   var files = event.target.files;
   var data = new FormData();
   $.each(files, function (key, value)
   {
        data.append(key, value);
   });
   data.append('idArticulo', id);
   $.ajax({
  	  url: 'dashboard/subir_carta_aceptacion',
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
                  toastr.success("Se subio la carta de aceptaci&oacute;n");
             }
             $('#cargando').addClass('hidden');

        },
        error: function (response) {
             message = $("<span class='error'>Ha ocurrido un error.</span>");
        }
   });
   return false;
});*/





