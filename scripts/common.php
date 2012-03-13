<?php 
        require_once('../home.php');
	header("Content-type: text/javascript");
	$param = $_GET['param'];
	$id = (isset($id)) ? $id : 0;
	switch($param) :
		case 'prov' :
?>
				$().ready(function() {
					$("#frmprov").validate({
						rules: {
							ruc: {
								required: true,
								minlength: 11
							}
						},
						messages: {
							ruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							}
						}
					});
					
					$('#nombre').blur(function() {
						if ($('#razonsocial').val()=='')
							$('#razonsocial').val($('#nombre').val());
					});
                    
                    if($('#print')) {
                    	$('#print').click(function(){
                        	window.open('proveedores.php?print&idproveedor=<?php echo $id; ?>','print','location=0,status=0,scrollbars=0,width=800,height=600');
                        })
                    }
				
				})
<?php
	break;
		case 'areas' :
?>
				$().ready(function() {
					$("#frmarea").validate();
				})
<?php
	break;
		case 'opciones' :
?>
				$().ready(function() {
					$("#frmoptions").validate();
				})                                
<?php
	break;
		case 'additem' :
?>
			$().ready(function() {
					$("#frmadditem").validate();
				})
<?php
	break;
		case 'proj' :
?>
				$().ready(function() {
					$("#frmproj").validate();
                    if($('#print')) {
                    	$('#print').click(function(){
                        	window.open('proyectos.php?print&idproyecto=<?php echo $id; ?>','print','location=0,status=0,scrollbars=0,width=800,height=600');
                        })
                    }
				
				})
<?php
		break;
		case 'user' :
                $projs = get_projs();
?>
				$().ready(function() {
					$("#frmuser").validate();

				});
<?php
		break;
		case 'espec' :
?>
				$().ready(function() {
					$("#frmespec").validate();
				})               
<?php
		break;	
		case 'ordencompra' :
                case 'ordenservicio' :
                $projs = get_projs();
