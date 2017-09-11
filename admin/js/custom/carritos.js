function verUsuario(strID) {
    if ($("#NumeUser" + strID).val() != '') {
        location.href= "objeto/usuarios.php?id=" + $("#NumeUser" + strID).val();
    }
    else {
        location.href= "objeto/invitados.php?id=" + $("#NumeInvi" + strID).val();
    }
}

function verProductos(strID) {
    location.href= "objeto/carritosdetalles.php?NumeCarr=" + strID;
}

function procesar(strID) {
    $.post("php/tablaHandler.php", { 
        operacion: "100",
        tabla: "carritos",
        field: "Procesar",
        data: strID
        },
        function (data) {
            if (data.valor === true) {
                $("#txtHint").html("Datos actualizados!");
                $("#divMsj").removeClass("alert-danger");
                $("#divMsj").addClass("alert-success");

                listarcarritos();
            }
            else {
                $("#txtHint").html("Error al actualizar los datos.");
                $("#divMsj").addClass("alert-danger");
                $("#divMsj").removeClass("alert-success");
            }
            $("#divMsj").show();
        }
    );
}

function checkButtons() {
    $("button[id^='btnProcesar']").each(function(i, element) {
        var strID = element.id.replace("btnProcesar", "");
        if ($("#NumeEstaCarr" + strID).val() != "7") {
            $(element).hide();
        }
    }, this);
}