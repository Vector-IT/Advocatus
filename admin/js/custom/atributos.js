function habilitarOpciones() {
    $("button[id^='btnOpciones']").each(function (index, element) {
        var numeAtri = element.id.replace("btnOpciones", "");
        var numeTipoAtri = $("#NumeTipoAtri" + numeAtri).val();

        if (numeTipoAtri != "7") {
            $(element).hide();
        }
    });
}

function verOpciones(strID) {
    location.href= "objeto/atributosopciones.php?NumeAtri=" + strID;
}