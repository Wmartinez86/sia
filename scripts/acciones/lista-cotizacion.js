$(document).ready(function() {
            
    $('.actions').change(function() {
        
        // IE Sucks.
        iditem = $(this).attr('id');
        iditem = iditem.split('-');
        iditem = iditem[1];
        
        action = $(this).val();

        switch(action) {
            case 'ecc': // Elaborar Orden de compra
                location.href = 'cuadro-comparativo.php?idcot=' + iditem;
            break;
            case 'cancelar': // Cancelar
                if(window.confirm('¿Está seguro de cancelar esta cotización?'))
                    location.href = 'cotizacionnew.php?cancel&idcot=' + iditem;
            break;
            case 'activar': // Activar
                if(window.confirm('¿Está seguro de activar esta cotización?'))
                    location.href = 'cotizacionnew.php?activate&idcot=' + iditem;
            break;
            case 'imprimir': // Imprimir cuadro
                location.href = 'cuadrocomparativo-print.php?idcot=' + iditem;
            break;
            case 'oc': //
                location.href = 'orden-compra.php?idcot=' + iditem;
            break;
            case 'os':
                location.href = 'orden-servicio.php?idcot=' + iditem;
            break;
            default:
                return false;
        } // endswitch

    });
});
