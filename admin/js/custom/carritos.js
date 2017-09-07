$(document).ready(function () {
    $("button[id^='btnProcesar']").each(function(i, element) {
        var strID = element.id.replace("btnProcesar", "");
        if ($("#NumeEstaCarr").val() != "7") {
            $(element).hide();
        }
    }, this);
});

function verUsuario(strID) {
    if ($("#NumeUser" + strID).val() != '') {
        location.href= "objeto/usuarios&id=" + $("#NumeUser" + strID).val();
    }
    else {
        location.href= "objeto/invitados&id=" + $("#NumeInvi" + strID).val();
    }
}

function verProductos(strID) {
    location.href= "objeto/carritosdetalles&NumeCarr=" + strID;
}