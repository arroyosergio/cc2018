

$('#form-nuevo-registro').submit(function(event){
	event.preventDefault();
	var url = $(this).attr('action');
	var data = $(this).serialize();

	$.post(url, data, function(response){

		if (response == 'error-null') {
			toastr.options.closeButton = true;
			toastr.error("Todos los campos son requeridos.");
			$("#input-correo").focus();
		}

		if (response == 'error-correo') {
			toastr.options.closeButton = true;
			toastr.error("El correo no es valido.");
			$("#input-correo").focus();
		}

		if (response == 'error-pass') {
			toastr.options.closeButton = true;
			toastr.error("Las contrase&ntilde;as no son iguales.");
			$("#input-pass").focus();
		}

		if (response == 'error-correo-registrado') {
			toastr.options.closeButton = true;
			toastr.error("El correo ya esta registrado.");
			$("#input-correo").focus();
		}

		if (response === 'error-registro') {
			toastr.options.closeButton = true;
			toastr.error("El registro no se pudo realizar.");
			$("#input-correo").focus();
		}

		if (response === 'true') {
			toastr.options.closeButton = true;
			toastr.success("El registro se realiz&oacute; con &eacute;xito.");
			$("#form-nuevo-registro")[0].reset();
			$("#input-correo").focus();
		}
	});

	return false;

});		

	


	

