$(document).ready(function () {
     activarOpcionMenu();
});


//==========================================================================
//FIN DE METODOS PARA CARGAR CARTAS DE ORIGINALIDAD Y CESION DE DERECHOS
//===========================================================================

$("#tbl-articulos tbody tr td.td-tabla").click(function (event) {
	var registro=$(this).parent('tr');
	var idArticulo=registro.find("td").eq(0).html();
	var loc = window.location;
	var parametros = {
          "idArticulo": idArticulo
     };

     $.ajax({
          url: 'constancias/generarConstancia',
          type: 'POST',
          //datos del formulario
          data: parametros,
          //una vez finalizado correctamente
          success: function () {
			toastr.success("Generaci&oacute;n de constancia existosa...");
			toastr.options.closeButton = true;
            
			var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
			var path = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
  			//ABRE EL ARCHIVO EN UNA NUEVA PESTAÑA 
			//alert( path + "docs/"+registro.find("td").eq(0).html() +"/constancia_art_"+registro.find("td").eq(0).html()+".pdf");
		 	window.open(path + "docs/"+registro.find("td").eq(0).html() +"/constancia_art_"+registro.find("td").eq(0).html()+".pdf", '_blank'); 

          },
		 error:function(){
			toastr.error("Error al generar constancia...&#13;&#10; Informe al comit&eacute; organizador");
			toastr.options.closeButton = true;
		 }
     });
	
	
	//DECLARACION DE VARIABLES
	/*var registro = $(this).parent('tr');
    var loc = window.location;
	
	event.preventDefault();
	   var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
	   path = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
	
	//ABRE EL ARCHIVO EN UNA NUEVA PESTAÑA 
	window.open(path + 'docs/'+ registro.find("td").eq(4).html(), '_blank');  */

});


function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnConstancia').addClass('active');
}
