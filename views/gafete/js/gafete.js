$(document).ready(function () {
    activarOpcionMenu();
});

				  
$("#tbl-articulos tbody tr td.td-tabla").click(function (event) {
	var registro=$(this).parent('tr');
	var idArticulo=registro.find("td").eq(0).html();
	var loc = window.location;
	var parametros = {
          "idArticulo": idArticulo
     };
     $("#lst-asistentes").html("");
     $.ajax({
          url: 'gafete/listaAsistentes',
          type: 'POST',
          //datos del formulario
          data: parametros,
          //una vez finalizado correctamente
          success: function (response) {
           	$("#lst-asistentes").html(response);
          },
		 error:function(){
			toastr.error("Error al obtener los asistentes del art&iacute;culo seleccionado");
			toastr.options.closeButton = true;
		 }
     });
	
	$("#modal-generar-gafetes").on('shown.bs.modal',function(){
	}).modal('show');
});				  

function activarOpcionMenu() {
	 var id = $('#navbar li.active').attr('id');
	 $('#' + id).removeClass('active');
	 $('#btnGefete').addClass('active');
}
