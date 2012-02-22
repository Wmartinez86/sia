$(document).ready(function() {
    
    // Quitando de almacen
    $("#frmproductos").validate();
    
    $('#fecha').datepicker($.datepicker.regional["es"]);
    
    $("#frmproductos .tabla tbody").find(':checkbox').click(function() {
       var txt = $(this).parent().parent().find(':text');
       if($(this).is(':checked')) {
           txt.removeAttr('disabled');
           txt.parent().parent().find('th').addClass('checkedvalue');
           txt.parent().parent().find('td').addClass('checkedvalue');
       } else {
           txt.attr('disabled', true);
           txt.val('');
           txt.parent().parent().find('th').removeClass('checkedvalue');
           txt.parent().parent().find('td').removeClass('checkedvalue');
       }
    });
    
    // Validación de cantidades
    $("#frmproductos .tabla tbody").find(':text').bind('blur', function() {
        var total = $(this).parent().parent().find(':hidden').val();
        var salida = $(this).val();
        if((total-salida) < 0)  {
            alert('El saldo disponible es '+ total + '. Ingresa un valor menor o igual al saldo.');
            $(this).val('');
            $(this).focus();
        } else if (salida <= 0) {
            alert('Ingresa un valor mayor que 0');
            $(this).val('');
            $(this).focus();
        }
    });
    
    // Quitando Tecla Enter
    $("#frmproductos").bind("keypress", function(e) {
        if (e.keyCode == 13) return false;
    });
    
});