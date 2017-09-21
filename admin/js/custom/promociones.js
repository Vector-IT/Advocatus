function verFiltros(strID) {
    location.href = "objeto/promocionesfiltros.php?NumeProm=" + strID;
}

function validar() {
    var fechDesd = $("#FechDesd").val();
    var fechHast = $("#FechHast").val();
    var cantPerm = $("#CantPerm").val();
    var tipoProm = $("#NumeTipoProm").val();
    var valoProm = $("#ValoProm").val().trim();

    var mensaje = '';

    if (fechDesd != '' && fechHast != '') {
        fechDesd = moment(fechDesd);
        fechHast = moment(fechHast);

        if (fechHast < fechDesd) {
            mensaje = "El rango de fechas de vigencia de la promoción es incorrecto!<br>";
        }
    }
    else {
        if (cantPerm == '') {
            mensaje = "Debe establecer un rango de fechas de vigencia o una cantidad máxima de utilización de la promoción!<br>"
        }
    }

    switch (tipoProm) {
        case '1':
            if (valoProm == '') {
                mensaje+= "Debe establecer el porcentaje de descuento!"
            }
            else if (isNaN(valoProm)) {
                mensaje+= "El porcentaje ingresado no es un número válido!";
            }
            break;
        case '2':
            if (valoProm == '') {
                mensaje+= "Debe establecer el monto de descuento!"
            }
            else if (isNaN(valoProm)) {
                mensaje+= "El monto ingresado no es un número válido!";
            }
            break;
    
        case '3':
            $("#ValoProm").val('');
            break;
    }

    if (mensaje != '') {
        $("#txtHint").html(mensaje);
        $("#divMsj").removeClass("alert-success");
        $("#divMsj").addClass("alert-danger");

        $("#actualizando").hide();
        $("#divMsj").show();

        return false;
    }
    else {
        return true;
    }

}