$(document).ready(function () {
    $('#mConvocatoria').hover(function (e) {
        $(this).toggleClass('open');
    });
	$('#mAutores').hover(function (e) {
        $(this).toggleClass('open');
    });
	$('#mAsistentes').hover(function (e) {
        $(this).toggleClass('open');
    });	
	$('#mSesion').hover(function (e) {
        $(this).toggleClass('open');
    });		
	
});

	
$("#user-login").click(function(event){
	event.preventDefault();
	$('#form-login')[0].reset();
	$('#modal-login').on('shown.bs.modal', function () {
	  $('#login-correo').focus();
	}).modal('show');	
});

$('#form-login').submit(function(){
	var url = $(this).attr('action');
	var data = $(this).serialize();
	$.post(url, data, function(response){
		if (response === 'error-correo') {
			toastr.options.closeButton = true;
			toastr.error("El correo no es valido.");
		}
		if (response === 'error-login') {
			$('#login-password').val('');
			toastr.options.closeButton = true;
			toastr.error("Correo o contrase&ntilde;a incorrectos.");
		}
		if (response === 'primer-ingreso') {
			location.reload();
			//window.location.href = 'perfil';
		}
		if (response === 'true') {
			location.reload();
		}
	});
	return false;
});