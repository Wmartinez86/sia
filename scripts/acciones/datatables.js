$(document).ready(function() {

	var dontSort = [];
    $('#data-table thead th').each( function () {
        if ( $(this).hasClass( 'no_sort' )) {
            dontSort.push( { "bSortable": false } );
        } else {
            dontSort.push( null );
        }
    } );

    $('#data-table').dataTable({
    	"aoColumns": dontSort,
        "aaSorting": [[ 0, "desc" ]],
        "oLanguage": {
				"sLengthMenu": "_MENU_ registros por página",
				"sSearch": "Buscar",
				"sInfo": "Mostrando _START_ a _END_ registros de  _TOTAL_",
				"sInfoFiltered": " - filtrado de _MAX_ registros",
				"oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                  }
				}
    });
} );