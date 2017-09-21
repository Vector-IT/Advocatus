function cambioTipo(strID, buscar) {
    var campo = '';

    switch (strID) {
        case '1': //PRODUCTOS
            campo = 'VerProductos';
            break;
    
        case '2': //CATEGORIAS
            campo = 'VerCategorias';
            break;
    }

    if (buscar) {
        numeFilt = $("#NumeFilt").val();
    }
    else {
        numeFilt = '';
    }

    $.post("php/tablaHandler.php", { 
        operacion: "100",
        tabla: "promocionesfiltros",
        field: campo,
        dato: numeFilt
        },
        function (data) {
            if (data.valor.lista != '') {
                var $divContenedor = $("#ValoFilt").parent();
                $("#ValoFilt").remove();
                $divContenedor.html(data.valor.lista);

                $("#ValoFilt").selectpicker().selectpicker("deselectAll");
            }

            if (data.valor.seleccion != '') {
                $("#ValoFilt").selectpicker("val", data.valor.seleccion.split(","));
            }
        }
    );
}

function calcField(campo) {
    cambioTipo($("#NumeTipoFilt").val(), true);
}

function nuevo() {
    var $divContenedor = $("#ValoFilt").parent();
    $("#ValoFilt").remove();
    $divContenedor.html('<input type="hidden" id="ValoFilt" />');
}