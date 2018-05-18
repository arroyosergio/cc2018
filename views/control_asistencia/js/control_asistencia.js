$(document).ready(function () {
    activarOpcionMenu();
    $('.dataTable').DataTable({
        "language": {
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros",
            "infoFiltered": "(filtrado de _MAX_ registros)",
            "infoEmpty": "No hay registros disponibles",
            "loadingRecords": "Cargando...",
            "processing": "Procesando..."
        },
         "orderCellsTop": true,
		 "order": [ 0, 'asc' ],
		 "ordering": true,
		 "responsive": true,
		 "pageLength": 20,
         "lengthMenu": [[20, 40, 60, -1], [20, 40, 60, "Todos"]]		
    });
});


$('.presentado').click(function () {
    var id = $(this).attr('articulo');
    var estatusPresentado = $(this).is(':checked');
    updateEstatusPresentado(id, estatusPresentado);
});

$('.kit').click(function () {
    var id = $(this).attr('articulo');
    var estatusKit= $(this).is(':checked');
    updateEstatuskit(id, estatusKit);
});


function updateEstatusPresentado(id, estatus){
    $.post('control_asistencia/update_estatus_presentado',{id:id,estatus:estatus},function(response){
        if (response === 'true') {
            mostrarAlerta('success', 'Se guardaron los cambios con éxito');
        }else{
            mostrarAlerta('error', 'Ocurrio un error al guardar los cambios.');
        }
    });
}

function updateEstatuskit(id, estatus){
    $.post('control_asistencia/update_estatus_kit_entregado',{id:id,estatus:estatus},function(response){
        if (response === 'true') {
            mostrarAlerta('success', 'Se guardaron los cambios con éxito');
        }else{
            mostrarAlerta('error', 'Ocurrio un error al guardar los cambios.');
        }
    });
}


function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#btn-ctrl-asistencia').addClass('active');
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
