$("document").ready(function(){

});

$("#reset-pass").submit(function(event){
	event.preventDefault();
	var url = $(this).attr('action');
	var data = $(this).serialize();
	
	$.post(url, data, function(response){
		if (response == 'error-null') {
			toastr.options.closeButton = true;
			toastr.error("Debe indicar un correo electronico");
			$("#input-correo").focus();
		}

    	if (response == 'error-formato') {
			toastr.options.closeButton = true;
			toastr.error("El formato del correo electronico es incorrecto");
			$("#input-correo").focus();
		}
	
		if (response == 'Correo-bad') {
			toastr.options.closeButton = true;
			toastr.error("Un error a ocurrido al enviar el correo, intentelo mas tarde");
			$("#input-correo").focus();
		}
		
		if (response == 'error-operacion') {
			toastr.options.closeButton = true;
			toastr.error("Un error a ocurrido al intentar actualizar la contrase&ntilde;a");
			$("#input-correo").focus();
		}
		
		if (response == 'error-correo') {
			toastr.options.closeButton = true;
			toastr.error("La dirección de correo electronico no esta registrada");
			$("#input-correo").focus();
		}

		if (response === 'Correo-ok') {
			toastr.options.closeButton = true;
			toastr.success("Se ah enviado la nueva contrase&ntilde;a");
			$("#reset-pass")[0].reset();
			$("#input-correo").focus();
		}
	});	
	return false;
});


