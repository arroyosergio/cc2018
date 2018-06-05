var idArticulo;

$(document).ready(function () {
    activarOpcionMenu();
    $('.dataTable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
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
    idArticulo = $(this).attr('deposito');
    getDatosDeposito($(this).attr('deposito'));
    getDatosFacturacion($(this).attr('deposito'));
    getDocumentosFacturacion($(this).attr('deposito'));
    $('#modal-detalles-facturacion').modal('show');
});

$('#generar').click(function () {
    $('#modal-detalles-facturacion').css('cursor', 'wait');
    $('#generar').css('cursor', 'wait');
    
    $.post('mifacturacion/generarFactura', {id: idArticulo}, function (response) {
        if(response == 'true'){
            getDocumentosFacturacion(idArticulo);
            mostrarAlerta('success', 'La factura fue generada éxitosamente.');
        }else{
            mostrarAlerta('error', response);
        }
        $('#modal-detalles-facturacion').css('cursor', 'pointer');
        $('#generar').css('cursor', 'pointer');        
    });
});

function getDatosDeposito(idArticulo) {
    $.post('mifacturacion/getDatosDeposito', {id: idArticulo}, function (response) {
        $('#monto').empty();
        $('#monto').html('$ ' + response.monto + '.00');
        $('#fecha-deposito').empty();
        $('#fecha-deposito').html(response.fecha);
    }, 'json');
}

function getDatosFacturacion(idArticulo) {
    $.post('mifacturacion/getDatosFacturacion', {id: idArticulo}, function (response) {
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

function getDocumentosFacturacion(idArticulo) {
    $.post('mifacturacion/getDocumentosFacturacion', {id: idArticulo}, function (response) {
        $('#archivopdf').empty();
        $('#archivoxml').empty();
        if(response.generada == 'si'){
            $('#generar').hide();
            $('#archivopdf').html('<a target="_blank" href="' + response.archivopdf + '"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>' );
            $('#archivoxml').html('<a target="_blank" href="' + response.archivoxml + '"><span class="glyphicon glyphicon-download-alt"></span> Descargar</a>');
        }else{
            $('#generar').show();
            $('#archivopdf').html("Inexistente");
            $('#archivoxml').html("Inexistente");
        }
    }, 'json');
}

function activarOpcionMenu() {
    var id = $('#navbar li.active').attr('id');
    $('#' + id).removeClass('active');
    $('#btnFacturacion').addClass('active');
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


