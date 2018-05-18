$(document).ready(function () {
    activarOpcionMenu();
    $('#listaUsuarios').DataTable({
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

function activarOpcionMenu(){
    var id = $('#navbar li.active').attr('id');
    $('#'+id).removeClass('active');
    $('#btn-usuarios').addClass('active');
}

$('#btnNuevoUsuario').click(function () {
    $('#modal_Capturar_usuario').on('shown.bs.modal', function () {
        $('#correo').focus();
    });
    $('#modal-nuevo-registro').modal('show');
});

$('#btn-cancelar-usuario').click(function(event){
	event.preventDefault();
	$('#modal_Capturar_usuario').modal('hide');
});


$('#btn-aceptar-usuario').submit(function(){
    //event.preventDefault();
    var url=$(this).attr('action');
    var data=$(this).serialize();
    $.post('dashboard/xhrInsert',data,function(o){
        $("#listInserts").append('<div>'+o.text+'<a class="del" rel="'+o.id+'" href="#">X</a></div>');
    },'json');
    return false;
});

$('#form-nuevo-registro').submit(function(){
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function(response){
        if (response == 'error-correo') {
            toastr.options.closeButton = true;
            toastr.error("El correo no es valido.");
            $('#correo').focus();
        }
        
        if (response == 'error-pass') {
            toastr.options.closeButton = true;
            toastr.error("Las contrase&ntilde;as no son iguales.");
            $('#password').focus();
        }
        if (response == 'error-emptypass') {
            toastr.options.closeButton = true;
            toastr.error("Debe teclear una Contraseña.");
            $('#password').focus();   
        }        
        
        if (response == 'error-correo-registrado') {
            toastr.options.closeButton = true;
            toastr.error("El correo ya esta registrado.");
            $('#correo').focus();
        }
        
        if (response === 'error-registro') {
            toastr.options.closeButton = true;
            toastr.error("El registro no se pudo realizar.");
            $('#correo').focus();
        }
        
        if (response === 'true') {
            toastr.options.closeButton = true;
            toastr.success("El registro se realizo con exito.");
            $('#modal-nuevo-registro').modal('hide');
            $('.form-control').val('');
             $(location).attr('href', 'usuario');
        }
    });
    return false;
});

$('#form-editar-registro').submit(function(){
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function(response){
        if (response == 'error-correo') {
            toastr.options.closeButton = true;
            toastr.error("El correo no es valido.");
        }
        
        if (response == 'error-pass') {
            toastr.options.closeButton = true;
            toastr.error("Las contrase&ntilde;as no son iguales.");
        }
        
        if (response == 'error-correo-registrado') {
            toastr.options.closeButton = true;
            toastr.error("El correo ya esta registrado.");
        }
        
        if (response === 'error-registro') {
            toastr.options.closeButton = true;
            toastr.error("El registro no se pudo realizar.");
        }
        
        if (response === 'true') {
            toastr.options.closeButton = true;
            toastr.success("El registro se realizo con exito.");
            $('#modal-editar-registro').modal('hide');
            $('.form-control').val('');
             $(location).attr('href', 'usuario');
        }
    });
    return false;
});


    //RUTINA PARA VALIDAR CHECKBOX
 $(".operaciones").click(function (event) {
        var accion =$(".accion",this).attr("name");
        var idusr = $(".accion",this).val();
     
        if(accion=='eliminar'){
            
            if(!confirm("Estas Seguro de Eliminar al Usuario?")){
                return false;
            };
            var parametros = {
                "id" : idusr
            };
            $.ajax({
                url: 'usuario/delete',
                type: 'POST',
                // datos del formulario
                data: parametros,
                //una vez finalizado correctamente
                success: function (response) {
                    if (response === 'true') {
                        toastr.options.closeButton = true;
                        toastr.success("Usuario Eliminado");
                        location.reload();
                    }
                },
                //si ha ocurrido un error
                error: function (response) {
                   if (response === 'false') {
                        toastr.options.closeButton = true;
                        toastr.error("Error al realizar el borrado");
                    }
                }
            });
         }else if(accion=='editar') {
             
            $.post( "usuario/edit",{id:idusr},  function( dato ) {
                $('#ecorreo').val(dato[0].usuCorreo);
                $('#erole').val(dato[0].usuTipo);
                $('#usrid').val(idusr);
                $('#modal-editar-registro').on('shown.bs.modal', function () {
                    $('#ecorreo').focus();
                });
                $('#modal-editar-registro').modal('show');

            }, "json");
                
         }
    });
