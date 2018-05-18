$(document).ready(function () {
	activarOpcionMenu();
    $("#pais").change(function () {
        dependencia_estado();
    });
    datosPerfil();
//    $("#estado").attr("disabled", true);
 var opcion = $('#tipo-institucion').val();
    $('#input-tipo-institucion').val(opcion);

});

$('#tipo-institucion').change(function () {
    var opcion = $(this).val();
    $('#input-tipo-institucion').val(opcion);
    if (opcion === 'otro') {
        $('#lbl-input-tipo-institucion').removeClass('hidden');
        $('#input-tipo-institucion').removeClass('hidden');
        $('#input-tipo-institucion').val('');
    } else {
        $('#input-tipo-institucion').val(opcion);
        $('#lbl-input-tipo-institucion').addClass('hidden');
        $('#input-tipo-institucion').addClass('hidden');
    }
});

$('#form-info-perfil').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (response) {
        if (response === 'error-vacio') {
            toastr.options.closeButton = true;
            toastr.error("Ning&uacute;n campo puede ir vacio.");
        }
        
        if (response === 'error-registro') {
            toastr.options.closeButton = true;
            toastr.error("No se puede actualizar la informaci&oacute;n.");
        }
        
        if (response === 'true') {
            toastr.options.closeButton = true;
            toastr.success("Se actualizo la informaci&oacute;n."); 
			$("btn-guardar").focus();
        }
        
    });
    return false;
});

$('#btn-cambiar-password').click(function(){
    $('#modal-cambiar-password').modal('show');
    return false;
});

$('#form-cambiar-password').submit(function () {
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function (response) {
        if (response === 'error-actualizar') {
            toastr.options.closeButton = true;
            toastr.error("No se puede realizar el cambio de contrase&ntilde;a.");
        }
        
        if (response === 'error-password') {
            toastr.options.closeButton = true;
            toastr.error("Las contrase&ntilde;as no son iguales.");
        }
        
        if (response === 'error-vacio') {
            toastr.options.closeButton = true;
            toastr.error("Ning&uacute;n campo puede ir vacio.");
        }
        
        if (response === 'error-validacion') {
            toastr.options.closeButton = true;
            toastr.error("La contrase&ntilde;a actual es incorrecta.");
        }
        
        if (response === 'true') {
            toastr.options.closeButton = true;
            toastr.success("Se cambio la contrase&ntilde;a correctamente.");
            $('#modal-cambiar-password').modal('hide');
            $('#modal-cambiar-password .form-control').val('');
        }
    });
    return false;
});

function datosPerfil(){   
    $.post('perfil/infoPerfil',{}, function(response){        
        if (response !== 'no-datos') {
           $('#nombre').val(response.nombre); 
           $('#apellido-paterno').val(response.apellidoPaterno); 
           $('#apellido-materno').val(response.apellidoMaterno); 
           $('#ciudad').val(response.ciudad); 
            jQuery.each(response.estados, function (i, val) {
                $('#estado').append($('<option>', {
                    value: val,
                    text: val
                }));
            });
           $('#estado').val(response.estado); 
           $('#pais').val(response.pais); 
           $('#grado-academico').val(response.gradoAcademico);
           $('#institucion-procedencia').val(response.institucionProcedencia); 
           $('#tipo-institucion').val(response.tipoInstitucion);
            var otroTipoInstitucion = false;
            $.each(['tecnologica', 'politecnica', 'autonoma', 'instTecnologico', 'centroInvestifacion'], function (index, value) {
                if (response.tipoInstitucion === value) {
                    otroTipoInstitucion = false;
                    return false;
                } else {
                    otroTipoInstitucion = true;
                }
            });
            if (otroTipoInstitucion) {
                $('#tipo-institucion').val('otro');
                $('#lbl-input-tipo-institucion').removeClass('hidden');
                $('#input-tipo-institucion').removeClass('hidden');
                $('#input-tipo-institucion').val(response.tipoInstitucion);
            } else {
                $('#tipo-institucion').val(response.tipoInstitucion);
                $('#input-tipo-institucion').val(response.tipoInstitucion);
                $('#lbl-input-tipo-institucion').addClass('hidden');
                $('#input-tipo-institucion').addClass('hidden');
            }
           $('#asistencia-cica').val(response.asistenciaCica);
        }
    },'json');
}

function dependencia_estado() {
    var code = $("#pais").val();
    $.post('registroarticulo/getEstados', {codigo: code}, function (response) {
        $('#estado option').remove();
        $('#estado').attr('disabled', false);
        $('#estado').append(response);
    });
}

function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnPerfil').addClass('active');
}