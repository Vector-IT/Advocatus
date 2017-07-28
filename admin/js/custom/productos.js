var precioViejo;

$(document).ready(function () {
    $(".editable").click( function() {
        precioViejo = $(this).html();
        $(this).attr("contenteditable", true);
    });

    $(".editable").blur( function() {
        var numeProd = this.id.replace("ImpoVent", "");
        var nombProd = $("#NombProd" + numeProd).html();
        var impoVent = $(this).html();

        if (precioViejo != impoVent) {
            $("#txtHint").html(nombProd + "<br>Nuevo precio: " + impoVent);
            $("#divMsj").removeClass("alert-danger");
            $("#divMsj").addClass("alert-success");
            $("#divMsj").show();
        }
    });

});

function verImagenes(strID) {
    location.href= "objeto/productosimagenes&NumeProd=" + strID;
}