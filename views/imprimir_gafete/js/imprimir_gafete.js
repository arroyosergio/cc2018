$(document).ready(function () {
     activarOpcionMenu();
});



//=================================
//METODO PARA GENERAR GAFETE
//=================================
$('#form-generar-gafete').submit(function(event){
    var action = $(this).attr('action');
    var data = $(this).serialize();
});

//=======================================
//METODO PARA ACTIVAR LA OPCION DEL MENU
//=======================================
function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btn-imp-gafete').addClass('active');
}