?>
				$().ready(function() {
					$("#frmorden").validate({
						rules: {
							oruc: {
								required: true,
								minlength: 11
							},
                            fruc: {
								required: true,
								minlength: 11
							}
						},
						messages: {
							oruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							},
                            fruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							}
						}
					});
                    
                    simg = '<img src="images/loading.gif" alt="Buscando proveedor" />';
                    $("#oruc").bind("keypress",	 function() {
                        if($(this).val().length==11) {
							$.ajax({
                               type: "POST",
                               url: "proveedores.php",
                               data: "getbyruc=true&ruc=" + $(this).val(),
                               success: function(msg){
                                 $('#idprov').empty();
                                 $('#idprov').append(msg);
                               },
                             });
                        }else {
                        	$('#idprov').empty();
                        	$('#idprov').append(simg);
                        }
                    });
                    
                    $("#oruc").bind("blur",	 function() {
                        $.ajax({
                           type: "POST",
                           url: "proveedores.php",
                           data: "getbyruc=true&ruc=" + $(this).val(),
                           success: function(msg){
                             $('#idprov').empty();
                             $('#idprov').append(msg);
                           },
                         });
                    });
                    
                    $('#fecha').datepicker($.datepicker.regional["es"]);
                    
                    
                    $("[name^=cantidad]").bind("blur", function () {
                    	$("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $("[name^=precio]").bind("blur", function () {
                    	$("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $("[name^=total_]").calc(
                        "qty * price",
                        {
                            qty: $("input[name^=cantidad]"),
                            price: $("[name^=precio]")
                        },
                        function (s){
                            return "S/. " + s.toFixed(2);
                        },
                        function ($this){
                            var sum = $this.sum();
                            
                            $("#grandTotal").text(
                                "S/. " + sum.toFixed(2)
                            );
                        }
                    );
                    
                    // add
                   $("#clone").click(function(){
                      $("#firstr").clone(true).appendTo(("#tbody"));
                      $("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $(".ddetalle").click(function(){
                    	return window.confirm('¿Está seguro de eliminar este item?');
                    });
			
				});

<?php
		break;	
		case 'requerimiento' :
?>
				$().ready(function() {
                    // add
                   $("#clone").click(function(){
                      $("#firstr").clone(true).appendTo(("#tbody"));
                      $("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $('#fecha').datepicker($.datepicker.regional["es"]);
                    
                    $(".ddetalle").click(function(){
                    	return window.confirm('¿Está seguro de eliminar este item?');
                    });
			
				});
<?php
		break;	
		case 'cotizacion' :
                    $idreq = $_GET['idreq'];
?>
                $().ready(function() {
                        $("#frmcot").validate();
                        
                    <?php
                        if (!empty($idreq)) :
                                $req = get_requerimiento($idreq);
                                $fecha = strtotime($req["fecha"]);
                    ?>
                    $('#fecha').datepicker("option", "minDate", new Date(<?php print $fecha; ?>));
                    <?php
                        endif;
                    ?>

                    $('#fecha').datepicker($.datepicker.regional["es"]);
                    
                    
                    // add
                   $("#clone").click(function(){
                      $("#firstr").clone(true).appendTo(("#tbody"));
                      $("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $(".ddetalle").click(function(){
                    	return window.confirm('¿Está seguro de eliminar este item?');
                    });
                    
            });
<?php
		break;	
		case 'cotizacionprov' :
?>
				$().ready(function() {
					$("#frmorden").validate({
						rules: {
							oruc: {
								required: true,
								minlength: 11
							},
                            fruc: {
								required: true,
								minlength: 11
							}
						},
						messages: {
							oruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							},
                            fruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							}
						}
					});
                    
                    simg = '<img src="images/loading.gif" alt="Buscando proveedor" />';
                    $("#oruc").bind("keypress",	 function() {
                        if($(this).val().length==11) {
							$.ajax({
                               type: "POST",
                               url: "proveedores.php",
                               data: "getbyruc=true&ruc=" + $(this).val(),
                               success: function(msg){
                                 $('#idprov').empty();
                                 $('#idprov').append(msg);
                               },
                             });
                        }else {
                        	$('#idprov').empty();
                        	$('#idprov').append(simg);
                        }
                    });
                    
                    $("#oruc").bind("blur",	 function() {
                        $.ajax({
                           type: "POST",
                           url: "proveedores.php",
                           data: "getbyruc=true&ruc=" + $(this).val(),
                           success: function(msg){
                             $('#idprov').empty();
                             $('#idprov').append(msg);
                           },
                         });
                    });
                    
                    $('#fecha').datepicker($.datepicker.regional["es"]);
                    
                    
                    $("[name^=cantidad]").bind("blur", function () {
                    	$("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $("[name^=precio]").bind("blur", function () {
                    	$("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $("[name^=total_]").calc(
                        "qty * price",
                        {
                            qty: $("input[name^=cantidad]"),
                            price: $("[name^=precio]")
                        },
                        function (s){
                            return "S/. " + s.toFixed(2);
                        },
                        function ($this){
                            var sum = $this.sum();
                            
                            $("#grandTotal").text(
                                "S/. " + sum.toFixed(2)
                            );
                        }
                    );
                    
                    // add
                   $("#clone").click(function(){
                      $("#firstr").clone(true).appendTo(("#tbody"));
                      $("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $(".ddetalle").click(function(){
                    	return window.confirm('¿Está seguro de eliminar este item?');
                    });
			
				});
<?php
		break;	
		case 'cuadrocomparativo' :
?>
				$().ready(function() {
					$("#frmorden").validate({
						rules: {
							oruc: {
								required: true,
								minlength: 11
							},
                            fruc: {
								required: true,
								minlength: 11
							}
						},
						messages: {
							oruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							},
                            fruc: {
								required: "Por favor ingrese el RUC.",
								minlength: "El RUC debe tener 11 d&iacute;gitos."
							}
						}
					});
                    
                    simg = '<img src="images/loading.gif" alt="Buscando proveedor" />';

                    $("#oruc1").bind("keypress",	 function() {
                        if($(this).val().length==11) {
							$.ajax({
                               type: "POST",
                               url: "proveedores.php",
                               data: "getbyruc=true&ruc=" + $(this).val(),
                               success: function(msg){
                                 $('#idprov1').empty();
                                 $('#idprov1').append(msg);
                               },
                             });
                        }else {
                        	$('#idprov1').empty();
                        	$('#idprov1').append(simg);
                        }
                    });
                    
                    $("#oruc1").bind("blur",	 function() {
                        $.ajax({
                           type: "POST",
                           url: "proveedores.php",
                           data: "getbyruc=true&ruc=" + $(this).val(),
                           success: function(msg){
                             $('#idprov1').empty();
                             $('#idprov1').append(msg);
                           },
                         });
                    });
                    

                    $("#oruc2").bind("keypress",	 function() {
                        if($(this).val().length==11) {
							$.ajax({
                               type: "POST",
                               url: "proveedores.php",
                               data: "getbyruc=true&ruc=" + $(this).val(),
                               success: function(msg){
                                 $('#idprov2').empty();
                                 $('#idprov2').append(msg);
                               },
                             });
                        }else {
                        	$('#idprov2').empty();
                        	$('#idprov2').append(simg);
                        }
                    });
                    
                    $("#oruc2").bind("blur",	 function() {
                        $.ajax({
                           type: "POST",
                           url: "proveedores.php",
                           data: "getbyruc=true&ruc=" + $(this).val(),
                           success: function(msg){
                             $('#idprov2').empty();
                             $('#idprov2').append(msg);
                           },
                         });
                    });



                    $("#oruc3").bind("keypress",	 function() {
                        if($(this).val().length==11) {
							$.ajax({
                               type: "POST",
                               url: "proveedores.php",
                               data: "getbyruc=true&ruc=" + $(this).val(),
                               success: function(msg){
                                 $('#idprov3').empty();
                                 $('#idprov3').append(msg);
                               },
                             });
                        }else {
                        	$('#idprov3').empty();
                        	$('#idprov3').append(simg);
                        }
                    });
                    
                    $("#oruc3").bind("blur",	 function() {
                        $.ajax({
                           type: "POST",
                           url: "proveedores.php",
                           data: "getbyruc=true&ruc=" + $(this).val(),
                           success: function(msg){
                             $('#idprov3').empty();
                             $('#idprov3').append(msg);
                           },
                         });
                    });



                    $("#oruc4").bind("keypress",	 function() {
                        if($(this).val().length==11) {
							$.ajax({
                               type: "POST",
                               url: "proveedores.php",
                               data: "getbyruc=true&ruc=" + $(this).val(),
                               success: function(msg){
                                 $('#idprov4').empty();
                                 $('#idprov4').append(msg);
                               },
                             });
                        }else {
                        	$('#idprov4').empty();
                        	$('#idprov4').append(simg);
                        }
                    });
                    
                    $("#oruc4").bind("blur",	 function() {
                        $.ajax({
                           type: "POST",
                           url: "proveedores.php",
                           data: "getbyruc=true&ruc=" + $(this).val(),
                           success: function(msg){
                             $('#idprov4').empty();
                             $('#idprov4').append(msg);
                           },
                         });
                    });




                    $('#fechaO').datepicker($.datepicker.regional["es"]);
                    $('#fecha1').datepicker($.datepicker.regional["es"]);
                    $('#fecha2').datepicker($.datepicker.regional["es"]);
                    $('#fecha3').datepicker($.datepicker.regional["es"]);
                    $('#fecha4').datepicker($.datepicker.regional["es"]);
                    
                    

                    //primer precio
                    $("[name^=precio1]").bind("blur", function () {
                    	$("[name^=total_1]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio1]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal1").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    $("[name^=total_1]").calc(
                        "qty * price",
                        {
                            qty: $("input[name^=cantidad]"),

                            price: $("[name^=precio1]")
                        },
                        function (s){
                            return "S/. " + s.toFixed(2);
                        },
                        function ($this){
                            var sum = $this.sum();
                            
                            $("#grandTotal1").text(
                                "S/. " + sum.toFixed(2)
                            );
                        }
                    );
                    
                    
					//Segundo precio
                    $("[name^=precio2]").bind("blur", function () {
                    	$("[name^=total_2]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio2]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal2").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    $("[name^=total_2]").calc(
                        "qty * price",
                        {
                            qty: $("input[name^=cantidad]"),

                            price: $("[name^=precio2]")
                        },
                        function (s){
                            return "S/. " + s.toFixed(2);
                        },
                        function ($this){
                            var sum = $this.sum();
                            
                            $("#grandTotal2").text(
                                "S/. " + sum.toFixed(2)
                            );
                        }
                    );
                    
                    //tercer precio
                    $("[name^=precio3]").bind("blur", function () {
                    	$("[name^=total_3]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio3]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal3").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    $("[name^=total_3]").calc(
                        "qty * price",
                        {
                            qty: $("input[name^=cantidad]"),

                            price: $("[name^=precio3]")
                        },
                        function (s){
                            return "S/. " + s.toFixed(2);
                        },
                        function ($this){
                            var sum = $this.sum();
                            
                            $("#grandTotal3").text(
                                "S/. " + sum.toFixed(2)
                            );
                        }
                    );
                    
                    
                    //cuarto precio
                    $("[name^=precio4]").bind("blur", function () {
                    	$("[name^=total_4]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio4]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal4").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    $("[name^=total_4]").calc(
                        "qty * price",
                        {
                            qty: $("input[name^=cantidad]"),

                            price: $("[name^=precio4]")
                        },
                        function (s){
                            return "S/. " + s.toFixed(2);
                        },
                        function ($this){
                            var sum = $this.sum();
                            
                            $("#grandTotal4").text(
                                "S/. " + sum.toFixed(2)
                            );
                        }
                    );                                       
                    
                    // add
                   $("#clone").click(function(){
                      $("#firstr").clone(true).appendTo(("#tbody"));
                      $("[name^=total_]").calc(
                            "qty * price",
                            {
                                qty: $("input[name^=cantidad]"),
                                price: $("[name^=precio]")
                            },
                            function (s){
                                return "S/. " + s.toFixed(2);
                            },
                            function ($this){
                                var sum = $this.sum();
                                
                                $("#grandTotal").text(
                                    "S/. " + sum.toFixed(2)
                                );
                            }
                        );
                    });
                    
                    $(".ddetalle").click(function(){
                    	return window.confirm('¿Está seguro de eliminar este item?');
                    });
			
				});
<?php
		break;
		case 'doc' :
?>
				$().ready(function() {
					$("#fromdoc").validate();
				})
<?php
		break;
		case 'fuente' :
?>
				$().ready(function() {
					$("#frmfont").validate();
				})
<?php
    break;
    case 'listacot' :
?>
    $(document).ready(function() {
            
        $('.actions').bind('change', function() {
            iditem = $(this).attr('id').split('-')[1];
            action = $(this).val();
            console.log(action);
            
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
<?php
    break;
    case 'lista' :
?>
    $().ready(function() {
				$(".cancelorder").click(function(){
                    return window.confirm('¿Está seguro de cancelar esta orden?');
                });
                
                $(".activateorder").click(function(){
                    return window.confirm('¿Estpa seguro de activar esta orden?');
                });
            });

<?php
		break;
		case 'inf' :
?>
				$().ready(function() {
					$('#fecha1').datepicker($.datepicker.regional["es"]);
                    $('#fecha2').datepicker($.datepicker.regional["es"]);
				})
<?php
		default:
?>
<?php
	endswitch;
?>
function pop(win, w, h) {
	window.open(win,'chat','location=0,status=0,scrollbars=0,width='+w+',height='+h);
    return false;
}
