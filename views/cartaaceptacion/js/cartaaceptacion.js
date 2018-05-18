$(document).ready(function () {
     activarOpcionMenu();
});


//==========================================================================
//FIN DE METODOS PARA CARGAR CARTAS DE ORIGINALIDAD Y CESION DE DERECHOS
//===========================================================================

$("#tbl-articulos tbody tr td.td-tabla").click(function (event) {
	//DECLARACION DE VARIABLES
	var registro = $(this).parent('tr');
    var loc = window.location;
	
	event.preventDefault();
	   var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
	   path = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
	
	//ABRE EL ARCHIVO EN UNA NUEVA PESTAÑA 
	window.open(path + 'docs/'+ registro.find("td").eq(4).html(), '_blank');  

});


function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnCartaAceptacion').addClass('active');
}
