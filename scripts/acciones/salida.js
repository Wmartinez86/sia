$(document).ready(function() {
    
    // Quitando de almacen
    $("#frmproductos").validate();
    
    $("#frmproductos .tabla tbody").find(':checkbox').click(function() {
       var txt = $(this).parent().parent().find(':text');
       if($(this).is(':checked')) {
           txt.removeAttr('disabled');
           txt.parent().parent().find('th').addClass('checkedvalue');
           txt.parent().parent().find('td').addClass('checkedvalue');
           txt.live('blur', myFunc);
       } else {
           txt.attr('disabled', true);
           txt.parent().parent().find('th').removeClass('checkedvalue');
           txt.parent().parent().find('td').removeClass('checkedvalue');
       }
    });
    
    
    
});