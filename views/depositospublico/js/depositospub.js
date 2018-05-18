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
        }
    });
});

$('.detalles').click(function () {
    getDatosDeposito($(this).attr('deposito'));
    getDatosFacturacion($(this).attr('deposito'));
    $('#modal-detalles-deposito').modal('show');
});

$('.email').click(function () {
    var id = $(this).attr('deposito');
    $('#id-deposito-correo').val(id);
    $('#modal-envio-correo').on('shown.bs.modal', function () {
        $('#comentarios').focus();
    }).modal('show');
});

$('.validacion-deposito').click(function () {
    var id = $(this).attr('deposito');
    var estatusDeposito = $(this).is(':checked');
    updateEstatusDeposito(id, estatusDeposito);
});

$('.facturacion').click(function () {
    var id = $(this).attr('deposito');
    var estatusDeposito = $(this).is(':checked');
    updateEstatusFacturacion(id, estatusDeposito);
});


$('#form-envio-correo').submit(function(){
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url,data,function(response){
        if (response === 'Correo-ok') {
            mostrarAlerta('success','Se enviaron los comentarios.');
             $('#modal-envio-correo').modal('hide');
             $('#form-envio-correo textarea').val('');
        }else{
            mostrarAlerta('error', 'Ocurrio un error al enviar los comentarios');
        }
    });
    return false;
});

function updateEstatusDeposito(id, estatus){
    $.post('depositospublico/updateEstatusDeposito',{id:id,estatus:estatus},function(response){
        if (response === 'true') {
            mostrarAlerta('success', 'Se guardaron los cambios con éxito');
        }else{
            mostrarAlerta('error', 'Ocurrio un error al guardar los cambios.');
        }
    });
}

function updateEstatusFacturacion(id, estatus){
    $.post('depositospublico/updateEstatusFacturacion',{id:id,estatus:estatus},function(response){
        if (response === 'true') {
            mostrarAlerta('success', 'Se guardaron los cambios con éxito');
        }else{
            mostrarAlerta('error', 'Ocurrio un error al guardar los cambios.');
        }
    });
}

function updateEstatusCambios(idDeposito, estatus) {
    $.post('depositos/updateEstatusCambios', {id: idDeposito, estatus: estatus}, function (response) {
        if (response === 'true') {
            mostrarAlerta('success','Se cambio el estatus de los cambios con éxito.');
        }else{
            mostrarAlerta('error','Ocurrio un error al activar los cambios.');
        }
    });
}

function getDatosDeposito(reg_id) {
    $.post('depositospublico/getDatosDeposito', {id: reg_id}, function (response) {
    	$('#banco').empty();
        $('#banco').html(response.banco);
        $('#sucursal').empty();
        $('#sucursal').html(response.sucursal);
        $('#transaccion').empty();
        $('#transaccion').html(response.transaccion);
        $('#tipo-pago').empty();
        $('#tipo-pago').html(response.tipo);
        $('#detalles').empty();
        $('#detalles').html(response.info);
        $('#monto').empty();
        $('#monto').html('$ ' + response.monto + '.00');
        $('#fecha-deposito').empty();
        $('#fecha-deposito').html(response.fecha);
        $('#hr-deposito').empty();
        $('#hr-deposito').html(response.hr);
        $('#comprobante').empty();
        $('#comprobante').html('<a target="_blank" href="' + response.comprobante + '"><span class="glyphicon glyphicon-download-alt"></span> Comprobante</a>');
    }, 'json');
}

function getDatosFacturacion(reg_id) {
    $.post('depositospublico/getDatosFacturacion', {id: reg_id}, function (response) {
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

function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#btn-pagos-asistentes').addClass('active');
}

//EXPORTACION A EXCEL
$("#exportXLS").click(function (event) {
    $.ajax({
        url: 'depositospublico/fncExportaExcell',
        //una vez finalizado correctamente
        success: function (response) {
            var loc = window.location;
            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
            path = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
            window.location.href = path + 'xls/RegPubCica.xlsx';
        },
        //si ha ocurrido un error
        error: function (response) {
        }
    });
});

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
