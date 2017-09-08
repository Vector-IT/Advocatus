$(document).ready(function () {
    $("button[id^='btnProcesar']").each(function(i, element) {
        var strID = element.id.replace("btnProcesar", "");
        if ($("#NumeEstaCarr" + strID).val() != "7") {
            $(element).hide();
        }
    }, this);
});

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