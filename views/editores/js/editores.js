$(document).ready(function () {
    $('#tbl-articulos').DataTable({
        "language": {
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros",
            "infoFiltered": "(filtrado de _MAX_ registros)",
            "infoEmpty": "No hay registros disponibles",
            "loadingRecords": "Cargando...",
            "processing": "Procesando..."
        }
    });
    activarOpcionMenu();
});

$('.revisado').click(function(){
    var id = $(this).attr('value');
    var estatus = $(this).is(':checked');
    updateEstatusRevisado(id, estatus);
});

function updateEstatusRevisado(id, estatus) {
    $.post('editores/updateEstatusRevisado', {id: id, estatus: estatus}, function (response) {
        console.log(response);
        if (response === 'true') {
            mostrarAlerta('success', 'Se cambio el estatus con éxito.');
        } else {
            mostrarAlerta('error', 'Ocurrio un error al activar los cambios.');
        }
    });
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

function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnArticulos_editor').addClass('active');
} 