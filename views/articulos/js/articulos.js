$(document).ready(function () {
    activarOpcionMenu();
});


$(document).ready(function(){
    $('#tbl-articulos').DataTable({
            "language": {
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros",
                "infoFiltered":   "(filtrado de _MAX_ registros)",
                "infoEmpty": "No hay registros disponibles",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando..."
            }
    });       
});

$("#tbl-articulos tr").click(function() {
    $('#detalles-articulo-autores').empty();
    $('#detalles-articulo-nombre').empty();
    $('#detalles-articulo-area').empty();
    $('#detalles-articulo-tipo').empty();
    $("#checkbox-recibido").removeAttr("checked");
    $("#checkbox-dictaminado").removeAttr("checked");
    $("#checkbox-avisodecambio").removeAttr("checked");
    var id = $(this).find("td").eq(0).html();   
    var nombre = $(this).find("td").eq(1).html();
    var area = $(this).find("td").eq(2).html();
    area = area.toUpperCase();
    $('#detalles-articulo-nombre').text(nombre);
    $('#detalles-articulo-area').text(area);
    $.post('articulos/getAutoresArticulo',{id:id}, function(response){
        $('#detalles-articulo-autores').html(response);
        
    });
     $.post('articulos/getDetallesArticulo',{id:id},function(response){
        $('#detalles-articulo-tipo').html(response);
        //$('#detalles-articulo-archivo').html(data.archivo);
    });
    $('#modal-detalles-articulo').modal('show');
   //window.alert("asd");
});

$('#form-detalles-articulo').submit(function(){
    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.post(url, data, function(response){
           console.log(response);

       if(response==='error-post-actualizardetalles')
       {
         toastr.options.closeButton = true;
         toastr.error("Error al enviar la informaci&oacute;n.");
       }else if(response===true){
            toastr.options.closeButton = true;
            toastr.success("Detalles actualizados.");
       }else if(response===false){
            toastr.options.closeButton = true;
            toastr.error("No se pudo realizar la actualizaci&oacute;n.");    
       }
       else{
         toastr.options.closeButton = true;
         toastr.error(response);
       }
    });
    return false;
});


$('#btn-cancelar-registro').click(function () {
    $('#modal-detalles-articulo').modal('toggle');
});

function activarOpcionMenu(){
    var id = $('#navbar li.active').attr('id');
    $('#'+id).removeClass('active');
    $('#li-articulos').addClass('active');
    }


