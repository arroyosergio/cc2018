$(document).ready(function () {
	activarOpcionMenu();
});

$('#form-cambiar-password').submit(function (Event) {
	Event.preventDefault();
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
        }
    }).done(function(response){
		 if (response === 'true') {
			$("#form-cambiar-password")[0].reset();
		 }
		$("#pass_contra").focus();
	});
    return false;
});

function activarOpcionMenu() {
     var id = $('#navbar li.active').attr('id');
     $('#' + id).removeClass('active');
     $('#btnCambioPas').addClass('active');
}