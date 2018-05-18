$(document).ready(function () {
    activarOpcionMenu();
});

//=======================================
//METODO PARA ACTIVAR LA OPCION DEL MENU
//=======================================
function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#btn-notificaciones').addClass('active');
}

//=========================================================
//METODO PARA ENVIAR LA NOTIFICACION A LA APLICACION MOVIL
//=========================================================
$('#form-enviar-notificacion').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (response) {
        if (response === "true") {
            toastr.options.closeButton = true;
            toastr.success("Notificación enviada.");
        } else if (response === "false") {
            toastr.options.closeButton = true;
            toastr.error("No se pudo enviar la notificación.");
        }
    });
    return false;
});