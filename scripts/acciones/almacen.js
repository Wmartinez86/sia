$(document).ready(function () {
    
    // Crear NEAS
    
    $("#frmalmacen").validate();

    simg = '<img src="/images/loading.gif" alt="Buscando proveedor" />';

    $('#fecha').datepicker($.datepicker.regional["es"]);

    $("[name^=cantidad]").bind("blur", function () {
        $("[name^=total_]").calc("qty * price", {
            qty: $("input[name^=cantidad]"),
            price: $("[name^=precio]")
        }, function (s) {
            return "S/. " + s.toFixed(2);
        }, function ($this) {
            var sum = $this.sum();

            $("#grandTotal").text("S/. " + sum.toFixed(2));
        });
    });

    $("[name^=precio]").bind("blur", function () {
        $("[name^=total_]").calc("qty * price", {
            qty: $("input[name^=cantidad]"),
            price: $("[name^=precio]")
        }, function (s) {
            return "S/. " + s.toFixed(2);
        }, function ($this) {
            var sum = $this.sum();

            $("#grandTotal").text("S/. " + sum.toFixed(2));
        });
    });

    $("[name^=total_]").calc("qty * price", {
        qty: $("input[name^=cantidad]"),
        price: $("[name^=precio]")
    }, function (s) {
        return "S/. " + s.toFixed(2);
    }, function ($this) {
        var sum = $this.sum();

        $("#grandTotal").text("S/. " + sum.toFixed(2));
    });

    // add
    $("#clone").click(function () {
        //$("#firstr").clone(true).appendTo(("#tbody"));
        newTr = $("#firstr").clone(true);
        newTr.attr('id', "tr" + Math.random());
        newTr.appendTo('#tbody');
        
        $("[name^=total_]").calc("qty * price", {
            qty: $("input[name^=cantidad]"),
            price: $("[name^=precio]")
        }, function (s) {
            return "S/. " + s.toFixed(2);
        }, function ($this) {
            var sum = $this.sum();

            $("#grandTotal").text("S/. " + sum.toFixed(2));
        });
    });

    $(".ddetalle").click(function () {
        return window.confirm('¿Está seguro de eliminar este item?');
    });

});