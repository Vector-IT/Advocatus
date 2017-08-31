$(document).ready(function () {
    $(".clickable").click(function () {
		var url = $(this).data("url");

		var js = $(this).data("js");

		var result;
		//Ejecuto primero el js

		if (js != undefined) {
			result = eval(js);
		}

		if ((url != undefined) && ((result == undefined) || (result == true))) {
			location.href = url;
		}

	});

});

function busqSimple() {
	$("#bsqTipo").val("");
	$("#bsqEditorial").val("");
	$("#bsqCategoria").val("");
	$("#bsqAutor").val("");
	$("#bsqFecha").val("");
	$("#bsqTexto").val($("#bsqTexto1").val());
	$("#bsqSubcat").val("");

	$("#frmBusqueda").submit();
}

function busqAvanzada() {
	$("#bsqSubcat").val("");
}